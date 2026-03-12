<?php

namespace App\Controller;

use App\Service\RecipeStatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetteRepository;

final class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }


    #[Route('/recette/stats', name: 'app_stats')]
    public function stats(RecetteRepository $recetteRepository, RecipeStatsService $statsService): Response
    {
        $recipes = $recetteRepository->findAll();
        $stats = $statsService->computeDashboard($recipes, 5);
//        $stats = $recetteRepository->getStats();
        return $this->render('recette/stats.html.twig', [
            'stats' => $stats,
        ]);
    }
}
