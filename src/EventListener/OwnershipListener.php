<?php

namespace App\EventListener;

use App\Entity\Offer;
use App\Entity\Submission;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OwnershipListener
{
    /** @var TokenStorageInterface $tokenStorage */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Submission || !$entity instanceof Offer ||  null === $this->tokenStorage->getToken() ) {
            return;
        }

        /** @var UserInterface $user */
        $user = $this->tokenStorage->getToken()->getUser();

        if($entity instanceof Submission && \in_array('ROLE_CANDIDATE', $user->getRoles())){
            $entity->setCandidate($user);
        }

        if($entity instanceof Offer){
            $entity->setOwner($user);
        }


    }
}
