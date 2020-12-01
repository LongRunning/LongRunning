<?php

namespace LongRunning\DoctrineORM\Cleaner;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

final class ResetClosedEntityManagers implements Cleaner
{
    private ManagerRegistry $managerRegistry;

    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->logger          = $logger;
    }

    public function cleanUp() : void
    {
        foreach ($this->managerRegistry->getManagers() as $name => $manager) {
            if (!$manager instanceof EntityManager && !$manager instanceof EntityManagerInterface) {
                continue;
            }

            if ($manager->isOpen()) {
                continue;
            }

            $this->logger->debug('Reset closed EntityManager', ['entity_manager' => $name]);

            $this->managerRegistry->resetManager($name);
        }
    }
}
