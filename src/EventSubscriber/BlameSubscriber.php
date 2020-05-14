<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Offer;
use App\Entity\Submission;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlameSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * BlameSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function blame(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($method === Request::METHOD_POST && ($entity instanceof Offer || $entity instanceof Submission)) {
            $entity->setOwner($this->tokenStorage->getToken()->getUser());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['blame', EventPriorities::PRE_WRITE],
        ];
    }
}
