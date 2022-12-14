<?php

namespace App\Controller;

use App\Controller\Utils\HasPaginationTrait;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/tasks', name: 'api_task_')]
class TaskController extends EntityController
{
    use HasPaginationTrait;

    public function __construct(
        ValidatorInterface $validator,
        private readonly TaskRepository $taskRepository,
    ) {
        parent::__construct($validator);
    }

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
        ParamFetcherInterface $paramFetcher
    ): Response {
        $page = intval($paramFetcher->get('page'));
        $limit = intval($paramFetcher->get('limit'));
        $orderByCreatedAtDirection = strval($paramFetcher->get('orderByCreatedAtDirection'));
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $queryBuilder = $this
            ->taskRepository
            ->userTasksQueryBuilder($currentUser, $orderByCreatedAtDirection);

        return $this->createAndHandleView(
            $this->paginate($queryBuilder, $page, $limit),
            Response::HTTP_OK,
            $this->getTaskViewContext(['taskSerialization'])
        );
    }

    #[Route(
        '/{id}',
        name: 'get',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['GET']
    )]
    public function getTask(Task $task): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::VIEW, $task);

        return $this->createAndHandleView(
            $task,
            Response::HTTP_OK,
            $this->getTaskViewContext(['taskSerialization'])
        );
    }

    #[Route(
        '/',
        name: 'create',
        methods: ['POST']
    )]
    public function createTask(Request $request): Response
    {
        $task = $this->setAllTaskProperties(new Task(), $request);

        return $this->handleSave(
            $task,
            $this->taskRepository,
            $this->getTaskViewContext(['taskSerialization'])
        );
    }

    #[Route(
        '/{id}',
        name: 'update',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['PUT']
    )]
    public function updateTask(Task $task, Request $request): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::EDIT, $task);
        $task = $this->setAllTaskProperties($task, $request);

        return $this->handleSave(
            $task,
            $this->taskRepository,
            $this->getTaskViewContext(['taskSerialization'])
        );
    }

    #[Route(
        '/{id}',
        name: 'delete',
        requirements: [
            'id' => '\d+',
        ],
        methods: ['DELETE']
    )]
    public function deleteTask(Task $task): Response
    {
        $this->denyAccessUnlessGranted(TaskVoter::DELETE, $task);
        $this->taskRepository->remove($task, true);

        return $this->createAndHandleView(
            $task,
            Response::HTTP_OK,
            $this->getTaskViewContext(['taskSerialization'])
        );
    }

    private function setAllTaskProperties(Task $task, Request $request): Task
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($request->getContent(), true);
        $name = strval($data['name'] ?? '');
        $description = strval($data['description'] ?? '');
        $done = $data['done'] ?? null;
        $done = is_null($done) ? null : boolval($data['done']);
        /** @var User $currentAuthUser */
        $currentAuthUser = $this->getUser();
        $task->setName($name)
            ->setDescription($description)
            ->setDone($done)
            ->setUser($currentAuthUser);

        return $task;
    }

    /**
     * @param array<string> $groups
     */
    private function getTaskViewContext(array $groups): Context
    {
        return (new Context())
            ->setGroups($groups);
    }
}
