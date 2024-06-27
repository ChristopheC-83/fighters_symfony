<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $fighters = [
            [
                'name'=>'Kikisan',
                'image'=>'sorcier.jpg',
                'health'=>100,
                'magic'=>100,
                'power'=>20,
                'side'=>'light',
            ],
            [
                'name'=>'Smaug',
                'image'=>'kikisan.jpg',
                'health'=>100,
                'magic'=>100,
                'power'=>20,
                'side'=>'dark',
            ],
        ];
        return $this->render('home/index.html.twig');
    }
}
