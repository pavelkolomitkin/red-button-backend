<?php

namespace App\Service\Geo;

use App\Entity\OSMAddress;

class TestEnvGeoLocationService implements IGeoLocationService
{
    public function getOSMAddress(float $latitude, float $longitude): OSMAddress
    {
        $result = new OSMAddress();

        $result
            ->setLatitude($latitude)
            ->setLongitude($longitude)
            ->setState('Волгоградская область')
            ->setOsmId(1234554)
            ->setPlaceId(123456);


        return $result;
    }
}
