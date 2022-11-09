<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/tasks', name: 'task_')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'get_paginated', methods: ['GET'])]
    public function getPaginatedTasks(): JsonResponse
    {
        // TODO
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TaskController.php',
        ]);
    }

    #[Route(
        '/{id}',
        name: 'get',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    public function getTask(Task $task): JsonResponse
    {
        return $this->json(['data' => $task]);
    }

    #[Route(
        '/',
        name: 'create',
        methods: ['POST']
    )]
    public function createTask(TaskRepository $taskRepository): JsonResponse
    {
        // TODO validation
        $task = (new Task())
            ->setName('zadanie1')
            ->setDescription('opis')
            ->setDone(false);
        $taskRepository->save($task, true);

        return $this->json($task);
    }

    #[Route(
        '/{id}',
        name: 'update',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PUT']
    )]
    public function updateTask(Task $task, TaskRepository $taskRepository): JsonResponse
    {
        // TODO validacja
        $task->setName('zadanie2')
            ->setDescription('nowy opis')
            ->setDone(true);
        $taskRepository->save($task, true);

        return $this->json($task);
    }

    #[Route(
        '/{id}',
        name: 'delete',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['DELETE']
    )]
    public function deleteTask(Task $task, TaskRepository $taskRepository): JsonResponse
    {
        $taskRepository->remove($task, true);

        return $this->json($task);
    }
}
