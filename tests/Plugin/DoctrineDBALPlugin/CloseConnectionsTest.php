<?php

namespace LongRunning\Tests\Plugin\DoctrineDBALPlugin;

use Doctrine\DBAL\Connection;
use LongRunning\Plugin\DoctrineDBALPlugin\CloseConnections;
use PHPUnit\Framework\TestCase;

class CloseConnectionsTest extends TestCase
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

        $registry = $this->createMock('Doctrine\Common\Persistence\ConnectionRegistry');
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn($connections);

        $logger = $this->createMock('Psr\Log\LoggerInterface');
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
        $registry = $this->createMock('Doctrine\Common\Persistence\ConnectionRegistry');
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn([
                'default' => new \stdClass(),
            ]);

        $logger = $this->createMock('Psr\Log\LoggerInterface');

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
