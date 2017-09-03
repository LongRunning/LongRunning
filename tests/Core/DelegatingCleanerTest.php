<?php

namespace LongRunning\Tests\Core;

use LongRunning\Core\Cleaner;
use LongRunning\Core\DelegatingCleaner;

class DelegatingCleanerTest extends \PHPUnit\Framework\TestCase
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
        $cleaner = $this->createMock('LongRunning\Core\Cleaner');
        $cleaner
            ->expects($this->once())
            ->method('cleanUp');

        return $cleaner;
    }
}
