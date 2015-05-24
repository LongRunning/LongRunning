<?php

namespace LongRunning\Tests\Plugin\DoctrineORMPlugin;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use LongRunning\Plugin\DoctrineORMPlugin\ResetClosedEntityManagers;

class ResetClosedEntityManagersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_resets_entity_managers()
    {
        $managers = [
            'default'   => $this->getEntityManager(),
            'second'    => $this->getEntityManager(),
        ];

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        foreach (array_keys($managers) as $count => $name) {
            $registry
                ->expects($this->at($count + 1))
                ->method('resetManager')
                ->with($name);
        }

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        foreach (array_keys($managers) as $count => $name) {
            $logger
                ->expects($this->at($count))
                ->method('debug')
                ->with('Reset closed EntityManager', ['entity_manager' => $name]);
        }

        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @tests
     */
    public function it_ignores_other_object_mappers()
    {
        $managers = [
            'default'   => $this->getObjectManager(),
        ];

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->never())
            ->method('debug');

        $cleaner = new ResetClosedEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EntityManager
     */
    private function getEntityManager()
    {
        $manager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('isOpen')
            ->willReturn(false);

        return $manager;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    private function getObjectManager()
    {
        $manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->never())
            ->method('isOpen');

        return $manager;
    }
}
