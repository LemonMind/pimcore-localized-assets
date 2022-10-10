<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Tests\Controller;

use Lemonmind\PimcoreLocalizedAssetsBundle\EventListener\FrontendListener;
use Pimcore\Model\Asset;
use Pimcore\Test\KernelTestCase;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\EventDispatcher\GenericEvent;

class EventListenerTest extends KernelTestCase
{
    private static Asset $asset;
    private FrontendListener $eventListener;
    private ReflectionMethod $methodAssetImageThumbnail;
    private ReflectionMethod $methodAssetPath;

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

    protected function setUp(): void
    {
        $this->eventListener = new FrontendListener();
        $reflector = new ReflectionClass($this->eventListener);
        $this->methodAssetImageThumbnail = $reflector->getMethod('assetImageThumbnail');
        $this->methodAssetPath = $reflector->getMethod('assetPath');
    }

    /**
     * @test
     */
    public function testAssetImageThumbnail(): void
    {
        $thumbnail = new Asset\Image\Thumbnail(self::$asset);

        $filesystemPath = $this->createStub(GenericEvent::class);
        $filesystemPath->method('getSubject')
            ->willReturn($thumbnail);

        $filesystemPath->method('getArgument')
            ->withConsecutive(['frontendPath'], ['pathReference'])
            ->willReturnOnConsecutiveCalls('/test-asset.png', ['src' => '/test-asset.png']);

        $filesystemPath->method('setArgument')
            ->withConsecutive(['frontendPath', '/name.png'], ['pathReference', ['src' => '/name.png']]);

        $this->methodAssetImageThumbnail->invokeArgs($this->eventListener, [$filesystemPath, '']);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function testAssetPath(): void
    {
        $thumbnail = new Asset\Image\Thumbnail(self::$asset);
        $thumbnail->getAsset();

        $filesystemPath = $this->createStub(GenericEvent::class);
        $filesystemPath->method('getSubject')
            ->willReturn($thumbnail->getAsset());

        $filesystemPath->method('getArgument')
            ->withConsecutive(['frontendPath'])
            ->willReturnOnConsecutiveCalls('/test-asset.png');

        $filesystemPath->method('setArgument')
            ->withConsecutive(['frontendPath', '/name.png']);

        $this->methodAssetPath->invokeArgs($this->eventListener, [$filesystemPath]);
        $this->assertTrue(true);
    }

    public static function tearDownAfterClass(): void
    {
        self::$asset->rollBack();
    }
}
