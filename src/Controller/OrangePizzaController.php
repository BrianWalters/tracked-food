<?php

namespace App\Controller;

use App\DataStructure\LogDto;
use App\DataStructure\TrackedFoodDto;
use App\Entity\Log;
use App\Entity\TrackedFood;
use App\Entity\User;
use App\Form\NewLogType;
use App\Form\NewTrackedFoodType;
use App\Repository\LogRepository;
use App\Repository\TrackedFoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrangePizzaController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        TrackedFoodRepository $trackedFoodRepository,
        LogRepository $logRepository,
        EntityManagerInterface $em
    ): Response {
        $dto = new LogDto();
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception('Wrong user class.');
        }

        $newLogForm = $this->createForm(NewLogType::class, $dto, [
            'user' => $user,
        ]);

        $newLogForm->handleRequest($request);

        if ($newLogForm->isSubmitted() && $newLogForm->isValid()) {
            $log = new Log(
                $dto->trackedFood
            );
            $log->setDescription($dto->description);

            $em->persist($log);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('orange_pizza/index.html.twig', [
            'newLogForm' => $newLogForm->createView(),
            'logs' => $logRepository->findLogsForUser($user),
            'trackedFood' => $trackedFoodRepository->findBy(
                [
                    'user' => $user,
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

    #[Route('/configure/tracked-food/{id}/delete', name: 'tracked-food-delete', methods: ['GET', 'POST'])]
    public function deleteTrackedFood(Request $request, $id, TrackedFoodRepository $trackedFoodRepository, EntityManagerInterface $em)
    {
        $trackedFood = $trackedFoodRepository->find($id);
        if (!$trackedFood) {
            throw $this->createNotFoundException();
        }

        if ($this->getUser() !== $trackedFood->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (Request::METHOD_POST === $request->getMethod()) {
            $em->remove($trackedFood);
            $em->flush();
            return $this->redirectToRoute('configure');
        }

        return $this->render('orange_pizza/tracked-food-delete.html.twig', [
            'trackedFood' => $trackedFood,
        ]);
    }
}
