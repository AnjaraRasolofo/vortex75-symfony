<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $features = [
            [
                'icon' => 'line-graph.png',
                'title' => 'Analyse de Marché en temps réel',
                'desc' => 'Recevez des analyses détaillées des tendances du marché.',
                'btn' => 'En savoir plus',
                'url' => '/actualites',
                'modal' => false
            ],
            [
                'icon' => 'ai.png',
                'title' => 'Trading Automatisé',
                'desc' => 'Utilisez des bots intelligents pour optimiser vos trades.',
                'btn' => 'Découvrir',
                'url' => '/experts',
                'modal' => false
            ],
            [
                'icon' => 'graduation.png',
                'title' => 'Formation Trading',
                'desc' => 'Apprenez les stratégies gagnantes pour mieux trader.',
                'btn' => 'Apprendre',
                'url' => '/tutoriels',
                'modal' => false
            ],
            [
                'icon' => 'loan.png',
                'title' => 'Gestion du Capital',
                'desc' => 'Apprenez à gérer efficacement votre portefeuille d’investissement.',
                'btn' => 'Voir plus',
                'url' => '/actualites/categorie/gestion-des-risques',
                'modal' => false
            ],
            [
                'icon' => 'attention.png',
                'title' => 'Alertes de Trading',
                'desc' => 'Recevez des alertes en temps réel.',
                'btn' => 'S\'inscrire',
                'url' => '/signaux',
                'id' => 'subscribe',
                'modal' => true
            ],
            [
                'icon' => 'strategy.png',
                'title' => 'Sécurité & Stratégie',
                'desc' => 'Protégez vos investissements avec des stratégies avancées.',
                'btn' => 'Commencer',
                'url' => '/actualites/categorie/strategies-de-trading',
                'modal' => false
            ]
        ];

        return $this->render('home/index.html.twig', [
            'title' => 'Accueil',
            'features' => $features
        ]);
    }

    #[Route('/guides', name: 'app_guide')]
    public function showGuide(): Response
    {
        return $this->render('home/guide.html.twig', [
            'title' => 'Guides',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function showAbout(): Response
    {
        return $this->render('home/about.html.twig', [
            'title' => 'A propos',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function showContact(): Response
    {
        return $this->render('home/contact.html.twig', [
            'title' => 'Contact',
        ]);
    }
}
