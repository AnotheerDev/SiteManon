<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassificationController extends AbstractController
{
    #[Route('/classification', name: 'app_classification')]
    public function index(): Response
    {
        return $this->render('classification/index.html.twig', [
            'controller_name' => 'ClassificationController',
        ]);
    }
}
