<?php

namespace LongRunning\Sentry\Functional;

use LongRunning\Core\Bundle\LongRunningBundle;
use LongRunning\Sentry\Bundle\LongRunningSentryBundle;
use Sentry\SentryBundle\SentryBundle;
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
            new SentryBundle(),
            new LongRunningBundle(),
            new LongRunningSentryBundle(),
            new FrameworkBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.php');
    }
}
