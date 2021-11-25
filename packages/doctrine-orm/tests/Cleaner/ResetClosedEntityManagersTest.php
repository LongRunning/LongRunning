<?php

namespace LongRunning\DoctrineORM\Cleaner;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

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

        $logger = new TestLogger();
        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();

        $this->assertTrue($logger->hasDebug([
            'message' => 'Reset closed EntityManager',
            'context' => ['entity_manager' => 'default'],
        ]));
        $this->assertTrue($logger->hasDebug([
            'message' => 'Reset closed EntityManager',
            'context' => ['entity_manager' => 'second'],
        ]));
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

        $logger = new TestLogger();
        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();

        $this->assertTrue($logger->hasDebug([
            'message' => 'Reset closed EntityManager',
            'context' => ['entity_manager' => 'default'],
        ]));
        $this->assertTrue($logger->hasDebug([
            'message' => 'Reset closed EntityManager',
            'context' => ['entity_manager' => 'second'],
        ]));
    }

    /**
     * @tests
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

        $logger = new TestLogger();
        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();

        $this->assertFalse($logger->hasDebugRecords());
    }

    // @return EntityManager|EntityManagerInterface|MockObject
    private function getEntityManager(): MockObject
    {
        $manager = $this->getMockBuilder(EntityManager::class)
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
        $manager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->never())
            ->method('isOpen');

        return $manager;
    }
}
