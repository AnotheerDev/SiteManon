<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class => ['onBeforeEntityDeleted'],
        ];
    }

    public function onBeforeEntityDeleted(BeforeEntityDeletedEvent $event)
    {
        $entityInstance = $event->getEntityInstance();

        if (!($entityInstance instanceof User)) {
            return;
        }

        // Ici, vous mettez la logique de dissociation avant la suppression
        foreach ($entityInstance->getCreatPost() as $post) {
            $post->setUser(null);
        }

        foreach ($entityInstance->getCreatTopic() as $topic) {
            $topic->setUser(null);
        }

        foreach ($entityInstance->getUserQuote() as $quote) {
            $quote->setUser(null);
        }

        $this->entityManager->flush();
    }
}

