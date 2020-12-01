<?php

namespace App\EventSubscriber;

use App\Entity\Images;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\ORM\EntityManager;


class RegistrationNotifySubscriber implements EventSubscriberInterface
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // le nom de l'event et le nom de la fonction qui sera déclenché
            Events::IMAGE_REGISTERED => 'onImageRegistrated',
        ];
    }

    public function onImageRegistrated(GenericEvent $event): void
    {
        /** @var Images $image */
        $image = $event->getSubject();

        $user = $image->getAuthor();
        $user->setCredit($user->getCredit()+1);
        $this->em->flush();

    }
}
