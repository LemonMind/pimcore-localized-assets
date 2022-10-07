<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\Tests\Controller;

use Lemonmind\PimcoreLocalizedAssetsBundle\Controller\AssetController;
use Pimcore\Model\Asset;
use Pimcore\Test\KernelTestCase;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

class AssetControllerTest extends KernelTestCase
{
    private static Asset $asset;
    private AssetController $controller;
    private ReflectionMethod $method;

    public static function setUpBeforeClass(): void
    {
        self::$asset = new Asset();
        self::$asset->setFilename('test-asset.png');
        self::$asset->setParentId(1);
        self::$asset->setData(file_get_contents('https://icatcare.org/app/uploads/2018/07/Thinking-of-getting-a-cat.png'));
        self::$asset->addMetadata('localized_asset_name', 'input', 'name', 'en');

        self::$asset->beginTransaction();
        self::$asset->save();
    }

    protected function setUp(): void
    {
        $this->controller = new AssetController();
        $reflector = new ReflectionClass($this->controller);
        $this->method = $reflector->getMethod('singleAction');
    }

    /**
     * @test
     */
    public function testSingleActionLocalizedPath(): void
    {
        $request = $this->createStub(Request::class);
        $request->method('getPathInfo')
            ->willReturn('/name.png');

        $response = $this->method->invokeArgs($this->controller, [$request]);
        $this->assertEquals($response->getStatusCode(), '200');
    }

    /**
     * @test
     */
    public function testSingleActionNormalPath(): void
    {
        $request = $this->createStub(Request::class);
        $request->method('getPathInfo')
            ->willReturn('/test-asset.png');

        $response = $this->method->invokeArgs($this->controller, [$request]);
        $this->assertEquals($response->getStatusCode(), '200');
    }

    public static function tearDownAfterClass(): void
    {
        self::$asset->rollBack();
    }
}
