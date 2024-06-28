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
    #[Route('/create', name: 'app_create')]
    public function index(
        SidesRepository $sidesRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $fighter = new Fighters();
        $sides = $sidesRepository->findAll();

        $form = $this->createForm(FightersType::class, $fighter, [
            'sides' => $sides,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('image')->getData();

            if ($img) {
                $imgName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImgName = $slugger->slug($imgName);
                $newImgName = $safeImgName . '-' . uniqid() . '.' . $img->guessExtension();

                try {
                    $img->move(
                        $this->getParameter('images_fighters_directory'), // Assure-toi que ce paramètre est bien défini dans services.yaml
                        $newImgName
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image');
                    return $this->redirectToRoute('app_create');
                }

                $fighter->setImage($newImgName);
            }

            $entityManager->persist($fighter);
            $entityManager->flush();

            $this->addFlash('success', 'Le fighter a été créé avec succès');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('create/index.html.twig', [
            'fighter_form' => $form->createView(),
        ]);
    }
}
