<?php

namespace LongRunning\Plugin\DoctrineDBALPlugin;

use Doctrine\Common\Persistence\ConnectionRegistry;
use Doctrine\DBAL\Connection;
use LongRunning\Core\Cleaner;

class CloseConnections implements Cleaner
{
    /**
     * @var ConnectionRegistry
     */
    private $connectionRegistry;

    public function __construct(ConnectionRegistry $connectionRegistry)
    {
        $this->connectionRegistry = $connectionRegistry;
    }

    public function cleanUp()
    {
        foreach ($this->connectionRegistry->getConnections() as $connection) {
            if (!($connection instanceof Connection)) {
                throw new \LogicException('Expected only instances of Connection');
            }

            $connection->close();
        }
    }
}
