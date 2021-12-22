<?php

declare(strict_types=1);

namespace App\Domain\Subscriber;

use JsonException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionListener
 */
final class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * ExceptionListener constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logger->error(
            $exception->getMessage(),
            [
                'code'    => $exception->getCode(),
                'trace'   => $exception->getTraceAsString(),
                'request' => $event->getRequest()
            ]
        );

        $code = $exception->getCode();
        if (empty(Response::$statusTexts[$code])) {
            $code = match (get_class($exception)) {
                JsonException::class => 400,
                default => 500,
            };
        }

        $event->setResponse(new JsonResponse(
            ['message' => Response::$statusTexts[$code]],
            $code
        ));
    }
}
