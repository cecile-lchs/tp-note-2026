<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Jeu;
use App\Entity\Livre;
use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dev')]
final class GenerateDataController extends AbstractController
{
    #[Route('/generate-data', name: 'app_dev_generate_data', methods: ['GET'])]
    public function generate(EntityManagerInterface $entityManager): Response
    {
        // Sécurité minimale : uniquement en environnement dev
        if ($this->getParameter('kernel.environment') !== 'dev') {
            throw $this->createAccessDeniedException('Route disponible uniquement en environnement dev.');
        }

        $this->truncateTables($entityManager);

        $this->generateLivres($entityManager);
        $this->generateRecettes($entityManager);
        $this->generateEvenements($entityManager);
        $this->generateJeux($entityManager);

        $entityManager->flush();

        return new Response('<h1>Données générées avec succès ✅</h1>');
    }

    private function truncateTables(EntityManagerInterface $entityManager): void
    {
        $connection = $entityManager->getConnection();
        $connection->executeStatement('DELETE FROM livre');
        $connection->executeStatement('DELETE FROM recette');
        $connection->executeStatement('DELETE FROM evenement');
        $connection->executeStatement('DELETE FROM jeu');
    }

    private function generateLivres(EntityManagerInterface $entityManager): void
    {
        $titres = [
            'Symfony pour les débutants',
            'Maîtriser Twig',
            'Les secrets du PHP moderne',
            'Design web accessible',
            'Le guide du développeur full-stack',
            'Apprendre Doctrine ORM',
            'Le grand livre de l’UX',
            'Architecture web propre',
            'Développer avec API Platform',
            'Les bases du CSS moderne',
            'Construire une application métier',
            'Patterns de développement web',
            'Développement web universitaire',
            'Initiation à la performance web',
            'Tests et qualité logicielle',
            'Clean Code en PHP',
            'Frontend moderne',
            'Le guide complet de Symfony',
            'Composants réutilisables',
            'Internationalisation des applications',
        ];

        $auteurs = [
            'Alice Martin',
            'Bruno Petit',
            'Claire Dupont',
            'David Leroy',
            'Emma Roux',
            'François Bernard',
            'Julie Morel',
            'Lucas Garnier',
        ];

        $genres = ['Technique', 'UX', 'Backend', 'Frontend', 'Architecture', 'Accessibilité'];

        for ($i = 0; $i < 25; $i++) {
            $livre = new Livre();
            $livre->setTitre($titres[$i % count($titres)]);
            $livre->setAuteur($auteurs[array_rand($auteurs)]);
            $livre->setPrix((string) random_int(5, 35));
            $livre->setDatePublication((new \DateTime())->modify('-' . random_int(1, 800) . ' days'));
            $livre->setDisponible((bool) random_int(0, 1));
            $livre->setNbPages(random_int(120, 900));
            $livre->setGenre($genres[array_rand($genres)]);

            $entityManager->persist($livre);
        }

        // Quelques cas intéressants "forcés"
        $specials = [
            [
                'titre' => 'Livre très récent et en promo',
                'prix' => '8',
                'days' => 5,
                'disponible' => true,
                'pages' => 520,
                'genre' => 'Technique',
            ],
            [
                'titre' => 'Livre indisponible',
                'prix' => '18',
                'days' => 40,
                'disponible' => false,
                'pages' => 300,
                'genre' => 'Backend',
            ],
            [
                'titre' => 'Très gros ouvrage',
                'prix' => '22',
                'days' => 200,
                'disponible' => true,
                'pages' => 780,
                'genre' => 'Architecture',
            ],
        ];

        foreach ($specials as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre']);
            $livre->setAuteur($auteurs[array_rand($auteurs)]);
            $livre->setPrix($data['prix']);
            $livre->setDatePublication((new \DateTime())->modify('-' . $data['days'] . ' days'));
            $livre->setDisponible($data['disponible']);
            $livre->setNbPages($data['pages']);
            $livre->setGenre($data['genre']);

            $entityManager->persist($livre);
        }
    }

