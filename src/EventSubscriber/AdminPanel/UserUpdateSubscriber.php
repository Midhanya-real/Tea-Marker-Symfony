<?php

namespace App\EventSubscriber\AdminPanel;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserUpdateSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    )
    {
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if(!($entity instanceof User)){
            return;
        }

        $hashPass = $this->hasher->hashPassword($entity, $entity->getPassword());
        $entity->setPassword($hashPass);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
