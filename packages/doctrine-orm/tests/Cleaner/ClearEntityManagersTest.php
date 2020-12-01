<?php

namespace LongRunning\DoctrineORM\Cleaner;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

final class ClearEntityManagersTest extends TestCase
{
    /**
     * @test
     */
    public function it_clears_all_object_managers() : void
    {
        $managers = [
            'default' => $this->getManager(),
            'second'  => $this->getManager(),
        ];

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $logger  = new TestLogger();
        $cleaner = new ClearEntityManagers($registry, $logger);
        $cleaner->cleanUp();

        $this->assertTrue($logger->hasDebug([
            'message' => 'Clear EntityManager',
            'context' => ['entity_manager' => 'default'],
        ]));
        $this->assertTrue($logger->hasDebug([
            'message' => 'Clear EntityManager',
            'context' => ['entity_manager' => 'second'],
        ]));
    }

    /**
     * @return MockObject|ObjectManager
     */
    private function getManager() : MockObject
    {
        $manager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('clear');

        return $manager;
    }
}
