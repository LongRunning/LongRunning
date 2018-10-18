<?php

namespace LongRunning\Tests\Plugin\MonologPlugin;

use LongRunning\Plugin\MonologPlugin\ClearFingersCrossedHandlers;
use Monolog\Handler\FingersCrossedHandler;

class ClearFingersCrossedHandlersTest extends \PHPUnit_Framework_TestCase
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

        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(count($handlers)))
            ->method('debug')
            ->with('Clear monolog fingers crossed handler');

        $cleaner = new ClearFingersCrossedHandlers($handlers,  $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|FingersCrossedHandler
     */
    private function getHandler()
    {
        $handler = $this->getMockBuilder('Monolog\Handler\FingersCrossedHandler')
            ->disableOriginalConstructor()
            ->getMock();

        $handler
            ->expects($this->once())
            ->method('clear');

        return $handler;
    }
}
