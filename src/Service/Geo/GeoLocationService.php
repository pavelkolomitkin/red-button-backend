<?php

namespace App\Service\Geo;

use App\Entity\OSMAddress;
use App\Service\Geo\Exception\GeoLocationException;
use GuzzleHttp\Client;

class GeoLocationService implements IGeoLocationService
{
    const API_BASE_URL = 'https://nominatim.openstreetmap.org/reverse?format=json&namedetails=1&accept-language=ru';

    protected static $fieldMap = [
        'placeId' => 'place_id',
        'osmType' => 'osm_type',
        'osmId' => 'osm_id',
        'latitude' => 'lat',
        'longitude' => 'lon',
        'displayName' => 'display_name'
    ];

    protected static $addressFieldMap = [
        'city' => 'city',
        'county' => 'county',
        'state' => 'state',
        'road' => 'road',
        'village' => 'village',
        'stateDistrict' => 'state_district',
        'country' => 'country',
        'postCode' => 'postcode',
        'countryCode' => 'country_code',
    ];

    protected static $nameDetailsFieldsMap = [
        'nameDetails' => 'name'
    ];

    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function getOSMAddress(float $latitude, float $longitude): OSMAddress
    {
        // get api end point url by geo coordinates
        $apiUrl = $this->getLocationApiUrl($latitude, $longitude);

        // grab the info from external api
        $data = $this->getExternalData($apiUrl);

        // convert received data to a OSMAddress object
        $result = new OSMAddress();

        foreach (self::$fieldMap as $classNameField => $externalNameField)
        {
            if (!empty($data[$externalNameField]))
            {
                if (($classNameField === 'latitude') || ($classNameField === 'longitude'))
                {
                    $data[$externalNameField] = (float)$data[$externalNameField];
                }

                $result->{'set' . ucfirst($classNameField)}($data[$externalNameField]);
            }
        }

        $nameDetails = $data['namedetails'];
        foreach (self::$nameDetailsFieldsMap as $classNameField => $externalNameField)
        {
            if (!empty($nameDetails[$externalNameField]))
            {
                $result->{'set' . ucfirst($classNameField)}($nameDetails[$externalNameField]);
            }
        }

        $address = $data['address'];
        foreach (self::$addressFieldMap as $classNameField => $externalNameField)
        {
            if (!empty($address[$externalNameField]))
            {
                $result->{'set' . ucfirst($classNameField)}($address[$externalNameField]);
            }
        }

        return $result;
    }

    protected function getLocationApiUrl(float $latitude, float $longitude): string
    {
        return self::API_BASE_URL . '&lat=' . $latitude . '&lon=' . $longitude;
    }

    protected function getExternalData(string $url): array
    {
        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() != 200)
        {
            throw new GeoLocationException('The external service is not available!');
        }

        $result = json_decode($response->getBody()->getContents(), true);
        if (empty($result))
        {
            throw new GeoLocationException('Cannot get address by location!');
        }

        return $result;
    }
}
