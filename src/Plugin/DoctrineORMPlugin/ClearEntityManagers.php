<?php

namespace LongRunning\Plugin\DoctrineORMPlugin;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

class ClearEntityManagers implements Cleaner
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->logger = $logger;
    }

    public function cleanUp()
    {
        foreach ($this->managerRegistry->getManagers() as $name => $manager) {
            $this->logger->debug('Clear EntityManager', ['entity_manager' => $name]);
            $manager->clear();
        }
    }
}
