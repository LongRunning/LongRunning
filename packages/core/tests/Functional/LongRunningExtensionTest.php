<?php

namespace LongRunning\Core\Functional;

use LongRunning\Core\DelegatingCleaner;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webmozart\Assert\Assert;

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

        $cleaner = self::$container->get('public_cleaner');

        assert($cleaner instanceof DelegatingCleaner);

        $this->cleaner = $cleaner;
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
            CleanerOne::class,
            CleanerTwo::class
        ];

        $this->assertEquals($expectedCleaners, array_map('get_class', $cleaners));

        $this->cleaner->cleanUp();
    }
}
