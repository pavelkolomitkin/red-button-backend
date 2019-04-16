<?php

namespace App\Service\Video;

use App\Entity\VideoMaterial;
use App\Service\Video\Link\VideoLink;

interface IExternalVideoProvider
{
    public function getMaterial(VideoLink $link): VideoMaterial;
}
