<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
//    #[Route('/task', name: 'app_task_get_all', methods: ['GET'])]
//    public function getAllTasks(TaskRepository $taskRepository): JsonResponse
//    {
//        $tasks = $taskRepository->findAll();
//
//        return $this->json($tasks);
//    }

    #[Route(
        '/task/{id}',
        name: 'app_task_get',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET'],
    )]
    public function getTask(TaskRepository $taskRepository, int $id = 1): JsonResponse
    {
        $task = $taskRepository->find($id);
        if (!$task) {
            throw $this->createNotFoundException();
        }

        return $this->json($task);
    }

    #[Route('/task', name: 'app_task_create', methods: ['POST'])]
    public function createTask(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $task = (new Task())
            ->setName('zadanie1')
            ->setDescription('Opis zadania')
            ->setDone(false);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->json(['task' => $task]);
    }
}
