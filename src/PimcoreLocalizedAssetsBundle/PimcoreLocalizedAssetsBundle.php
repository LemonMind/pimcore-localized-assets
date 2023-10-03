<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

class PimcoreLocalizedAssetsBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;
    use PackageVersionTrait;

    public function getJsPaths(): array
    {
        return [
            '/bundles/pimcorelocalizedassets/js/pimcore/startup.js',
        ];
    }

    public function getInstaller(): InstallerInterface|null
    {
                    $installerInstance = $this->container->get(Installer::class);

            if ($installerInstance instanceof InstallerInterface) {
                return $installerInstance;
                    }

        return null;
    }
}
