<?php

namespace LongRunning\Tests\Plugin\DoctrineORMPlugin;

use Doctrine\Common\Persistence\ObjectManager;
use LongRunning\Plugin\DoctrineORMPlugin\ClearEntityManagers;

class ClearEntityManagersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_clears_all_object_managers()
    {
        $managers = [
            'default'   => $this->getManager(),
            'second'    => $this->getManager(),
        ];

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry
            ->expects($this->once())
            ->method('getManagers')
            ->willReturn($managers);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        foreach (array_keys($managers) as $count => $name) {
            $logger
                ->expects($this->at($count))
                ->method('debug')
                ->with('Clear EntityManager', ['entity_manager' => $name]);
        }

        $cleaner = new ClearEntityManagers($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ObjectManager
     */
    private function getManager()
    {
        $manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('clear');

        return $manager;
    }
}
