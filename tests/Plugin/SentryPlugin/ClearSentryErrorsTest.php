<?php

namespace LongRunning\Tests\Plugin\SentryPlugin;

use LongRunning\Plugin\SentryPlugin\ClearSentryErrors;
use Sentry\SentryBundle\SentrySymfonyClient;

class ClearSentryErrorsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_test_if_handlers_get_cleared()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with('Flush sentry errors');

        $sentry = $this->getSentry();
        $sentry
            ->expects($this->once())
            ->method('sendUnsentErrors');

        $cleaner = new ClearSentryErrors($sentry,  $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SentrySymfonyClient
     */
    private function getSentry()
    {
        return $this->getMockBuilder('Sentry\SentryBundle\SentrySymfonyClient')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
