<?php

namespace LongRunning\DoctrineORM\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use LongRunning\Core\Bundle\LongRunningBundle;
use LongRunning\DoctrineORM\Bundle\LongRunningDoctrineORMBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

final class TestKernel extends Kernel
{
    /**
     * @return BundleInterface[]
     */
    public function registerBundles(): array
    {
        return [
            new DoctrineBundle(),
            new LongRunningBundle(),
            new LongRunningDoctrineORMBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.php');
    }
}
