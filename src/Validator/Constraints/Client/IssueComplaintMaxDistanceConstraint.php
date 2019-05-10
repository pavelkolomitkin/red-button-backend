<?php


namespace App\Validator\Constraints\Client;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IssueComplaintMaxDistanceConstraint extends Constraint
{
    public function validatedBy()
    {
        return IssueComplaintMaxDistanceValidator::class;
    }
}