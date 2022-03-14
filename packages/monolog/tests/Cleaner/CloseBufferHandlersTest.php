<?php

namespace LongRunning\Monolog\Cleaner;

use Monolog\Handler\BufferHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class CloseBufferHandlersTest extends TestCase
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

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(count($handlers)))
            ->method('debug')
            ->with('Close monolog buffer handler');

        $cleaner = new CloseBufferHandlers($handlers, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return BufferHandler&MockObject
     */
    private function getHandler()
    {
        $handler = $this->getMockBuilder(BufferHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $handler
            ->expects($this->once())
            ->method('close');

        return $handler;
    }
}
