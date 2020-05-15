<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Submission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class CandidateSubscriber implements EventSubscriberInterface
{
    /** @var TokenStorageInterface $tokenStorage */
    private $tokenStorage;
    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * InvitationSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $em
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setCandidate', EventPriorities::PRE_WRITE],
        ];
    }

    public function setCandidate(ViewEvent $event): void
    {
        $submission = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$submission instanceof Submission || Request::METHOD_PATCH !== $method) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if ($token) {
            $submission->setCandidate($token->getUser());
        }
        if ($submission->getStatus() === Submission::STATUS_CREATED) {
            $submission->setStatus(Submission::STATUS_ACCEPTED);
        }

        $this->em->flush();
    }
}