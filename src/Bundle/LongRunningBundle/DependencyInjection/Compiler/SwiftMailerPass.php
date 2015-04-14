<?php

namespace LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SwiftMailerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $clearSpoolsId = 'long_running.swift_mailer.clear_spools';
        if (!($container->has($clearSpoolsId))) {
            return;
        }

        if ($container->findDefinition('swiftmailer.mailer') === null || $container->getParameter('swiftmailer.mailers') === []) {
            return;
        }

        $spoolServiceReferences = [];
        $realTransportServiceReferences = [];

        $mailers = $container->getParameter('swiftmailer.mailers');
        foreach ($mailers as $name => $mailer) {
            if ($container->getParameter(sprintf('swiftmailer.mailer.%s.spool.enabled', $name))) {
                $transport = sprintf('swiftmailer.mailer.%s.transport', $name);
                $transportDefinition = $container->findDefinition($transport);

                if (is_a('Swift_Transport_SpoolTransport', $transportDefinition->getClass(), true)) {
                    $spool = sprintf('swiftmailer.mailer.%s.spool', $name);
                    $spoolDefinition = $container->findDefinition($spool);

                    if (is_a('Swift_MemorySpool', $spoolDefinition->getClass(), true)) {
                        $realTransport = sprintf('swiftmailer.mailer.%s.transport.real', $name);
                        $spoolServiceReferences[$name] = new Reference($spool);
                        $realTransportServiceReferences[$name] = new Reference($realTransport);
                    }
                }
            }
        }

        $definition = $container->getDefinition($clearSpoolsId);
        $definition->replaceArgument(0, $spoolServiceReferences);
        $definition->replaceArgument(1, $realTransportServiceReferences);
    }
}
