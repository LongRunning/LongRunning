<?php

namespace LongRunning\Monolog\Bundle\DependencyInjection\Compiler;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\FingersCrossedHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MonologCleanersPass implements CompilerPassInterface
{
    public const FINGERS_CROSSED_HANDLERS = 'long_running.monolog.fingers.crossed.handlers';
    public const BUFFER_HANDLERS = 'long_running.monolog.buffer.handlers';

    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $serviceId => $definition) {
            if (0 === strpos($serviceId, 'monolog.handler.')) {
                $class = $container->getParameterBag()->resolveValue($definition->getClass());
                if (is_a($class, FingersCrossedHandler::class, true)) {
                    $definition->addTag(self::FINGERS_CROSSED_HANDLERS);
                }
            }
        }

        foreach ($container->getDefinitions() as $serviceId => $definition) {
            if (0 === strpos($serviceId, 'monolog.handler.')) {
                $class = $container->getParameterBag()->resolveValue($definition->getClass());
                if (is_a($class, BufferHandler::class, true)) {
                    $definition->addTag(self::BUFFER_HANDLERS);
                }
            }
        }
    }
}
