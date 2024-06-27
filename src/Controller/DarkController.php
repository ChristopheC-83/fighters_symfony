<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DarkController extends AbstractController
{
    #[Route('/dark', name: 'app_dark')]
    public function index(): Response
    {
        return $this->render('dark/index.html.twig', [
            'controller_name' => 'DarkController',
        ]);
    }
}
