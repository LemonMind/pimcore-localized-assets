<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Tests\Services;

use Lemonmind\PimcoreLocalizedAssetsBundle\Services\ImageService;
use PHPUnit\Framework\TestCase;

class ImageServiceTest extends TestCase
{
    /**
     * @test
     */
    public function testGeneratePimcoreImageSrc(): void
    {
        $currentSrc = '/Brand%20Logos/302/image-thumb__302__product_detail_manufacturer/Jaguar_2012_logo.png';
        $localizedImageName = 'jaguar en';
        $expected = '/Brand%20Logos/302/image-thumb__302__product_detail_manufacturer/jaguar en.png';

        $newSrc = ImageService::generatePimcoreImageSrc($currentSrc, $localizedImageName);
        $this->assertEquals($expected, $newSrc);
    }
}
