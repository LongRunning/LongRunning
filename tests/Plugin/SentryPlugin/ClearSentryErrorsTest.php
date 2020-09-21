<?php

namespace LongRunning\Tests\Plugin\SentryPlugin;

use LongRunning\Plugin\SentryPlugin\ClearSentryErrors;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sentry\ClientInterface;
use Sentry\FlushableClientInterface;

class ClearSentryErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_if_handlers_get_cleared()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with('Flush sentry errors');

        $sentry = $this->getSentry();
        $sentry
            ->expects($this->once())
            ->method('flush');

        $cleaner = new ClearSentryErrors($sentry,  $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClientInterface
     */
    private function getSentry()
    {
        $sentry = $this->getMockBuilder(FlushableClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $sentry;
    }
}
