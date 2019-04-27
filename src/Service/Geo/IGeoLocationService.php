<?php

namespace App\Service\Geo;

use App\Entity\OSMAddress;

interface IGeoLocationService
{
    public function getOSMAddress(float $latitude, float $longitude): OSMAddress;
}
