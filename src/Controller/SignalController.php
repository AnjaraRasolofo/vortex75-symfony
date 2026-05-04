<?php

namespace App\Controller;

use App\Repository\SignalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SignalController extends AbstractController
{
    #[Route('/signals', name: 'app_signals')]
    public function index(SignalRepository $signalRepo): Response
    {
        $signals = $signalRepo->findAll();

        return $this->render('signal/index.html.twig', [
            'signals' => $signals,
        ]);
    }
}
