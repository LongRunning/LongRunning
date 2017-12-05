<?php

namespace LongRunning\Bundle\LongRunningBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class LongRunningExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    /**
     * @var string
     */
    private $alias;

    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if ($mergedConfig['bernard']['enabled']) {
            $loader->load('bernard.yml');
        }

        if ($mergedConfig['doctrine_orm']['enabled']) {
            $loader->load('doctrine_orm.yml');
        }

        if ($mergedConfig['doctrine_dbal']['enabled']) {
            $loader->load('doctrine_dbal.yml');
        }

        if ($mergedConfig['monolog']['enabled']) {
            $loader->load('monolog.yml');
        }

        if ($mergedConfig['swift_mailer']['enabled']) {
            $loader->load('swift_mailer.yml');
        }

        if ($mergedConfig['sentry']['enabled']) {
            $loader->load('sentry.yml');
        }

        if ($mergedConfig['simple_bus_rabbit_mq']['enabled']) {
            $loader->load('simple_bus_rabbit_mq.yml');
        }
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($this->getAlias());
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function prepend(ContainerBuilder $container)
    {
        $enabledBundles = $container->getParameter('kernel.bundles');

        if (isset($enabledBundles['BernardBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'bernard' => [
                    'enabled' => true
                ],
            ]);
        }

        if (isset($enabledBundles['DoctrineBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'doctrine_orm' => [
                    'enabled' => true
                ],
                'doctrine_dbal' => [
                    'enabled' => true
                ]
            ]);
        }

        if (isset($enabledBundles['MonologBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'monolog' => [
                    'enabled' => true
                ]
            ]);
        }


        if (isset($enabledBundles['SentryBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'sentry' => [
                    'enabled' => true
                ]
            ]);
        }

        if (isset($enabledBundles['SwiftmailerBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'swift_mailer' => [
                    'enabled' => true
                ]
            ]);
        }

        if (isset($enabledBundles['SimpleBusRabbitMQBundleBridgeBundle'])) {
            $container->prependExtensionConfig($this->getAlias(), [
                'simple_bus_rabbit_mq' => [
                    'enabled' => true
                ]
            ]);
        }
    }
}
