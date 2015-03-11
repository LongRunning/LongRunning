<?php

namespace LongRunning\Bundle\LongRunningBundle;

use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\RegisterCleanersPass;
use LongRunning\Bundle\LongRunningBundle\DependencyInjection\LongRunningExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class LongRunningBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new LongRunningExtension('long_running');
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterCleanersPass());
    }
}
