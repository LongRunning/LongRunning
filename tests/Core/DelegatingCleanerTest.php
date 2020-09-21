<?php

namespace LongRunning\Tests\Core;

use LongRunning\Core\Cleaner;
use LongRunning\Core\DelegatingCleaner;
use PHPUnit\Framework\TestCase;

class DelegatingCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_the_delecated_cleaners()
    {
        $cleaner = new DelegatingCleaner([
            $this->getCleaner(),
            $this->getCleaner(),
            $this->getCleaner(),
        ]);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Cleaner
     */
    private function getCleaner()
    {
        $cleaner = $this->createMock(Cleaner::class);
        $cleaner
            ->expects($this->once())
            ->method('cleanUp');

        return $cleaner;
    }
}
