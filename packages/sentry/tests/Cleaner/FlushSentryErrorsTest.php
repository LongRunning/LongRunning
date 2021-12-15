<?php

namespace LongRunning\Sentry\Cleaner;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sentry\ClientInterface;

final class FlushSentryErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_if_handlers_get_cleared(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $sentry = $this
            ->getMockBuilder(ClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $sentry
            ->expects($this->once())
            ->method('flush');

        $logger
            ->expects($this->once())
            ->method('debug')
            ->with('Flush sentry errors');

        $cleaner = new FlushSentryErrors($sentry, $logger);
        $cleaner->cleanUp();
    }
}
