<?php

declare(strict_types=1);

namespace App\Domain\Subscriber;

use App\Domain\Dto\AuthenticationDto;
use JsonException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class RequestSubscriber
 */
final class RequestSubscriber implements EventSubscriberInterface
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
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     * @throws JsonException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $this->parseBody($request);
        $this->validateToken($request);
    }

    /**
     * @param Request $request
     * @return void
     * @throws JsonException
     */
    private function parseBody(Request $request): void
    {
        if ('json' === $request->getContentType() && $request->getContent()) {
            $json = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if (is_array($json)) {
                $request->request->replace($json);
            }
        }
    }

    /**
     * @param Request $request
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateToken(Request $request): void
    {
        $authenticationDto = new AuthenticationDto($request);
        $token             = $authenticationDto->getToken();
        if (!empty($token)) {
            if (!$this->cache->hasItem($authenticationDto->getTokenCacheKey())) {
                throw new UnauthorizedHttpException($token, message: 'Invalid token', code: 401);
            }
        }
    }
}
