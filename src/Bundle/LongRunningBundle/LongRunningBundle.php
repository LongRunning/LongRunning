<?php

namespace LongRunning\Bundle\LongRunningBundle;

use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\MonologCleanersPass;
use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\RegisterCleanersPass;
use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\SwiftMailerPass;
use LongRunning\Bundle\LongRunningBundle\DependencyInjection\LongRunningExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
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
        $container->addCompilerPass(new MonologCleanersPass());
        $container->addCompilerPass(new RegisterCleanersPass());
        $container->addCompilerPass(new SwiftMailerPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
