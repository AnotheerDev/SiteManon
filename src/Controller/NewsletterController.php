<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Form\ContactRequestType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
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
    public function contact(ManagerRegistry $doctrine, Request $request): Response
    {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactRequest = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($contactRequest);
            $em->flush();
            $this->addFlash('success', 'Votre demande a bien été enregistrée');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('newsletter/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
