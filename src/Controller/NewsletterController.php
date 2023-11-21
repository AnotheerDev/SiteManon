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


    #[Route('/newsletter/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // Création d'une nouvelle demande de contact
        $contactRequest = new ContactRequest();
        // Création du formulaire
        $form = $this->createForm(ContactRequestType::class, $contactRequest);
        // Gestion de la requête
        $form->handleRequest($request);
        // Vérification de la soumission et de la validité du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Créer et envoyer l'e-mail
            $email = (new Email())
                ->from('votre@adresse.email') //  votre adresse e-mail
                ->to('destinataire@adresse.email') // l'adresse e-mail du destinataire
                ->subject('Nouvelle demande de contact')
                ->text("Nom: {$contactRequest->getLastName()}\nPrénom: {$contactRequest->getFirstName()}\nEmail: {$contactRequest->getEmail()}\nSujet: {$contactRequest->getSubject()}\nMessage: {$contactRequest->getMessage()}");
            
            // Envoi de l'e-mail
            $mailer->send($email);
            // Confirmation de l'envoi
            $this->addFlash('success', 'Votre demande a bien été envoyée');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('newsletter/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
