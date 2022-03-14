<?php

namespace LongRunning\Monolog\Cleaner;

use Monolog\Handler\FingersCrossedHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class ClearFingersCrossedHandlersTest extends TestCase
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
            ->with('Clear monolog fingers crossed handler');

        $cleaner = new ClearFingersCrossedHandlers($handlers, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return FingersCrossedHandler&MockObject
     */
    private function getHandler()
    {
        $handler = $this->getMockBuilder(FingersCrossedHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $handler
            ->expects($this->once())
            ->method('clear');

        return $handler;
    }
}
