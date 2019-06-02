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
                $this->context->addViolation('complaint_confirmation.you_can_request_only_one_signature_from_each_user');
                break;
            }

            $userIds[$userId] = true;
        }

    }
}