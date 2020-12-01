<?php

namespace LongRunning\DoctrineORM\Cleaner;

use Doctrine\Persistence\ManagerRegistry;
use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

final class ClearEntityManagers implements Cleaner
{
    private ManagerRegistry $managerRegistry;
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->logger = $logger;
    }

    public function cleanUp() : void
    {
        foreach ($this->managerRegistry->getManagers() as $name => $manager) {
            $this->logger->debug('Clear EntityManager', ['entity_manager' => $name]);

            $manager->clear();
        }
    }
}
