<?php

use LongRunning\Core\DelegatingCleaner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container->extension('framework', ['test' => null]);
    $container->extension(
        'doctrine',
        [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'path'   => '%kernel.project_dir%/var/data.sqlite',
                'memory' => true,
            ],
            'orm' => null,
        ]);

    $container->services()->alias('public_cleaner', DelegatingCleaner::class)->public();
};
