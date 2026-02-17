<?php

namespace App\Controller\API;

use App\Entity\Signal;
use App\Repository\SignalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignalController extends AbstractController
{
    #[Route("/api/signal", methods: ['GET'])]
    public function index(SignalRepository $signalRepository): JsonResponse
    {
        $signals = $signalRepository->findAll();
        
        return $this->json($signals);
    }

    #[Route("/api/signal/{id}", methods:['GET'])]
    public function show($id, SignalRepository $signalRepository): JsonResponse
    {
        $signal = $signalRepository->findOneById($id);
        return $this->json($signal);
    }

    #[Route("/api/signal", methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $signal = new Signal();
        $signal->setName($data['name']);
        $signal->setType($data['type']);
        $signal->setEntry($data['entry']);
        $signal->setStopLoss($data['stopLoss']);
        $signal->setTakeProfit($data['takeProfit']);
        $signal->setResult($data['result']);
        $signal->setPublishedAt(
            new \DateTimeImmutable($data['publishedAt'])
        );

        $entityManager->persist($signal);
        $entityManager->flush();

        return $this->json($signal, 201);
    }

    #[Route("/api/signal/{id}", methods: ['PUT'])]
    public function update($id, Signal $signal, Request $request, EntityManagerInterface $entityManager)
    {

        $data = $request->toArray();

        if (isset($data['type'])) {
            $signal->setType($data['type']);
        }

        if (isset($data['result'])) {
            $signal->setResult($data['result']);
        }

        if (isset($data['name'])) {
            $signal->setName($data['name']);
        }

        if (isset($data['entry'])) {
            $signal->setEntry($data['entry']);
        }

        if (isset($data['stopLoss'])) {
            $signal->setStopLoss($data['stopLoss']);
        }

        if (isset($data['takeProfit'])) {
            $signal->setTakeProfit($data['takeProfit']);
        }

        if (isset($data['publishedAt'])) {
            $signal->setPublishedAt(
                new \DateTimeImmutable($data['publishedAt'])
            );
        }

        $entityManager->flush();

        return $this->json($signal, 201);
    }

    #[Route("/api/signal/{id}", methods:['delete'])]
    public function delete($id, SignalRepository $signalRepository, EntityManagerInterface $entityManager)
    {
        $signal = $signalRepository->findOneById($id);
        $entityManager->remove($signal);
        $entityManager->flush();

        return $this->json(['message' => 'Signal supprimé']);
    }
}