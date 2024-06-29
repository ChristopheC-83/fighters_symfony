<?php

namespace App\Controller;

use App\Entity\Fighters;
use App\Form\FightersType;
use App\Repository\SidesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CreateController extends AbstractController
{
    #[Route('/create/{id}', name: 'app_create', defaults: ['id' => null])]
    public function index(
        $id,
        SidesRepository $sidesRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {

        // Récupérer le combattant existant ou créer une nouvelle instance
        if ($id) {
            $fighter = $entityManager->getRepository(Fighters::class)->find($id);
            if (!$fighter) {
                throw $this->createNotFoundException('Fighter non trouvé pour l\'id ' . $id);
            }
            $originalImage = $fighter->getImage(); // Stocker l'image originale pour comparaison
        } else {
            $fighter = new Fighters();
            $originalImage = null;
        }

        $sides = $sidesRepository->findAll();

        // Créer le formulaire en passant le combattant et les côtés disponibles
        $form = $this->createForm(FightersType::class, $fighter, [
            'sides' => $sides,
        ]);

        // Gestion de la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Vérifier si une nouvelle image a été téléchargée
            $img = $form->get('image')->getData();

            if ($img) {
                // Gérer l'image si elle est modifiée ou ajoutée
                $imgName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImgName = $slugger->slug($imgName);
                $newImgName = $safeImgName . '-' . uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move(
                        $this->getParameter('images_fighters_directory'),
                        $newImgName
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image');
                    return $this->redirectToRoute('app_create', ['id' => $id]);
                }

                $fighter->setImage($newImgName);
            } elseif ($originalImage) {
                // Utiliser l'image originale si aucune nouvelle image n'est téléchargée
                $fighter->setImage($originalImage);
            }

            // Persister les données dans la base de données
            $entityManager->persist($fighter);
            $entityManager->flush();

            // Ajouter un message flash pour confirmer l'action réussie
            $this->addFlash('success', 'Le fighter a été ' . ($id ? 'modifié' : 'créé') . ' avec succès');

            // Rediriger vers la page d'accueil ou une autre page appropriée
            return $this->redirectToRoute('app_home');
        }

        // Afficher le formulaire avec les données actuelles
        return $this->render('create/index.html.twig', [
            'fighter_form' => $form->createView(),
            'fighter' => $fighter,
            'current_image' => $originalImage,
        ]);
    }
}
