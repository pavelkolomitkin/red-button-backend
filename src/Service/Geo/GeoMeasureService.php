<?php

namespace App\Service\Geo;

use App\Service\Geo\Model\GeoLocation;

/**
 * Class GeoMeasureService
 * @package App\Service\Geo
 */
class GeoMeasureService
{
    const EARTH_RADIUS = 6371000;

    public function getDistanceMetres(GeoLocation $point1, GeoLocation $point2)
    {
        $latFrom = deg2rad($point1->latitude);
        $lonFrom = deg2rad($point1->longitude);
        $latTo = deg2rad($point2->latitude);
        $lonTo = deg2rad($point2->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * self::EARTH_RADIUS;
    }
}