    private function generateRecettes(EntityManagerInterface $entityManager): void
    {
        $noms = [
            'Salade fraîcheur',
            'Tarte aux pommes',
            'Gratin dauphinois',
            'Soupe de légumes',
            'Cookies maison',
            'Pâtes carbonara',
            'Brownie chocolat',
            'Velouté de potiron',
            'Quiche lorraine',
            'Mousse au chocolat',
            'Croque-monsieur',
            'Riz cantonais',
            'Crumble pommes-poires',
            'Poulet rôti',
            'Cake salé',
        ];

        $categories = ['Entrée', 'Plat', 'Dessert', 'Boisson'];

        for ($i = 0; $i < 24; $i++) {
            $recette = new Recette();
            $recette->setNom($noms[$i % count($noms)] . ' ' . ($i + 1));
            $recette->setDescription('Une recette générée automatiquement pour les TP Symfony.');
            $recette->setTempsPreparation(random_int(10, 140));
            $recette->setDifficulte(random_int(1, 5));
            $recette->setDatePublication((new \DateTime())->modify('-' . random_int(1, 1000) . ' days'));
            $recette->setCategorie($categories[array_rand($categories)]);

            $entityManager->persist($recette);
        }

        $specialTimes = [15, 25, 45, 75, 130];
        foreach ($specialTimes as $index => $time) {
            $recette = new Recette();
            $recette->setNom('Recette exemple ' . ($index + 1));
            $recette->setDescription('Cas particulier pour les filtres Twig de durée.');
            $recette->setTempsPreparation($time);
            $recette->setDatePublication((new \DateTime())->modify('-' . random_int(1, 1000) . ' days'));

            $recette->setDifficulte(random_int(1, 5));
            $recette->setCategorie($categories[$index % count($categories)]);

            $entityManager->persist($recette);
        }
    }

    private function generateEvenements(EntityManagerInterface $entityManager): void
    {
        $titres = [
            'Conférence Symfony',
            'Atelier UX',
            'Workshop Vue.js',
            'Journée portes ouvertes',
            'Hackathon étudiant',
            'Séminaire accessibilité',
            'Table ronde innovation',
            'Forum métiers du web',
            'Matinée pédagogie numérique',
            'Rencontre développeurs',
        ];

        $lieux = ['Reims', 'Troyes', 'Paris', 'Nancy', 'Lyon', 'Campus principal', 'IUT', 'Salle A12'];

        for ($i = 0; $i < 24; $i++) {
            $startOffset = random_int(-90, 120);
            $dateDebut = (new \DateTime())->modify(($startOffset >= 0 ? '+' : '') . $startOffset . ' days');
            $dateFin = $dateDebut->modify('+' . random_int(1, 8) . ' hours');

            $evenement = new Evenement();
            $evenement->setTitre($titres[$i % count($titres)] . ' ' . ($i + 1));
            $evenement->setDateDebut($dateDebut);
            $evenement->setDateFin($dateFin);
            $evenement->setLieu($lieux[array_rand($lieux)]);
            $evenement->setPrix((string) random_int(0, 50));

            $entityManager->persist($evenement);
        }

        // Cas spécifiques : gratuits + futurs
        for ($i = 1; $i <= 4; $i++) {
            $dateDebut = (new \DateTime())->modify('+' . (10 * $i) . ' days');

            $evenement = new Evenement();
            $evenement->setTitre('Événement gratuit ' . $i);
            $evenement->setDateDebut($dateDebut);
            $evenement->setDateFin($dateDebut->modify('+2 hours'));
            $evenement->setLieu('Campus');
            $evenement->setPrix('0');

            $entityManager->persist($evenement);
        }
    }

    private function generateJeux(EntityManagerInterface $entityManager): void
    {
        $noms = [
            'Galaxy Quest',
            'Pixel Racer',
            'Mystic Valley',
            'Cyber Clash',
            'Fantasy World',
            'Urban Legends',
            'Speed Arena',
            'Castle Builder',
            'Rogue Planet',
            'Shadow Mission',
            'Ocean Story',
            'Infinite Battle',
        ];

        $platformes = ['PC', 'PS5', 'Xbox', 'Switch'];

        for ($i = 0; $i < 24; $i++) {
            $jeu = new Jeu();
            $jeu->setNom($noms[$i % count($noms)] . ' ' . ($i + 1));
            $jeu->setPlateforme($platformes[array_rand($platformes)]);
            $jeu->setNote((string) random_int(6, 19));
            $jeu->setPrix((string) random_int(10, 70));
            $jeu->setDateSortie((new \DateTime())->modify('-' . random_int(1, 600) . ' days'));
            $jeu->setNbAvis(random_int(5, 200));

            $entityManager->persist($jeu);
        }

        // Cas "tendances"
        for ($i = 1; $i <= 5; $i++) {
            $jeu = new Jeu();
            $jeu->setNom('Trending Game ' . $i);
            $jeu->setPlateforme($platformes[array_rand($platformes)]);
            $jeu->setNote((string) random_int(15, 19));
            $jeu->setPrix((string) random_int(20, 50));
            $jeu->setDateSortie((new \DateTime())->modify('-' . random_int(5, 40) . ' days'));
            $jeu->setNbAvis(random_int(25, 120));

            $entityManager->persist($jeu);
        }
    }
}