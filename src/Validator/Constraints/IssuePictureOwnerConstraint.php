<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IssuePictureOwnerConstraint extends Constraint
{
    public function validatedBy()
    {
        return IssuePictureOwnerValidator::class;
    }
}
