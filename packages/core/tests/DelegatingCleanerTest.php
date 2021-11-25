<?php

namespace LongRunning\Core;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class DelegatingCleanerTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_the_delecated_cleaners(): void
    {
        $cleaner = new DelegatingCleaner([
            $this->getCleaner(),
            $this->getCleaner(),
            $this->getCleaner(),
        ]);
        $cleaner->cleanUp();
    }

    /**
     * @return Cleaner|MockObject
     */
    private function getCleaner(): MockObject
    {
        $cleaner = $this->createMock(Cleaner::class);
        $cleaner
            ->expects($this->once())
            ->method('cleanUp');

        return $cleaner;
    }
}
