<?php

namespace LongRunning\Plugin\EnqueuePlugin;

use Enqueue\Consumption\Context\PostMessageReceived;
use Enqueue\Consumption\PostMessageReceivedExtensionInterface;
use LongRunning\Core\Cleaner;

class WhenPostMessageReceivedCleanUp implements PostMessageReceivedExtensionInterface
{
    /**
     * @var Cleaner
     */
    private $cleaner;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function onPostMessageReceived(PostMessageReceived $context) : void
    {
        $this->cleaner->cleanUp();
    }
}
