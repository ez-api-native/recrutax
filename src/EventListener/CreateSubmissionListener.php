<?php

namespace App\EventListener;

use App\Entity\Submission;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\Mailer;

class CreateSubmissionListener
{
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Submission ) {
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
