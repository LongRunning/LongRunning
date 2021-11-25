<?php

namespace LongRunning\Core\Bundle;

use LongRunning\Core\Cleaner;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LongRunningBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(Cleaner::class)->addTag('long_running.cleaner');
    }
}
