<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;
use App\Repository\CategoryRepository;

class TemplateSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $categoryRepository;

    public function __construct(Environment $twig, CategoryRepository $categoryRepository)
    {
        $this->twig = $twig;
        $this->categoryRepository = $categoryRepository;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // Ne pas exécuter dans les sous-requêtes
        if (!$event->isMainRequest()) {
            return;
        }

        $categories = $this->categoryRepository->findAll();
        $this->twig->addGlobal('categories', $categories);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}

