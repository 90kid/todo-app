<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsSpecialCharacterValidator extends ConstraintValidator
{
    public const CONTAINS_SPECIAL_CHARACTER_REGEX = '/[!@#$%^&*()_+\-=\[\]{};\':"\\\\|,.<>\/?`~]/';

    public function validate($value, Constraint $constraint)
    {
        /* @var ContainsSpecialCharacter $constraint */

        if (!$constraint instanceof ContainsSpecialCharacter) {
            throw new UnexpectedTypeException($constraint, ContainsSpecialCharacter::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        $this->checkIfContainSpecialCharacter($value, $constraint);
    }

    protected function checkIfContainSpecialCharacter($value, Constraint $constraint)
    {
        if (!preg_match(self::CONTAINS_SPECIAL_CHARACTER_REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
