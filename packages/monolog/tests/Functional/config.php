<?php

use LongRunning\Core\DelegatingCleaner;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container->extension('framework', ['test' => null]);

    $container->services()->alias('public_cleaner', DelegatingCleaner::class)->public();
    $container->extension('monolog', [
        'handlers' => [
            'main' => [
                'type' => 'fingers_crossed',
                'action_level' => 'error',
                'handler' => 'file_log',
            ],
            'file_log' => [
                'type' => 'stream',
                'path' => '%kernel.logs_dir%/%kernel.environment%.log',
                'level' => 'debug',
            ],
        ],
    ]);
};
