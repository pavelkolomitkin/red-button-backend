<?php

namespace App\Service\Geo;

use App\Service\Geo\Model\GeoLocation;

/**
 * Class GeoMeasureService
 * @package App\Service\Geo
 */
class GeoMeasureService
{
    public function getDistanceKm(GeoLocation $point1, GeoLocation $point2)
    {
        $R = 6371; // Radius of the earth in km
        $dLat = deg2rad($point2->latitude - $point1->latitude);  // deg2rad below
        $dLon = deg2rad( $point2->longitude - $point1->longitude);
        $a = sin($dLat/2) * sin($dLat/2) + cos($this->deg2rad($point1->latitude)) * cos(deg2rad($point2->latitude)) * sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c; // Distance in km

        return $d;
    }

    private function deg2rad ($deg)
    {
        return $deg * (pi()/180);
    }
}