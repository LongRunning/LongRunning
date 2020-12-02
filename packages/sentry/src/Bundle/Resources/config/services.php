<?php

use LongRunning\Sentry\Cleaner\FlushSentryErrors;
use Psr\Log\LoggerInterface;
use Sentry\ClientInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(FlushSentryErrors::class)
        ->args([service(ClientInterface::class), service(LoggerInterface::class)])
        ->tag('long_running.cleaner');
};
