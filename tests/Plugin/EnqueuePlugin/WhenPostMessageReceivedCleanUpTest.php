<?php

namespace LongRunning\Tests\Plugin\EnqueuePlugin;

use Enqueue\Consumption\Context\PostMessageReceived;
use Interop\Queue\Consumer;
use Interop\Queue\Context;
use Interop\Queue\Message;
use LongRunning\Core\Cleaner;
use LongRunning\Plugin\EnqueuePlugin\WhenPostMessageReceivedCleanUp;
use Psr\Log\LoggerInterface;

class WhenPostMessageReceivedCleanUpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCallCleanUpOnPostMessageReceive()
    {
        $delegatedCleaner = $this->createMock(Cleaner::class);
        $delegatedCleaner
            ->expects($this->once())
            ->method('cleanUp');

        $postMessageCleanUp = new WhenPostMessageReceivedCleanUp($delegatedCleaner);

        $postMessageCleanUp->onPostMessageReceived($this->getMessageReceived());
    }

    protected function getMessageReceived()
    {
        $messageReceived = new PostMessageReceived(
            $this->createMock(Context::class),
            $this->createMock(Consumer::class),
            $this->createMock(Message::class),
            null,
            1,
            $this->createMock(LoggerInterface::class)
        );

        return $messageReceived;
    }
}
