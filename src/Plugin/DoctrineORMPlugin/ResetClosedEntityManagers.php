<?php

namespace LongRunning\Plugin\DoctrineORMPlugin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use LongRunning\Core\Cleaner;
use Psr\Log\LoggerInterface;

class ResetClosedEntityManagers implements Cleaner
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
            if (!($manager instanceof EntityManager)) {
                continue;
            }

            if (!$manager->isOpen()) {
                $this->logger->debug('Reset closed EntityManager', ['entity_manager' => $name]);
                $this->managerRegistry->resetManager($name);
            }
        }
    }
}
