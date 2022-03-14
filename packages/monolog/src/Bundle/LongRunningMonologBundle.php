<?php

namespace LongRunning\Monolog\Bundle;

use LongRunning\Monolog\Bundle\DependencyInjection\Compiler\MonologCleanersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class LongRunningMonologBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new MonologCleanersPass());
    }
}
