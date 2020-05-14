<?php

namespace App\EventListener;

use App\Entity\Submission;
use App\Service\Mailer;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CreateSubmissionListener
{
    /** @var Mailer $mailer */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Submission) {
            return;
        }

        $this->mailer
            ->sendMail(
                $entity->getEmail(),
                'createMailToken',
                ['token' => $entity->getToken()]
            );

    }
}
