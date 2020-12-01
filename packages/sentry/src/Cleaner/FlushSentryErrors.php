<?php

namespace LongRunning\Sentry\Cleaner;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;
use Sentry\FlushableClientInterface;

final class FlushSentryErrors implements Cleaner
{
    private LoggerInterface $logger;
    private FlushableClientInterface $sentry;

    public function __construct(FlushableClientInterface $sentry, LoggerInterface $logger)
    {
        $this->sentry = $sentry;
        $this->logger = $logger;
    }

    public function cleanUp() : void
    {
        $this->logger->debug('Flush sentry errors');
        $this->sentry->flush();
    }
}
