<?php

namespace App\Service\Video\Link;

abstract class VideoLink
{
    protected $originalUrl;

    public function __construct(string $originalUrl)
    {
        $this->originalUrl = $originalUrl;
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    abstract static public function matchUrl($url): bool;

    abstract public function getApiUrl(): string;

    abstract public function getExternalId(): string;
}
