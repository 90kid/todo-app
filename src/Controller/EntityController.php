<?php

namespace App\Controller;

use App\Entity\Interfaces\EntityInterface;
use App\Repository\AbstractEntityRepository;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityController extends AbstractFOSRestController
{
    public function __construct(
        protected readonly ValidatorInterface $validator
    ) {
    }

    public function handleSave(
        EntityInterface $entity,
        AbstractEntityRepository $entityRepository,
        ?Context $context = null
    ): Response {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            return $this->createAndHandleView($errors, Response::HTTP_BAD_REQUEST, $context);
        }
        $entityRepository->save($entity, true);

        return $this->createAndHandleView($entity, Response::HTTP_OK, $context);
    }

    public function createAndHandleView(
        EntityInterface|ConstraintViolationListInterface|Pagerfanta $data,
        int $httpCode,
        ?Context $context = null
    ): Response {
        $view = $this->view($data, $httpCode);
        if (!is_null($context)) {
            $view->setContext($context);
        }

        return $this->handleView($view);
    }
}
