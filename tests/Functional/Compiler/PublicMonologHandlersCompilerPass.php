<?php

declare(strict_types=1);

namespace LongRunning\Tests\Functional\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PublicMonologHandlersCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) : void
    {
        $container->getDefinition('monolog.handler.fingers_crossed')->setPublic(true);
        $container->getDefinition('monolog.handler.fingers_crossed_test')->setPublic(true);
        $container->getDefinition('monolog.handler.buffer_test')->setPublic(true);
        $container->getDefinition('long_running.delegating_cleaner')->setPublic(true);

        if ($container->hasDefinition('monolog.logger')) {
            $container->getDefinition('monolog.logger')->setPublic(true);
        }

        if ($container->hasAlias('logger')) {
            $container->getAlias('logger')->setPublic(true);
        }
    }
}
