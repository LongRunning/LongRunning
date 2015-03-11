<?php

namespace LongRunning\Plugin\SimpleBusRabbitMQPlugin;

use LongRunning\Core\Cleaner;
use SimpleBus\RabbitMQBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WhenMessageConsumedOrFailedCleanUp implements EventSubscriberInterface
{
    /**
     * @var Cleaner
     */
    private $cleaner;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::MESSAGE_CONSUMED => ['messageConsumedOrFailed', -1000],
            Events::MESSAGE_CONSUMPTION_FAILED => ['messageConsumedOrFailed', -1000]
        ];
    }

    public function messageConsumedOrFailed()
    {
        $this->cleaner->cleanUp();
    }
}
