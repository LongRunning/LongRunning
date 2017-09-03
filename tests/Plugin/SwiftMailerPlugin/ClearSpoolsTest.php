<?php

namespace LongRunning\Tests\Plugin\SwiftMailerPlugin;

use LongRunning\Plugin\SwiftMailerPlugin\ClearSpools;

class ClearSpoolsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_tests_flushing_memory_spools()
    {
        $transports = [
            'default'   => $this->getTransport(),
            'second'    => $this->getTransport(),
        ];

        $spools = [];
        foreach ($transports as $name => $transport) {
            $spools[$name] = $this->getSpool($transport);
        }

        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(count($transports)))
            ->method('debug')
            ->with('Flush swiftmailer memory spool');

        $cleaner = new ClearSpools($spools, $transports, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     */
    public function it_tests_failed_flushing()
    {
        $transports = [
            'default'   => $this->getTransport(),
        ];

        $spools = [];
        $spools['default'] = $spool = $this->createMock('Swift_MemorySpool');
        $spool
            ->expects($this->once())
            ->method('flushQueue')
            ->willThrowException(new \Swift_TransportException('Fake error'));

        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(count($transports)))
            ->method('debug')
            ->with('Flush swiftmailer memory spool');
        $logger
            ->expects($this->exactly(count($transports)))
            ->method('error')
            ->with('Exception occurred while flushing email queue: Fake error');

        $cleaner = new ClearSpools($spools, $transports, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Swift_Transport
     */
    private function getTransport()
    {
        return $this->createMock('Swift_Transport');
    }

    /**
     * @param \Swift_Transport $transport
     * @return \PHPUnit_Framework_MockObject_MockObject|\Swift_MemorySpool
     */
    private function getSpool(\Swift_Transport $transport)
    {
        $spool = $this->createMock('Swift_MemorySpool');
        $spool
            ->expects($this->once())
            ->method('flushQueue')
            ->with($transport);

        return $spool;
    }
}
