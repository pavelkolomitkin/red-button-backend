<?php

namespace App\Service\Video;

use App\Entity\VideoMaterial;
use App\Service\Video\Exception\ProvideVideoException;
use App\Service\Video\Link\VideoLink;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
        try
        {
            $response = $this->httpClient->request('GET', $link->getApiUrl());
        }
        catch (ClientException $exception)
        {
            throw new ProvideVideoException('external_video_service.cannot_get_an_access_to_this_video');
        }


        $responseCode = $response->getStatusCode();
        if ($responseCode === 401)
        {
            throw new ProvideVideoException('external_video_service.cannot_get_an_access_to_this_video');
        }
        elseif ($responseCode != 200)
        {
            throw new ProvideVideoException('external_video_service.external_service_is_not_available');
        }

        $result = json_decode($response->getBody()->getContents(), true);
        if (empty($result))
        {
            throw new ProvideVideoException('external_video_service.external_service_is_not_available');
        }

        return $result;
    }

    protected function initMaterialWithData(VideoMaterial $material, array $data)
    {
        $material
            ->setTitle($data['title'])
            ->setMetaData($data);
    }

}
