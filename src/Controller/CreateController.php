<?php

namespace App\Controller;

use App\Form\FightersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateController extends AbstractController
{
    #[Route('/create', name: 'app_create')]
    public function index(): Response
    {

        $form = $this->createForm(FightersType::class);


        return $this->render('create/index.html.twig', [
            'fighter_form' => $form->createView(),
        ]);
    }
}
