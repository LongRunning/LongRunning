<?php

namespace LongRunning\Tests\Plugin\DoctrineDBALPlugin;

use Doctrine\DBAL\Connection;
use LongRunning\Plugin\DoctrineDBALPlugin\CloseConnections;

class CloseConnectionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_closes_all_connections()
    {
        $connections = [
            'default'   => $this->getConnection(),
            'second'    => $this->getConnection(),
        ];

        $registry = $this->getMock('Doctrine\Common\Persistence\ConnectionRegistry');
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn($connections);

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        foreach (array_keys($connections) as $count => $name) {
            $logger
                ->expects($this->at($count))
                ->method('debug')
                ->with('Close database connection', ['connection' => $name]);
        }

        $cleaner = new CloseConnections($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     *
     * @expectedException        \LogicException
     * @expectedExceptionMessage Expected only instances of Connection
     */
    public function it_throws_exception_with_wrong_connection()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ConnectionRegistry');
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn([
                'default' => new \stdClass(),
            ]);

        $logger = $this->getMock('Psr\Log\LoggerInterface');

        $cleaner = new CloseConnections($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Connection
     */
    private function getConnection()
    {
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $connection
            ->expects($this->once())
            ->method('close');

        return $connection;
    }
}
