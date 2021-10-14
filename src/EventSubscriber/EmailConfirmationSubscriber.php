<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailConfirmationSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered($event)
    {
        $user = $event->getUser();
        $email = [
            'from' => 'team@sensiotv.com',
            'to' => $user->getEmail(),
            'subject' => 'Welcome to SensioTV ' . $user->getFirstName(),
        ];

        dump($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}
