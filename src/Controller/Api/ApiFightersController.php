<?php

namespace App\Controller\Api;

use App\Repository\FightersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiFightersController extends AbstractController
{
    #[Route('/api/fighters', name: 'api_fighters')]
    public function index(FightersRepository $fightersRepository, Packages $packages, RequestStack $requestStack): Response
    {
        // Récupère le schéma (http/https) et l'hôte (domaine)
        $baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();


        $fighters = $fightersRepository->findAll();

        // Transformer les entités en tableau associatif
        $data = [];
        foreach ($fighters as $fighter) {

            $side = $fighter->getSide();
            $imageUrl = $fighter->getImage() ? $packages->getUrl('images/fighters/' . $fighter->getImage()) : null;

            $data[] = [
                'id' => $fighter->getId(),
                'name' => $fighter->getName(),
                'image' => $baseUrl.$imageUrl,
                'health' => $fighter->getHealth(),
                'magic' => $fighter->getMagic(),
                'power' => $fighter->getPower(),
                'side' => $side->getSide()
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
