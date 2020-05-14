<?php
namespace App\EventSuscriber;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;


class JWTCreatedListener {
    /**
    * @var Security
    */
    private $security;

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $this->security->getUser();

        $payload        = $event->getData();
        $payload['role'] = $user->getPlainRole();

        $event->setData($payload);
    }
}
