<?php

declare(strict_types=1);

namespace App\Domain\Subscriber;

use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class RequestSubscriber
 */
final class RequestSubscriber implements EventSubscriberInterface
{
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
}
