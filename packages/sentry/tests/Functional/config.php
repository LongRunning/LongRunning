<?php

use LongRunning\Core\DelegatingCleaner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container->extension('framework', ['test' => null]);

    $container->services()->alias('public_cleaner', DelegatingCleaner::class)->public();
};
