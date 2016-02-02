<?php

namespace LongRunning\Plugin\MonologPlugin\Tests;

use LongRunning\Plugin\MonologPlugin\CloseBufferHandlers;
use Monolog\Handler\BufferHandler;

class CloseBufferHandlersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_test_if_handlers_get_cleared()
    {
        $handlers = [
            $this->getHandler(),
            $this->getHandler(),
        ];

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(count($handlers)))
            ->method('debug')
            ->with('Close monolog buffer handler');

        $cleaner = new CloseBufferHandlers($handlers,  $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|BufferHandler
     */
    private function getHandler()
    {
        $handler = $this->getMockBuilder('Monolog\Handler\BufferHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $handler
            ->expects($this->once())
            ->method('close');

        return $handler;
    }
}
