<?php

namespace App\Validator\Constraints\Client;

use App\Entity\VideoMaterial;
use App\Service\UserAwareServiceTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ComplaintVideoOwnerValidator
 * @package App\Validator\Constraints
 */
class VideoOwnerValidator extends ConstraintValidator
{
    use UserAwareServiceTrait;

    /**
     * Checks if the passed value is valid.
     *
     * @param VideoMaterial $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value->getOwner() !== $this->getUser())
        {
            $this->context->addViolation('video.only_own_items');
            return;
        }
    }
}
