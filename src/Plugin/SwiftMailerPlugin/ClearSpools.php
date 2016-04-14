<?php

namespace LongRunning\Plugin\SwiftMailerPlugin;

use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

class ClearSpools implements Cleaner
{
    /**
     * @var \Swift_MemorySpool[]
     */
    private $spools;

    /**
     * @var \Swift_Transport[]
     */
    private $transports;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(array $spools, array $transports, LoggerInterface $logger)
    {
        $this->spools = $spools;
        $this->transports = $transports;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        foreach ($this->transports as $transport) {
            $transport->start();
        }
        foreach ($this->spools as $name => $spool) {
            try {
                $this->logger->debug('Flush swiftmailer memory spool');
                $spool->flushQueue($this->transports[$name]);
            } catch (\Swift_TransportException $exception) {
                $this->logger->error(sprintf('Exception occurred while flushing email queue: %s', $exception->getMessage()));
            }
        }
        foreach ($this->transports as $transport) {
            $transport->stop();
        }
    }
}
