<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Tests\Services;

use Lemonmind\PimcoreLocalizedAssetsBundle\Services\AssetService;
use Pimcore\Model\Asset;
use Pimcore\Test\KernelTestCase;

class AssetServiceTest extends KernelTestCase
{
    private static Asset $asset;

    public static function setUpBeforeClass(): void
    {
        self::$asset = new Asset();
        self::$asset->setFilename('test-asset.png');
        self::$asset->setParentId(1);
        self::$asset->setData(file_get_contents(__DIR__ . '/../img/img.png'));
        self::$asset->addMetadata('localized_asset_name', 'input', 'name', 'en');

        self::$asset->beginTransaction();
        self::$asset->save();
    }

    /**
     * @test
     */
    public function testGetByMetaNameAsset(): void
    {
        $asset = AssetService::getByMetaName('/name.png');
        $metaData = $asset->getMetadata();

        $this->assertEquals('localized_asset_name', $metaData[0]['name']);
        $this->assertEquals('en', $metaData[0]['language']);
        $this->assertEquals('input', $metaData[0]['type']);
        $this->assertEquals('name', $metaData[0]['data']);
    }

    /**
     * @test
     */
    public function testGetByMetaNameNull(): void
    {
        $asset = AssetService::getByMetaName('/someRandomName.png');

        $this->assertNull($asset);
    }

    public static function tearDownAfterClass(): void
    {
        self::$asset->rollBack();
    }
}
