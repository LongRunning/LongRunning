<?php

namespace LongRunning\Plugin\MonologPlugin;

use LongRunning\Core\Cleaner;
use Monolog\Handler\BufferHandler;
use Psr\Log\LoggerInterface;

class CloseBufferHandlers implements Cleaner
{
    /**
     * @var BufferHandler[]
     */
    private $handlers;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param BufferHandler[] $handlers
     * @param LoggerInterface $logger
     */
    public function __construct($handlers, LoggerInterface $logger)
    {
        $this->handlers = $handlers;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        foreach ($this->handlers as $handler) {
            $this->logger->debug('Close monolog buffer handler');
            $handler->close();
        }
    }
}
