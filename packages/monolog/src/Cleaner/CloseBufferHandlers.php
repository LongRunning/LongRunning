<?php

namespace LongRunning\Monolog\Cleaner;

use LongRunning\Core\Cleaner;
use Monolog\Handler\BufferHandler;
use Psr\Log\LoggerInterface;

final class CloseBufferHandlers implements Cleaner
{
    /**
     * @var BufferHandler[]
     */
    private iterable $handlers;

    private LoggerInterface $logger;

    /**
     * @param BufferHandler[] $handlers
     */
    public function __construct(
        iterable $handlers,
        LoggerInterface $logger
    ) {
        $this->handlers = $handlers;
        $this->logger = $logger;
    }

    public function cleanUp(): void
    {
        foreach ($this->handlers as $handler) {
            $this->logger->debug('Close monolog buffer handler');
            $handler->close();
        }
    }
}
