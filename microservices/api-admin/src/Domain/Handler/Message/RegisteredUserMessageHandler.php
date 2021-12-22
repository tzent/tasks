<?php

declare(strict_types=1);

namespace App\Domain\Handler\Message;

use App\Domain\Message\ApiConsumerMessage;
use App\Domain\Message\RegisteredUserMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class RegisteredUserMessageHandler
{
    private const JWT_ALGORITHM = 'HS512';

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $messageBus;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $apiGatewayClient;

    /**
     * @param MessageBusInterface $messageBus
     * @param HttpClientInterface $apiGatewayClient
     */
    public function __construct(
        MessageBusInterface $messageBus,
        HttpClientInterface $apiGatewayClient
    ) {
        $this->messageBus = $messageBus;
        $this->apiGatewayClient = $apiGatewayClient;
    }

    /**
     * @param RegisteredUserMessage $message
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function __invoke(RegisteredUserMessage $message): void
    {
        $consumer = $this->addConsumer($message->getId(), $message->getEmail());

        if (empty($consumer['id'])) {
            throw new \LogicException(sprintf('Consumer for user with id %s not created', $message->getId()));
        }

        $jwtData = $this->addJwtData($consumer['id']);
        $this->addAcl($consumer['id'], $message->getRoles());

        if (is_array($jwtData)) {
            $this->messageBus->dispatch(
                new ApiConsumerMessage($message->getId(), $jwtData)
            );
        } else {
            throw new \LogicException(sprintf('JWT credentials for user with id %s not added', $message->getId()));
        }
    }

    /**
     * @param string $id
     * @param string $email
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function addConsumer(string $id, string $email): array
    {
        $response = $this->apiGatewayClient->request(
            'GET',
            sprintf('/consumers/%s', $email)
        );

        if ($response->getStatusCode() === Response::HTTP_OK) {
            $response = $this->apiGatewayClient->request(
                'PATCH',
                sprintf('/consumers/%s', $email),
                [
                    'body' => json_encode([
                        'custom_id' => $id
                    ])
                ]
            );

            if ($response->getStatusCode() === Response::HTTP_OK) {
                return $response->toArray();
            }
        } else {
            $response = $this->apiGatewayClient->request('POST', '/consumers', [
                'body' => json_encode([
                    'username'  => $email,
                    'custom_id' => $id,
                    'tags'      => ['user-level']
                ])
            ]);

            if ($response->getStatusCode() === Response::HTTP_CREATED) {
                return $response->toArray();
            }
        }

        return [];
    }

    /**
     * @param string $consumerId
     * @return array|null
     */
    private function addJwtData(string $consumerId): ?array
    {
        try {
            $response = $this->apiGatewayClient->request(
                'GET',
                sprintf('/consumers/%s/jwt', $consumerId)
            );

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $body = $response->toArray();
                if (!empty($body['data'])) {
                    return current($body['data']);
                }
            }

            $response = $this->apiGatewayClient->request(
                'POST',
                sprintf('/consumers/%s/jwt', $consumerId),
                [
                    'body' => json_encode([
                        'consumer'  => $consumerId,
                        'algorithm' => RegisteredUserMessageHandler::JWT_ALGORITHM,
                        'secret'    => bin2hex(openssl_random_pseudo_bytes(16))
                    ])
                ]
            );

            if ($response->getStatusCode() === Response::HTTP_CREATED) {
                return $response->toArray();
            }
        } catch (ExceptionInterface $exception) {
            error_log($exception->getMessage());
        }

        return null;
    }

    /**
     * @param string $consumerId
     * @param array $groups
     * @return void
     */
    private function addAcl(string $consumerId, array $groups): void
    {
        try {
            foreach ($groups as $group) {
                $this->apiGatewayClient->request(
                    'POST',
                    sprintf('/consumers/%s/acls', $consumerId),
                    [
                        'body' => json_encode([
                            'group' => $group
                        ])
                    ]
                );
            }
        } catch (ExceptionInterface $exception) {
            error_log($exception->getMessage());
        }
    }
}