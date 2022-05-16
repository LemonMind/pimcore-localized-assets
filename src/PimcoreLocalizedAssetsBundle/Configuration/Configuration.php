<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Configuration;

class Configuration
{
    private array $config;

    public function setConfig(array $config = []): void
    {
        $this->config = $config;
    }

    public function getConfig(string $slot): mixed
    {
        return $this->config[$slot];
    }
}
