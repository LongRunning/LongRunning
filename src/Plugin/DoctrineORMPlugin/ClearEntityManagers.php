<?php

namespace LongRunning\Plugin\DoctrineORMPlugin;

use Doctrine\Common\Persistence\ManagerRegistry;
use LongRunning\Core\Cleaner;

class ClearEntityManagers implements Cleaner
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
        foreach ($this->managerRegistry->getManagers() as $manager) {
            $manager->clear();
        }
    }
}
