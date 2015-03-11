<?php

namespace LongRunning\Plugin\DoctrineDBALPlugin;

use Doctrine\Common\Persistence\ConnectionRegistry;
use Doctrine\DBAL\Connection;
use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

class CloseConnections implements Cleaner
{
    /**
     * @var ConnectionRegistry
     */
    private $connectionRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ConnectionRegistry $connectionRegistry, LoggerInterface $logger)
    {
        $this->connectionRegistry = $connectionRegistry;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        foreach ($this->connectionRegistry->getConnections() as $name => $connection) {
            if (!($connection instanceof Connection)) {
                throw new \LogicException('Expected only instances of Connection');
            }

            $this->logger->debug('Close database connection', ['connection' => $name]);
            $connection->close();
        }
    }
}
