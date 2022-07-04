<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Services;

class ImageService
{
    public static function generatePimcoreImageSrc(string $currentSrc, string $newFileName): string
    {
        $currentFileName = pathinfo($currentSrc, PATHINFO_FILENAME);

        return str_replace($currentFileName, $newFileName, $currentSrc);
    }
}
