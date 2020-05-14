<?php

namespace App\EventListener;

use App\Entity\Offer;
use App\Entity\Submission;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OwnershipListener
{
    public function __construct(TokenStorageInterface $tokenStorage = null)
    {
        $this->tokenStorage = $tokenStorage;
    }

    // the listener methods receive an argument which gives you access to
    // both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ((!$entity instanceof Submission && !$entity instanceof Offer) ||  null === $this->tokenStorage->getToken() ) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if($entity instanceof Submission){
            $entity->setCandidate($user);
        }

        if($entity instanceof Offer){
            $entity->setOwner($user);
        }


    }
}