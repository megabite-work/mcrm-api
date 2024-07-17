<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: Events::JWT_CREATED)]
final class JwtCreatedListener
{
    public function __invoke(JWTCreatedEvent $event)
    {
        /**
         * @var User $user
        */
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}
