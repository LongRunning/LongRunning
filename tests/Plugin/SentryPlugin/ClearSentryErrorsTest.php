<?php

namespace LongRunning\Tests\Plugin\SentryPlugin;

use LongRunning\Plugin\SentryPlugin\ClearSentryErrors;
use PHPUnit\Framework\TestCase;
use Sentry\SentryBundle\SentrySymfonyClient;

class ClearSentryErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function it_test_if_handlers_get_cleared()
    {
        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->once())
            ->method('debug')
            ->with('Flush sentry errors');

        $sentry = $this->getSentry();
        $sentry
            ->expects($this->once())
            ->method('sendUnsentErrors');

        $sentry
            ->breadcrumbs
            ->expects($this->once())
            ->method('reset');

        $cleaner = new ClearSentryErrors($sentry,  $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SentrySymfonyClient
     */
    private function getSentry()
    {
        $sentry = $this->getMockBuilder('Sentry\SentryBundle\SentrySymfonyClient')
            ->disableOriginalConstructor()
            ->getMock();

        $sentry->breadcrumbs = $this->getBreadcrumbs();

        return $sentry;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Raven_Breadcrumbs
     */
    private function getBreadcrumbs()
    {
        return $this->getMockBuilder('Raven_Breadcrumbs')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
