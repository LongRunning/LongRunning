<?php

use LongRunning\Core\Cleaner;
use LongRunning\Core\DelegatingCleaner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(DelegatingCleaner::class)
        ->args([tagged_iterator('long_running.cleaner')]);

    $container->services()->alias(Cleaner::class, DelegatingCleaner::class);
};
