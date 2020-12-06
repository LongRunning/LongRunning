<?php

namespace LongRunning\Sentry\Functional;

use LongRunning\Core\DelegatingCleaner;
use LongRunning\Sentry\Cleaner\FlushSentryErrors;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class LongRunningExtensionTest extends KernelTestCase
{
    private DelegatingCleaner $cleaner;

    protected static function getKernelClass() : string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->cleaner = self::$container->get('public_cleaner');
    }

    /**
     * @test
     */
    public function it_automatically_enables_plugins() : void
    {
        $reflectionObject = new \ReflectionObject($this->cleaner);
        $property = $reflectionObject->getProperty('cleaners');
        $property->setAccessible(true);

        $cleaners = iterator_to_array($property->getValue($this->cleaner));

        $expectedCleaners = [
            FlushSentryErrors::class
        ];

        $this->assertEquals($expectedCleaners, array_map('get_class', $cleaners));

        $this->cleaner->cleanUp();
    }
}
