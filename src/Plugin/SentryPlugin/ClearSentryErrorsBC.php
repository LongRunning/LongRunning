<?php

namespace LongRunning\Plugin\SentryPlugin;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;
use Sentry\SentryBundle\SentrySymfonyClient;

/**
 * BC layer for Sentry V1 + V2
 */
class ClearSentryErrorsBC implements Cleaner
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
        $this->sentry->breadcrumbs->reset();
    }
}
