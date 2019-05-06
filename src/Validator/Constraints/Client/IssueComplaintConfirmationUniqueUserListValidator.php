<?php

namespace App\Validator\Constraints\Client;

use App\Entity\ComplaintConfirmation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IssueComplaintConfirmationUniqueUserListValidator
 * @package App\Validator\Constraints\Client
 */
class IssueComplaintConfirmationUniqueUserListValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param ComplaintConfirmation[] $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $userIds = [];

        foreach ($value as $item)
        {
            $userId = $item->getComplaint()->getClient()->getId();
            if (isset($userIds[$userId]))
            {
                $this->context->addViolation('You can request signature only one from each person!');
                break;
            }

            $userIds[$userId] = true;
        }

    }
}