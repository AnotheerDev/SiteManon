<?php

namespace App\Controller;

use App\Form\ContactRequestType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(): Response
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }

    #[Route('/newsletter/contact', name: 'app_contact')]
    public function contact(): Response
    {
        $form = $this->createForm(ContactRequestType::class);
        return $this->render('newsletter/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
