<?php

namespace App\Validator\Constraints\Client;

use App\Entity\IssuePicture;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ComplaintPictureOwnerValidator
 * @package App\Validator\Constraints
 */
class IssuePictureOwnerValidator extends ConstraintValidator
{
    use UserAwareServiceTrait;

    /**
     * Checks if the passed value is valid.
     *
     * @param IssuePicture $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value->getOwner() !== $this->getUser())
        {
            $this->context->addViolation('issue.picture.only_own_items');
            return;
        }
    }
}
