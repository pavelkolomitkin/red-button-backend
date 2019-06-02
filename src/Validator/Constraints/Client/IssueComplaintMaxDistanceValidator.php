<?php


namespace App\Validator\Constraints\Client;

use App\Entity\Complaint;
use App\Entity\Issue;
use App\Service\Geo\GeoMeasureService;
use App\Service\Geo\Model\GeoLocation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class IssueComplaintMaxDistanceValidator
 * @package App\Validator\Constraints\Client
 */
class IssueComplaintMaxDistanceValidator extends ConstraintValidator
{
    /**
     * @var GeoMeasureService
     */
    private $geoMeasureService;

    private $maxDistance;


    /**
     * @param GeoMeasureService $geoMeasureService
     *
     * @required
     */
    public function setGeoMeasureService(GeoMeasureService $geoMeasureService)
    {
        $this->geoMeasureService = $geoMeasureService;
    }

    /**
     * @param $maxDistance
     */
    public function setMaxDistance($maxDistance)
    {
        $this->maxDistance = $maxDistance;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param Issue $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {

        /** @var Complaint $complaint */
        $complaint = $this->context->getObject()->getComplaint();

        $issuePoint = new GeoLocation($value->getAddress()->getLatitude(), $value->getAddress()->getLongitude());
        $complaintPoint = new GeoLocation($complaint->getAddress()->getLatitude(), $complaint->getAddress()->getLongitude());

        $distance = $this->geoMeasureService->getDistanceMetres($issuePoint, $complaintPoint);

        if ($distance > $this->maxDistance)
        {
            $this->context->addViolation('issue.complaint.max_distance');
            return;
        }

    }
}