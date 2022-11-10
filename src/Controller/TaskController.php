<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/tasks', name: 'api_task_')]
class TaskController extends AbstractController
{
    #[Route('/', name: 'get_paginated', methods: ['GET'])]
    #[QueryParam(
        name: 'page',
        requirements: '\d+',
        default: '1',
        description: 'Page of the overview.'
    )]
    #[QueryParam(
        name: 'limit',
        requirements: '\d+',
        default: '10',
        description: 'Page of the overview.'
    )]
    #[QueryParam(
        name: 'orderByCreatedAtDirection',
        requirements: '(?i)asc|desc(?-i)',
        default: 'asc',
        description: 'Direction order by createdAt field.'
    )]
    public function getPaginatedTasks(
        TaskRepository $taskRepository,
        ParamFetcherInterface $paramFetcher
    ): JsonResponse {
        $page = (int) $paramFetcher->get('page');
        $limit = (int) $paramFetcher->get('limit');
        $orderByCreatedAtDirection = (string) $paramFetcher->get('orderByCreatedAtDirection');
        $queryBuilder = $taskRepository->addOrderByCreatedAtQueryBuilder(direction: $orderByCreatedAtDirection);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $page,
            $limit,
        );

        return $this->json($pagerfanta);
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
