<?php

namespace App\Domain\Handler;

use App\Domain\Entity\User;
use App\Domain\Handler\Interfaces\JwtHandlerInterface;
use Firebase\JWT\JWT;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;

final class JwtHandler implements JwtHandlerInterface
{
    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;

    /**
     * @param AdapterInterface $cache
     */
    public function __construct(AdapterInterface $cache) {
        $this->cache = $cache;
    }

    /**
     * @param User $user
     * @return string
     * @throws InvalidArgumentException
     */
    public function generate(User $user): string
    {
        $jwtData = $user->getJwtData();

        $token = JWT::encode(
            [
                'iat' => time(),
                'exp' => strtotime(self::JWT_TTL),
                'nbf' => strtotime(self::JWT_NBF),
                'iss' => $jwtData['key']
            ],
            $jwtData['secret'],
            $jwtData['algorithm'],
            $jwtData['key']
        );

        $cacheKey  = sprintf('%s-%s', $user->getId(), $token);
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($this->cache->hasItem($cacheKey)) {
            $this->cache->deleteItem($cacheKey);
        }
        $cacheItem->set($user->getUserIdentifier());
        $cacheItem->expiresAfter(strtotime(self::SESSION_TTL));
        $this->cache->save($cacheItem);

        return $token;
    }
}