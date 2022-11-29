<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class MixedCaseValidator extends ConstraintValidator
{
    public const MIXED_CASE_REGEX = '/(?=.*[A-Z])(?=.*[a-z])/';

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var MixedCase $constraint */

        if (!$constraint instanceof MixedCase) {
            throw new UnexpectedTypeException($constraint, MixedCase::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }
        $this->checkIfContainMixedCase($value, $constraint);
    }

    protected function checkIfContainMixedCase(string $value, Constraint $constraint): void
    {
        if (!preg_match(self::MIXED_CASE_REGEX, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
