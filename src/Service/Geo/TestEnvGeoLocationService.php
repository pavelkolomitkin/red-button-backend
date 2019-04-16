<?php

namespace App\Service\Geo;

use App\Entity\OSMAddress;

class TestEnvGeoLocationService implements IGeoLocationService
{
    public function getOSMAddress(float $latitude, float $longitude): OSMAddress
    {
        throw new \Exception('Implement it for tests!');
    }
}
