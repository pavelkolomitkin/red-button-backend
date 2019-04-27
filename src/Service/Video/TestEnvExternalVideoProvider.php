<?php


namespace App\Service\Video;


use App\Entity\VideoMaterial;
use App\Service\Video\Link\VideoLink;

class TestEnvExternalVideoProvider implements IExternalVideoProvider
{
    public function getMaterial(VideoLink $link): VideoMaterial
    {
        $result = new VideoMaterial();

        $result
            ->setOriginalLink($link->getOriginalUrl())
            ->setExternalId($link->getExternalId())
            ->setTitle('Test video')
            ->setMetaData([]);

        return $result;
    }
}
