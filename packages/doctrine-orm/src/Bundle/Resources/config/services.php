<?php

use LongRunning\DoctrineORM\Cleaner\ClearEntityManagers;
use LongRunning\DoctrineORM\Cleaner\ResetClosedEntityManagers;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(ClearEntityManagers::class)
        ->args([service('doctrine'), service(LoggerInterface::class)])
        ->tag('long_running.cleaner');

    $container->services()
        ->set(ResetClosedEntityManagers::class)
        ->args([service('doctrine'), service(LoggerInterface::class)])
        ->tag('long_running.cleaner');
};
