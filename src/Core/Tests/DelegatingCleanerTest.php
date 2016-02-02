<?php

namespace LongRunning\Core\Tests;

use LongRunning\Core\Cleaner;
use LongRunning\Core\DelegatingCleaner;

class DelegatingCleanerTest extends \PHPUnit_Framework_TestCase
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
        $cleaner = $this->getMock('LongRunning\Core\Cleaner');
        $cleaner
            ->expects($this->once())
            ->method('cleanUp');

        return $cleaner;
    }
}
