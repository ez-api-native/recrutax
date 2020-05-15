<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Submission;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class InvitationSubscriber implements EventSubscriberInterface
{
    /** @var Mailer $mailer */
    private $mailer;
    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * InvitationSubscriber constructor.
     * @param Mailer $mailer
     * @param EntityManagerInterface $em
     */
    public function __construct(Mailer $mailer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        $submission = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$submission instanceof Submission || Request::METHOD_POST !== $method) {
            return;
        }

        $submission->setToken((string)random_int(1000, 9999));
        $this->em->flush();

        $this->mailer
            ->sendMail(
                $submission->getEmail(),
                'createMailToken',
                ['token' => $submission->getToken()]
            );
    }
}