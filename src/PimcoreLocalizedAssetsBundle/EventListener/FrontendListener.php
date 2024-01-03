<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\EventListener;

use Lemonmind\PimcoreLocalizedAssetsBundle\Services\ImageService;
use Pimcore\Model\Asset;
use Symfony\Component\EventDispatcher\GenericEvent;

class FrontendListener
{
    public function assetImageThumbnail(GenericEvent $filesystemPath, string $frontendPath): void
    {
        $asset = $filesystemPath->getSubject()->getAsset();
        $currentSrc = $filesystemPath->getArgument('frontendPath');

        if (null === $asset) {
            return;
        }

        $localizedImageName = $asset->getMetadata('localized_asset_name');

        if (null === $localizedImageName) {
            return;
        }

        $newSrc = ImageService::generatePimcoreImageSrc($currentSrc, $localizedImageName);

        $pathReference = $filesystemPath->getArgument('pathReference');
        $pathReference['src'] = $newSrc;

        $filesystemPath->setArgument('frontendPath', $newSrc);
        $filesystemPath->setArgument('pathReference', $pathReference);
    }

    public function assetVideoImageThumbnail(GenericEvent $filesystemPath, string $frontendPath): void
    {
    }

    public function assetVideoThumbnail(GenericEvent $filesystemPath, string $frontendPath): void
    {
    }

    public function assetDocumentImageThumbnail(GenericEvent $filesystemPath, string $frontendPath): void
    {
    }

    public function assetPath(GenericEvent $frontendPath): void
    {
        $asset = $frontendPath->getSubject();
        $currentSrc = $frontendPath->getArgument('frontendPath');

        if (null === $asset) {
            return;
        }

        $localizedImageName = $asset->getMetadata('localized_asset_name');

        if (null === $localizedImageName) {
            return;
        }

        $newSrc = ImageService::generatePimcoreImageSrc($currentSrc, $localizedImageName);

        $frontendPath->setArgument('frontendPath', $newSrc);
    }
}
