<?php

namespace App\Controller;

use App\DataStructure\LogDto;
use App\DataStructure\TrackedFoodDto;
use App\Entity\Log;
use App\Entity\TrackedFood;
use App\Entity\User;
use App\Form\EditLogType;
use App\Form\NewLogType;
use App\Form\TrackedFoodType;
use App\Repository\LogRepository;
use App\Repository\TrackedFoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
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

    #[Route('/log/{id}/edit', name: 'log_edit', methods: ['GET', 'POST'])]
    public function editLog(
        $id,
        Request $request,
        LogRepository $logRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $log = $logRepository->find($id);
        $user = $this->getUser();

        if (!$log) {
            throw $this->createNotFoundException();
        }

        if ($log->getTrackedFood()->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        if (Request::METHOD_POST === $request->getMethod() && $request->request->has('submit_delete')) {
            $entityManager->remove($log);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(EditLogType::class, $log);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($log);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('app/log-edit.html.twig', [
            'log' => $log,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/configure', name: 'configure')]
    public function configure(
        Request $request,
        TrackedFoodRepository $trackedFoodRepository,
        EntityManagerInterface $em
    ) {
        $dto = new TrackedFoodDto();
        $newTrackedFoodForm = $this->createForm(TrackedFoodType::class, $dto);
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
    public function deleteTrackedFood(
        Request $request,
        $id,
        TrackedFoodRepository $trackedFoodRepository,
        EntityManagerInterface $em
    ) {
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

    #[Route('/configure/tracked-food/{id}/edit', name: 'tracked-food-edit', methods: ['GET', 'POST'])]
    public function editTrackedFood(
        Request $request,
        $id,
        TrackedFoodRepository $trackedFoodRepository,
        EntityManagerInterface $em
    ) {
        $trackedFood = $trackedFoodRepository->find($id);
        if (!$trackedFood) {
            throw $this->createNotFoundException();
        }

        if ($this->getUser() !== $trackedFood->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $dto = new TrackedFoodDto();
        $dto->description = $trackedFood->getDescription();
        $dto->timeIntervalType = 'day';
        $dto->timeIntervalCount = $trackedFood->getTimeInterval()->d;
        $form = $this->createForm(TrackedFoodType::class, $dto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trackedFood->setDescription($dto->description);
            $trackedFood->setTimeInterval(
                \DateInterval::createFromDateString($dto->timeIntervalCount . ' ' . $dto->timeIntervalType)
            );

            $em->persist($trackedFood);
            $em->flush();

            return $this->redirectToRoute('configure');
        }

        return $this->render('tracked-food/edit.html.twig', [
            'trackedFood' => $trackedFood,
            'form' => $form->createView(),
        ]);
    }
}
