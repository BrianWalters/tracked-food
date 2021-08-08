<?php

namespace App\Controller;

use App\Repository\TrackedFoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrangePizzaController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(TrackedFoodRepository $trackedFoodRepository): Response
    {
        return $this->render('orange_pizza/index.html.twig', [
            'trackedFood' => $trackedFoodRepository->findBy(
                [
                    'user' => $this->getUser(),
                ]
            )
        ]);
    }

    #[Route('/configure', name: 'configure')]
    public function configure(TrackedFoodRepository $trackedFoodRepository)
    {
        return $this->render('orange_pizza/configure.html.twig', [
            'trackedFood' => $trackedFoodRepository->findBy(
                [
                    'user' => $this->getUser(),
                ]
            )
        ]);
    }
}
