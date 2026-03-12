<?php

namespace App\Service;

class RecipeStatsService
{
    public function computeDashboard(array $recipes, int $limit = 3): array
    {
        $counts = [];

        // Compter les recettes par catégorie
        foreach ($recipes as $r) {
            $counts[$r->getCategorie()] = ($counts[$r->getCategorie()] ?? 0) + 1;
        }

        // Transformer en tableau et trier décroissant
        $topCategories = [];
        foreach ($counts as $cat => $total) {
            $topCategories[] = ['categorie' => $cat, 'total' => $total];
        }
        usort($topCategories, fn($a, $b) => $b['total'] <=> $a['total']);

        // Limiter au top N
        return ['byCategory' => array_slice($topCategories, 0, $limit)];
    }

}
