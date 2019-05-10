<?php

namespace App\Validator\Constraints\Client;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VideoOwnerConstraint extends Constraint
{
    public function validatedBy()
    {
        return VideoOwnerValidator::class;
    }
}
