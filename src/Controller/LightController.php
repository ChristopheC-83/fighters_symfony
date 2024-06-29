<?php

namespace App\Controller;

use App\Repository\FightersRepository;
use App\Repository\SidesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LightController extends AbstractController
{
    #[Route('/light', name: 'app_light')]
    public function index(FightersRepository $fightersRepository, SidesRepository $sideRepository): Response
    {
        // on récupère le coté lumiere
        $side = $sideRepository->findOneBy(['side' => 'light']);
        // on extrait son id
        $sideId = $side->getId();

        //  on appelle la méthode findBy de FightersRepository pour récupérer les fighters du coté lumiere
        $fighters = $fightersRepository->findBy(['side' => $sideId], ['id' => 'DESC']);

        return $this->render('light/index.html.twig', [
            'fighters' => $fighters,
        ]);
    }
}
