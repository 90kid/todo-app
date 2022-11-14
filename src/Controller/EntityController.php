<?php

namespace App\Controller;

use App\Entity\Interfaces\EntityInterface;
use App\Repository\AbstractEntityRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityController extends AbstractFOSRestController
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    // TODO zmienić nazwe bo jest słaba
    public function validateAndReturnResponse(
        EntityInterface $entity,
        AbstractEntityRepository $entityRepository
    ): Response {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            return $this->createAndHandleView($errors, Response::HTTP_BAD_REQUEST);
        }
        $entityRepository->save($entity, true);

        return $this->createAndHandleView($entity, Response::HTTP_OK);
    }

    public function createAndHandleView(
        EntityInterface|ConstraintViolationListInterface|Pagerfanta $data,
        int $httpCode
    ): Response {
        $view = $this->view($data, $httpCode);

        return $this->handleView($view);
    }
}
