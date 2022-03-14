<?php

use LongRunning\Monolog\Bundle\DependencyInjection\Compiler\MonologCleanersPass;
use LongRunning\Monolog\Cleaner\ClearFingersCrossedHandlers;
use LongRunning\Monolog\Cleaner\CloseBufferHandlers;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return function (ContainerConfigurator $container) {
    $services = $container->services();

    $services
        ->set(ClearFingersCrossedHandlers::class)
        ->args([
            tagged_iterator(MonologCleanersPass::FINGERS_CROSSED_HANDLERS),
            service(LoggerInterface::class),
        ])
        ->tag('long_running.cleaner');

    $services
        ->set(CloseBufferHandlers::class)
        ->args([
            tagged_iterator(MonologCleanersPass::BUFFER_HANDLERS),
            service(LoggerInterface::class),
        ])
        ->tag('long_running.cleaner');
};
