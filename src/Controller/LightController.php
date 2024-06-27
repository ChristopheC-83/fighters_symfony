<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LightController extends AbstractController
{
    #[Route('/light', name: 'app_light')]
    public function index(): Response
    {
        return $this->render('light/index.html.twig', [
            'controller_name' => 'LightController',
        ]);
    }
}
