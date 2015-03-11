<?php

namespace LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterCleanersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $delegatingCleanerId = 'long_running.delegating_cleaner';
        if (!($container->has($delegatingCleanerId))) {
            return;
        }

        $cleanerReferences = [];
        foreach ($container->findTaggedServiceIds('long_running.cleaner') as $serviceId => $tags) {
            $cleanerReferences[] = new Reference($serviceId);
        }

        $delegatingCleaner = $container->findDefinition($delegatingCleanerId);
        $delegatingCleaner->replaceArgument(0, $cleanerReferences);
    }
}
