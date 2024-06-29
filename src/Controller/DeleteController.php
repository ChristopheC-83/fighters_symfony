<?php

namespace App\Controller;

use App\Entity\Fighters;
use App\Repository\FightersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteController extends AbstractController
{
    #[Route('/delete/{id}', name: 'app_delete')]
    public function index($id, FightersRepository $fightersRepository,  EntityManagerInterface $entityManager, Request $request): Response
    {
        //  Le combattant existe il ?
        $fighter = $fightersRepository->findOneById($id);
        if(!$fighter){
            $this->addFlash('warning', "Ce personnage semble ne pas exister !");
            return $this->redirectToRoute('app_home');
        }
        // dd($fighter);

        // Si oui, on récupère son image 
        $imageName = $fighter->getImage();

        // Supprime le fighter de la base de données
        $entityManager->remove($fighter);
        $entityManager->flush();

        // Supprime le fichier de l'image si elle existe
        if ($imageName) {
            $filesystem = new Filesystem();
            $filesystem->remove($this->getParameter('images_fighters_directory') . '/' . $imageName);
        }

        $this->addFlash('success', 'Le fighter a été supprimé avec succès.');

         // Redirige vers la dernière page visitée
         $referer = $request->headers->get('referer');
         if ($referer) {
             return $this->redirect($referer);
         }
         
        return $this->redirectToRoute('app_home');
    }

        
    }

