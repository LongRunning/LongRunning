<?php

namespace LongRunning\DoctrineORM\Cleaner;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

final class ResetClosedEntityManagersTest extends TestCase
{
    /**
     * @test
     */
    public function it_resets_entity_managers(): void
    {
        $managers = [
            'default' => $this->getEntityManager(),
            'second' => $this->getEntityManager(),
        ];

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $registry
            ->expects($this->exactly(count($managers)))
            ->method('resetManager')
            ->withConsecutive(...array_map(function ($manager) { return [$manager]; }, array_keys($managers)));

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->with('Reset closed EntityManager');

        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     */
    public function it_resets_entity_manager_interfase(): void
    {
        $managers = [
            'default' => $this->getEntityManagerInterface(),
            'second' => $this->getEntityManagerInterface(),
        ];

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $registry
            ->expects($this->exactly(count($managers)))
            ->method('resetManager')
            ->withConsecutive(...array_map(function ($manager) { return [$manager]; }, array_keys($managers)));

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->with('Reset closed EntityManager');

        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     */
    public function it_ignores_other_object_mappers(): void
    {
        $managers = [
            'default' => $this->getObjectManager(),
        ];

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $logger = $this->createMock(LoggerInterface::class);
        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }

    // @return EntityManager|EntityManagerInterface|MockObject
    private function getEntityManager(): MockObject
    {
        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('isOpen')
            ->willReturn(false);

        return $manager;
    }

    // @return EntityManagerInterface|MockObject
    private function getEntityManagerInterface(): MockObject
    {
        $manager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('isOpen')
            ->willReturn(false);

        return $manager;
    }

    /**
     * @return MockObject|ObjectManager
     */
    private function getObjectManager(): MockObject
    {
        return $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
