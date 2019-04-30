<?php


namespace App\Service\Geo\Model;


class GeoLocation
{
    public $latitude;

    public $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}