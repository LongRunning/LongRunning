<?php

namespace LongRunning\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use LongRunning\Bundle\LongRunningBundle\LongRunningBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    private $tempDir;

    public function __construct()
    {
        parent::__construct('test', true);
        $this->tempDir = __DIR__ . '/temp';
    }

    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new MonologBundle(),
            new SwiftmailerBundle(),
            new LongRunningBundle(),
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