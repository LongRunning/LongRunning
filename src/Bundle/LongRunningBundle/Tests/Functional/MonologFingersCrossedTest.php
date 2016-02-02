<?php

namespace LongRunning\Bundle\LongRunningBundle\Tests\Functional;

use LongRunning\Core\DelegatingCleaner;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\TestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MonologFingersCrossedTest extends KernelTestCase
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
    public function it_clears_messages_that_never_hit_the_action_level()
    {
        $bufferTestHandler = $this->getTestHandler('fingers_crossed_test');
        $logger = $this->getLogger();

        $logger->debug('This message will never show up');
        $this->assertMessages($bufferTestHandler->getRecords(), array());

        $this->getCleaner()->cleanUp();

        $this->assertMessages($bufferTestHandler->getRecords(), array());

        $fingersCrossedHandler = $this->getFingersCrossedHandler('fingers_crossed');
        $reflectionObject = new \ReflectionObject($fingersCrossedHandler);
        $property = $reflectionObject->getProperty('buffer');
        $property->setAccessible(true);

        $messages = $property->getValue($fingersCrossedHandler);
        $this->assertMessages($messages, array(
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
     * @param string $name
     * @return FingersCrossedHandler
     */
    private function getFingersCrossedHandler($name)
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
