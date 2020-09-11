<?php

namespace LongRunning\Plugin\DoctrineDBALPlugin;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
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

    public function __construct($connectionRegistry, LoggerInterface $logger)
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

            try {
                if ($connection->isTransactionActive() && $connection->isRollbackOnly()) {
                    $this->logger->notice('Rolling back active transaction in rollback only state', ['connection' => $name]);

                    $connection->rollBack();
                }
            } catch(ConnectionException $exception) {
                $this->logger->error('Rolling back active transaction failed', [
                    'connection' => $name,
                    'exception' => $exception->getMessage()
                ]);
            }

            $this->logger->debug('Close database connection', ['connection' => $name]);
            $connection->close();
        }
    }
}
