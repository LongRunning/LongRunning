<?php

namespace LongRunning\DoctrineORM\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use LongRunning\Core\Bundle\LongRunningBundle;
use LongRunning\DoctrineORM\Bundle\LongRunningDoctrineORMBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles() : array
    {
        return [
            new DoctrineBundle(),
            new LongRunningBundle(),
            new LongRunningDoctrineORMBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yaml');
    }
}
