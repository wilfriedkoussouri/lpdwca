<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Article;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => [['setPublicationDate'], ['setModificationDate'], ['assignAuthor']],
        ];
    }

    public function setPublicationDate(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Article)) {
            return;
        }

        if (!$entity->getPublicationDate()) {
            $publicationDate = new \DateTime();
            $entity->setPublicationDate($publicationDate);
        }
    }

    public function setModificationDate(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Article)) {
            return;
        }

        $modificationDate = new \DateTime();
        $entity->setModificationDate($modificationDate);
    }

    public function assignAuthor(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Article) {
            $token = $this->tokenStorage->getToken();

            if (null !== $token && $token->getUser() instanceof User) {
                $entity->setAuthor($token->getUser());
            }
        }
    }
}
