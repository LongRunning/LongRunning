<?php

namespace LongRunning\Tests\Plugin\DoctrineDBALPlugin;

use Doctrine\Persistence\ConnectionRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use LongRunning\Plugin\DoctrineDBALPlugin\CloseConnections;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

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

        $registry = $this->createMock(ConnectionRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn($connections);

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->withConsecutive(
                ['Close database connection', ['connection' => 'default']],
                ['Close database connection', ['connection' => 'second']]
            );

        $cleaner = new CloseConnections($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     */
    public function it_rolls_back_when_in_rollback_only_mode()
    {
        $connections = [
            'default'   => $this->getConnection(),
            'second'    => $this->getConnection(),
        ];

        $connections['default']->expects($this->atLeastOnce())
            ->method('isTransactionActive')
            ->willReturn(true);

        $connections['default']->expects($this->atLeastOnce())
            ->method('isRollbackOnly')
            ->willReturn(true);

        $connections['default']->expects($this->atLeastOnce())
            ->method('rollBack');

        $registry = $this->createMock(ConnectionRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn($connections);

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->once())
            ->method('notice')
            ->with('Rolling back active transaction in rollback only state', ['connection' => 'default']);

        $logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->withConsecutive(
                ['Close database connection', ['connection' => 'default']],
                ['Close database connection', ['connection' => 'second']]
            );

        $cleaner = new CloseConnections($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @test
     */
    public function it_rolls_back_when_in_rollback_only_mode_and_catches_any_exception()
    {
        $connections = [
            'default'   => $this->getConnection(),
            'second'    => $this->getConnection(),
        ];

        $connections['default']->expects($this->atLeastOnce())
            ->method('isTransactionActive')
            ->willReturn(true);

        $connections['default']->expects($this->atLeastOnce())
            ->method('isRollbackOnly')
            ->willReturn(true);

        $connections['default']->expects($this->atLeastOnce())
            ->method('rollBack')
            ->willThrowException(ConnectionException::noActiveTransaction());

        $connections['second']->expects($this->atLeastOnce())
            ->method('isTransactionActive')
            ->willReturn(false);

        $registry = $this->createMock(ConnectionRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn($connections);

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->once())
            ->method('notice')
            ->with('Rolling back active transaction in rollback only state', ['connection' => 'default']);

        $logger
            ->expects($this->once())
            ->method('error')
            ->with('Rolling back active transaction failed', [
                'connection' => 'default',
                'exception' => 'There is no active transaction.'
            ]);

        $logger
            ->expects($this->exactly(2))
            ->method('debug')
            ->withConsecutive(
                ['Close database connection', ['connection' => 'default']],
                ['Close database connection', ['connection' => 'second']]
            );

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
        $registry = $this->createMock(ConnectionRegistry::class);
        $registry
            ->expects($this->once())
            ->method('getConnections')
            ->willReturn([
                'default' => new \stdClass(),
            ]);

        $logger = $this->createMock(LoggerInterface::class);

        $cleaner = new CloseConnections($registry, $logger);
        $cleaner->cleanUp();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Connection
     */
    private function getConnection()
    {
        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $connection
            ->expects($this->once())
            ->method('close');

        return $connection;
    }
}
