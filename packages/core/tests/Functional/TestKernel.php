<?php

namespace LongRunning\Core\Functional;

use LongRunning\Core\Bundle\LongRunningBundle;
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
            new LongRunningBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.php');
    }
}
