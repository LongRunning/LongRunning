<?php

namespace LongRunning\Bundle\LongRunningBundle\Tests\Functional;

use LongRunning\Core\DelegatingCleaner;
use Monolog\Handler\TestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MonologBufferTest extends KernelTestCase
{
    protected static function getKernelClass()
    {
        return 'LongRunning\Bundle\LongRunningBundle\Tests\Functional\TestKernel';
    }

    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @test
     */
    public function it_keeps_messages_in_buffer_until_cleanp()
    {
        $bufferTestHandler = $this->getTestHandler('buffer_test');
        $logger = $this->getLogger();

        $logger->debug('Buffer this message');
        $this->assertMessages($bufferTestHandler->getRecords(), array());

        $this->getCleaner()->cleanUp();

        $this->assertMessages($bufferTestHandler->getRecords(), array(
            'Buffer this message',
            'Clear EntityManager',
            'Close database connection',
            'Clear monolog fingers crossed handler',
            'Close monolog buffer handler',
        ));
    }

    /**
     * @return DelegatingCleaner
     */
    private function getCleaner()
    {
        return static::$kernel->getContainer()->get('long_running.delegating_cleaner');
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger()
    {
        return static::$kernel->getContainer()->get('logger');
    }

    /**
     * @param string $name
     * @return TestHandler
     */
    private function getTestHandler($name)
    {
        return static::$kernel->getContainer()->get(sprintf('monolog.handler.%s', $name));
    }

    /**
     * @param array $records
     * @param array $expectedMessages
     */
    private function assertMessages(array $records, array $expectedMessages)
    {
        $messages = array();
        foreach ($records as $record) {
            $messages[] = $record['message'];
        }

        $this->assertEquals($messages, $expectedMessages);
    }
}
