<?php

namespace LongRunning\Plugin\DoctrineORMPlugin;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use LongRunning\Core\Cleaner;

class ResetClosedEntityManagers implements Cleaner
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function cleanUp()
    {
        foreach ($this->managerRegistry->getManagers() as $name => $manager) {
            if (!($manager instanceof EntityManager)) {
                throw new \LogicException('Expected only instances of EntityManager');
            }

            if (!$manager->isOpen()) {
                $this->managerRegistry->resetManager($name);
            }
        }
    }
}
