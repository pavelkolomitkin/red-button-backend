<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VideoOwnerConstraint extends Constraint
{
    public function validatedBy()
    {
        return ComplaintVideoOwnerValidator::class;
    }
}
