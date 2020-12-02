<?php

namespace LongRunning\Sentry\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class LongRunningSentryExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
    }
}
