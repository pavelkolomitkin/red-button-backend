<?php

namespace App\Service\Video\Link;

class YouTubeLink extends VideoLink
{
    private const URL_PATTERN = '/www\.youtube\.(com|ru)\/watch\?v=([A-Za-z0-9\-\_]+)/';

    static public function matchUrl($url): bool
    {
        preg_match(self::URL_PATTERN, $url, $matches);

        return !empty($matches[2]);
    }

    function getApiUrl(): string
    {
        return 'https://www.youtube.com/oembed?url=' . $this->originalUrl . '&format=json';
    }

    function getExternalId(): string
    {
        preg_match(self::URL_PATTERN, $this->originalUrl, $matches);
        return $matches[2];
    }
}
