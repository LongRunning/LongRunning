<?php

namespace LongRunning\Plugin\SentryPlugin;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;
use Sentry\SentryBundle\SentrySymfonyClient;

class ClearSentryErrors implements Cleaner
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SentrySymfonyClient
     */
    private $sentry;

    public function __construct(SentrySymfonyClient $sentry,  LoggerInterface $logger)
    {
        $this->sentry = $sentry;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        $this->logger->debug('Flush sentry errors');
        $this->sentry->sendUnsentErrors();
    }
}
