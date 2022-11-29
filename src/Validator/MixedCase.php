<?php

namespace App\Validator;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class MixedCase extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public string $message = 'The value "{{ value }}" is not mixed case.';

    #[HasNamedArguments]
    public function __construct(bool $isPasswordValidation = false, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        if ($isPasswordValidation) {
            $this->message = 'Password is not mixed case.';
        }
    }
}
