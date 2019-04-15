<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ComplaintVideoOwnerConstraint extends Constraint
{
    public function validatedBy()
    {
        return ComplaintVideoOwnerValidator::class;
    }
}
