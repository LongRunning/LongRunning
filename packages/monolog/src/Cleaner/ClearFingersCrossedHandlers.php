<?php

namespace LongRunning\Monolog\Cleaner;

use LongRunning\Core\Cleaner;
use Monolog\Handler\FingersCrossedHandler;
use Psr\Log\LoggerInterface;

final class ClearFingersCrossedHandlers implements Cleaner
{
    /**
     * @var FingersCrossedHandler[]
     */
    private iterable $handlers;

    private LoggerInterface $logger;

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
            $this->logger->debug('Clear monolog fingers crossed handler');
            $handler->clear();
        }
    }
}
