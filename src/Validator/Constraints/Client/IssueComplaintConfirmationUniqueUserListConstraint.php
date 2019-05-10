<?php

namespace App\Validator\Constraints\Client;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IssueComplaintConfirmationUniqueUserListConstraint extends Constraint
{
    public function validatedBy()
    {
        return IssueComplaintConfirmationUniqueUserListValidator::class;
    }
}