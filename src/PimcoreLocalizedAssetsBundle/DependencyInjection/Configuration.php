<?php

declare(strict_types=1);

namespace Lemonmind\PimcoreLocalizedAssetsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pimcore_localized_assets');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
