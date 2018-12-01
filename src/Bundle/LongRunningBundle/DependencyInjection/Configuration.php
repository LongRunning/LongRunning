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
        $treeBuilder = new TreeBuilder($this->alias);

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root($this->alias);
        }

        $rootNode
            ->children()
                ->arrayNode('bernard')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('doctrine_orm')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('doctrine_dbal')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('monolog')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('swift_mailer')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('sentry')
                    ->canBeEnabled()
                ->end()
                ->arrayNode('simple_bus_rabbit_mq')
                    ->canBeEnabled()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
