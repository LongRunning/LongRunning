<?php

namespace LongRunning\Bundle\LongRunningBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root($this->alias);
        $rootNode
            ->children()
                ->arrayNode('doctrine_orm')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('doctrine_dbal')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->arrayNode('connections')
                            ->requiresAtLeastOnElement()
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('simple_bus_rabbit_mq')
                    ->canBeEnabled()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
