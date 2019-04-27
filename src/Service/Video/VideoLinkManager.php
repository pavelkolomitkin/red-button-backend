<?php

namespace App\Service\Video;

use App\Service\Video\Exception\VideoLinkException;
use App\Service\Video\Link\VideoLink;
use App\Service\Video\Link\YouTubeLink;

class VideoLinkManager
{
    private static $linkClasses = [
        YouTubeLink::class
    ];

    /**
     * @param string $url
     * @return VideoLink
     * @throws VideoLinkException
     */
    public function create(string $url): VideoLink
    {
        $result = null;

        foreach (self::$linkClasses as $linkClass)
        {
            if ($linkClass::matchUrl($url))
            {
                $result = new $linkClass($url);
                break;
            }
        }

        if ($result === null)
        {
            throw new VideoLinkException('Cannot recognize the video link "' . $url . '"!');
        }

        return $result;
    }
}
