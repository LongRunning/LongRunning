<?php

namespace LongRunning\Plugin\MonologPlugin;

use LongRunning\Core\Cleaner;
use Monolog\Handler\FingersCrossedHandler;
use Psr\Log\LoggerInterface;

class ClearFingersCrossedHandlers implements Cleaner
{
    /**
     * @var FingersCrossedHandler[]
     */
    private $handlers;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param FingersCrossedHandler[] $handlers
     * @param LoggerInterface         $logger
     */
    public function __construct(array $handlers, LoggerInterface $logger)
    {
        $this->handlers = $handlers;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        foreach ($this->handlers as $handler) {
            $this->logger->debug('Clear monolog fingers crossed handler');
            $handler->clear();
        }
    }
}
