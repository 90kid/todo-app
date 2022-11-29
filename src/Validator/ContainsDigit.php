<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ContainsDigit extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The value "{{ value }}" must contains digit.';

    #[HasNamedArguments]
    public function __construct(bool $isPasswordValidation = false, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        if ($isPasswordValidation) {
            $this->message = 'Password must contain minimum one digit.';
        }
    }
}
