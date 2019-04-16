<?php

namespace App\Service\Video;

use App\Entity\VideoMaterial;
use App\Service\Video\Exception\ProvideVideoException;
use App\Service\Video\Link\VideoLink;
use GuzzleHttp\Client;

class ExternalVideoProvider implements IExternalVideoProvider
{
    protected $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function getMaterial(VideoLink $link): VideoMaterial
    {
        $result = new VideoMaterial();

        // get info from external source
        $data = $this->getExternalData($link);

        $result
            ->setExternalId($link->getExternalId())
            ->setOriginalLink($link->getOriginalUrl());

        // initialize the material object
        $this->initMaterialWithData($result, $data);

        return $result;
    }

    protected function getExternalData(VideoLink $link): array
    {
        $response = $this->httpClient->request('GET', $link->getApiUrl());

        if ($response->getStatusCode() != 200)
        {
            throw new ProvideVideoException('The external service is not available!');
        }

        $data = json_decode($response->getBody()->getContents(), true);
        if (empty($data))
        {
            throw new ProvideVideoException('Cannot to receive data by link!');
        }

        return $data;
    }

    protected function initMaterialWithData(VideoMaterial $material, array $data)
    {
        $material
            ->setTitle($data['title'])
            ->setMetaData($data);
    }

}
