<?php

namespace LongRunning\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use LongRunning\Bundle\LongRunningBundle\LongRunningBundle;
use LongRunning\Tests\Functional\Compiler\PublicMonologHandlersCompilerPass;
use OldSound\RabbitMqBundle\OldSoundRabbitMqBundle;
use Sentry\SentryBundle\SentryBundle;
use SimpleBus\AsynchronousBundle\SimpleBusAsynchronousBundle;
use SimpleBus\RabbitMQBundleBridge\SimpleBusRabbitMQBundleBridgeBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    private $tempDir;

    public function __construct()
    {
        parent::__construct('test', true);
        $this->tempDir = __DIR__ . '/temp';
    }

    protected function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new PublicMonologHandlersCompilerPass());
    }

    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new MonologBundle(),
            new OldSoundRabbitMqBundle(),
            new SimpleBusAsynchronousBundle(),
            new SimpleBusRabbitMQBundleBridgeBundle(),
            new SwiftmailerBundle(),
            new SentryBundle(),
            new LongRunningBundle(),
            new FrameworkBundle()
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
    }

    public function getCacheDir()
    {
        return $this->tempDir . '/cache';
    }

    public function getLogDir()
    {
        return $this->tempDir . '/logs';
    }
}
