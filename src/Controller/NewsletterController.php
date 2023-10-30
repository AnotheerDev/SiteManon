<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Form\ContactRequestType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter', name: 'app_newsletter')]
    public function index(): Response
    {
        return $this->render('newsletter/index.html.twig', [
            'controller_name' => 'NewsletterController',
        ]);
    }

    // #[Route('/newsletter/contact', name: 'app_contact')]
    // public function contact(ManagerRegistry $doctrine, Request $request): Response
    // {
    //     $contactRequest = new ContactRequest();
    //     $form = $this->createForm(ContactRequestType::class);
    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $contactRequest = $form->getData();
    //         $em = $doctrine->getManager();
    //         $em->persist($contactRequest);
    //         $em->flush();
    //         $this->addFlash('success', 'Votre demande a bien été enregistrée');
    //         return $this->redirectToRoute('app_contact');
    //     }

    //     return $this->render('newsletter/contact.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/newsletter/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactRequestType::class, $contactRequest);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Créer et envoyer l'e-mail
            $email = (new Email())
                ->from('votre@adresse.email') // Remplacez par votre adresse e-mail
                ->to('destinataire@adresse.email') // Remplacez par l'adresse e-mail du destinataire
                ->subject('Nouvelle demande de contact')
                ->text("Nom: {$contactRequest->getLastName()}\nPrénom: {$contactRequest->getFirstName()}\nEmail: {$contactRequest->getEmail()}\nSujet: {$contactRequest->getSubject()}\nMessage: {$contactRequest->getMessage()}");
            
            $mailer->send($email);

            $this->addFlash('success', 'Votre demande a bien été envoyée');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('newsletter/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
