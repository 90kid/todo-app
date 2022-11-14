<?php

namespace App\Validator;

use App\Entity\Interfaces\EntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator
    ) {
    }

    public function validate(EntityInterface $entity, ServiceEntityRepository $entityRepository)
    {
    }
}
