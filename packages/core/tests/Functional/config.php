<?php

use LongRunning\Core\DelegatingCleaner;
use LongRunning\Core\Functional\CleanerOne;
use LongRunning\Core\Functional\CleanerTwo;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $container->extension('framework', ['test' => null, 'secret' => 'secret']);

    $container->services()->alias('public_cleaner', DelegatingCleaner::class)->public();

    $container->services()->set(CleanerOne::class)->autoconfigure();
    $container->services()->set(CleanerTwo::class)->autoconfigure();
};
