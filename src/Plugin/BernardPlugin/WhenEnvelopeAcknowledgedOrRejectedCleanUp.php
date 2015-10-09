<?php

namespace LongRunning\Plugin\BernardPlugin;

use LongRunning\Core\Cleaner;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WhenEnvelopeAcknowledgedOrRejectedCleanUp implements EventSubscriberInterface
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
            'bernard.acknowledge' => ['envelopeAcknowledgedOrRejected', -1000],
            'bernard.reject' => ['envelopeAcknowledgedOrRejected', -1000]
        ];
    }

    public function envelopeAcknowledgedOrRejected()
    {
        $this->cleaner->cleanUp();
    }
}
