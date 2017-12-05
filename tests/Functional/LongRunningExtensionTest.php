<?php

namespace LongRunning\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LongRunningExtensionTest extends KernelTestCase
{
    protected static function getKernelClass()
    {
        return 'LongRunning\Tests\Functional\TestKernel';
    }

    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @test
     */
    public function it_automatically_enables_plugins()
    {
        $cleaner = static::$kernel->getContainer()->get('long_running.delegating_cleaner');

        $reflectionObject = new \ReflectionObject($cleaner);
        $property = $reflectionObject->getProperty('cleaners');
        $property->setAccessible(true);

        $cleaners = $property->getValue($cleaner);

        $expectedCleaners = [
            'LongRunning\Plugin\DoctrineORMPlugin\ClearEntityManagers',
            'LongRunning\Plugin\DoctrineORMPlugin\ResetClosedEntityManagers',
            'LongRunning\Plugin\DoctrineDBALPlugin\CloseConnections',
            'LongRunning\Plugin\MonologPlugin\ClearFingersCrossedHandlers',
            'LongRunning\Plugin\MonologPlugin\CloseBufferHandlers',
            'LongRunning\Plugin\SwiftMailerPlugin\ClearSpools',
            'LongRunning\Plugin\SentryPlugin\ClearSentryErrors',
        ];

        $this->assertEquals($expectedCleaners, array_map('get_class', $cleaners));
    }
}
