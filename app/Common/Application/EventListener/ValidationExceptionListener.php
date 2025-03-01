<?php

declare(strict_types=1);

namespace App\Common\Application\EventListener;

use App\Common\Infrastructure\Response\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ValidationExceptionListener implements EventSubscriberInterface
{
    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof BadRequestHttpException) {
            return;
        }

        $message = $exception->getMessage();

        $errors = json_decode($message, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($errors['errors'])) {
            $response = (new JsonResponse())->error($errors['errors'], 400);
            $event->setResponse($response);
        }
    }
}