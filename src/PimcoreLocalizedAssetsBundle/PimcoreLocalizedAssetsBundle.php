<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;

class PimcoreLocalizedAssetsBundle extends AbstractPimcoreBundle
{
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
