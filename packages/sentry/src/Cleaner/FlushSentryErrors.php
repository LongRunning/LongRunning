<?php

namespace LongRunning\Sentry\Cleaner;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;
use Sentry\ClientInterface;

final class FlushSentryErrors implements Cleaner
{
    private ClientInterface $sentry;
    private LoggerInterface $logger;

    public function __construct(ClientInterface $sentry, LoggerInterface $logger)
    {
        $this->sentry = $sentry;
        $this->logger = $logger;
    }

    public function cleanUp(): void
    {
        $this->logger->debug('Flush sentry errors');
        $this->sentry->flush();
    }
}
