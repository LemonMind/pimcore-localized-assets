<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class PimcoreLocalizedAssetsBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/pimcorelocalizedassets/js/pimcore/startup.js',
        ];
    }
}
