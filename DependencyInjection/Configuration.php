<?php

namespace Wucdbm\Bundle\LocaleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {

    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('wucdbm_locale');

        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('enabled')->end()
                            ->scalarNode('currency')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cookie_listener')
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('name')
                            ->defaultValue(null)
                        ->end()
                        ->scalarNode('duration')
                            ->defaultValue(null)
                        ->end()
                        ->scalarNode('path')
                            ->defaultValue(null)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('disabled_locale_redirect_listener')
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('use_preferred_locale')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        // ... add node definitions to the root of the tree

        return $treeBuilder;
    }

}