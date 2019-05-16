<?php

namespace App\Repository;

/**
 * Trait GeoCriteriaAwareTrait
 * @package App\Repository
 */
trait GeoCriteriaAwareTrait
{
    public function hasGeoCriteria(array $criteria)
    {
        return $this->hasGeoBoundariesCriteria($criteria) || $this->hasGeoNearCriteria($criteria);
    }

    public function hasGeoBoundariesCriteria(array $criteria)
    {
        return isset($criteria['topLeftLatitude']) && isset($criteria['topLeftLongitude'])
            && isset($criteria['bottomRightLatitude']) && isset($criteria['bottomRightLongitude']);
    }

    public function hasGeoNearCriteria(array $criteria)
    {
        return isset($criteria['centerLatitude']) && isset($criteria['centerLongitude']);
    }
}