<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\JeuRepository;

final class JeuController extends AbstractController
{
    #[Route('/jeu', name: 'app_jeu')]
    public function index(JeuRepository $jeuRepository): Response
    {
        return $this->render('jeu/index.html.twig', [
            'jeux' => $jeuRepository->findAll(),
        ]);
    }

    #[Route('/fiche/{code}', name: 'app_fiche')]
    public function fiche(int $code, JeuRepository $jeuRepository ): Response
    {
        $jeu = $jeuRepository->find($code);

        return $this->render('jeu/fiche.html.twig', [
            'jeu' => $jeu,
        ]);
    }


}
