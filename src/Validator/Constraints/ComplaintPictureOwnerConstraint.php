<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ComplaintPictureOwnerConstraint extends Constraint
{
    public function validatedBy()
    {
        return ComplaintPictureOwnerValidator::class;
    }
}
