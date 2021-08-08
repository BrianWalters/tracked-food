<?php

namespace App\Controller;

use App\DataStructure\TrackedFoodDto;
use App\Entity\TrackedFood;
use App\Entity\User;
use App\Form\NewTrackedFoodType;
use App\Repository\TrackedFoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function configure(
        Request $request,
        TrackedFoodRepository $trackedFoodRepository,
        EntityManagerInterface $em
    ) {
        $dto = new TrackedFoodDto();
        $newTrackedFoodForm = $this->createForm(NewTrackedFoodType::class, $dto);
        $newTrackedFoodForm->handleRequest($request);

        if ($newTrackedFoodForm->isSubmitted() && $newTrackedFoodForm->isValid()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                throw new \Exception('Wrong user class.');
            }

            $trackedFood = new TrackedFood(
                $dto->description,
                \DateInterval::createFromDateString($dto->timeIntervalCount . ' ' . $dto->timeIntervalType),
                $user
            );

            $em->persist($trackedFood);
            $em->flush();

            return $this->redirectToRoute('configure');
        }

        return $this->render('orange_pizza/configure.html.twig', [
            'newTrackedFoodForm' => $newTrackedFoodForm->createView(),
            'trackedFood' => $trackedFoodRepository->findBy(
                [
                    'user' => $this->getUser(),
                ]
            )
        ]);
    }
}
