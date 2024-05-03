<?php

namespace App\EventSubscriber;

use App\Entity\Comment;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class EasyAdminCommentSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['updateCommentData']
        ];
    }

    public function updateCommentData(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Comment)) {
            return;
        }

        $entity->setModificationDate(new \DateTime());
    }
}
