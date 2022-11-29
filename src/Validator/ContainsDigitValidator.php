<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsDigitValidator extends ConstraintValidator
{
    public const CONTAINS_DIGIT_REGEX = '/\d/';

    public function validate($value, Constraint $constraint)
    {
        /* @var ContainsDigit $constraint */

        if (!$constraint instanceof ContainsDigit) {
            throw new UnexpectedTypeException($constraint, ContainsDigit::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        $this->checkIfContainDigit($value, $constraint);
    }

    protected function checkIfContainDigit($value, Constraint $constraint)
    {
        if (!preg_match(self::CONTAINS_DIGIT_REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
