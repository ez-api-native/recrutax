<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Submission;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SubmissionTokenGenerator implements EventSubscriberInterface
{
    public function __construct()
    {}

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addToken', EventPriorities::POST_WRITE],
        ];
    }

    public function addToken(ViewEvent $event): void
    {
        $submission = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$submission instanceof Submission || Request::METHOD_POST !== $method) {
            return;
        }

        $token = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyzAZERTYUIOPMLKJHGFDSQWXCVBN", 5)), 0, 5);
        $submission->setToken($token);

    }
}
