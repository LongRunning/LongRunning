<?php

namespace LongRunning\Plugin\SentryPlugin;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;
use Sentry\FlushableClientInterface;

class ClearSentryErrors implements Cleaner
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FlushableClientInterface
     */
    private $sentry;

    public function __construct(FlushableClientInterface $sentry,  LoggerInterface $logger)
    {
        $this->sentry = $sentry;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        $this->logger->debug('Flush sentry errors');
        $this->sentry->flush();
    }
}
