<?php

namespace LongRunning\Tests\Plugin\BernardPlugin;

use LongRunning\Plugin\BernardPlugin\WhenEnvelopeAcknowledgedOrRejectedCleanUp;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WhenEnvelopeAcknowledgedOrRejectedCleanUpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_envelope_acknowledge()
    {
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
        $delegatedCleaner
            ->expects($this->once())
            ->method('cleanUp');

        $cleaner = new WhenEnvelopeAcknowledgedOrRejectedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('bernard.acknowledge');
    }

    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_envelope_rejected()
    {
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
        $delegatedCleaner
            ->expects($this->once())
            ->method('cleanUp');

        $cleaner = new WhenEnvelopeAcknowledgedOrRejectedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('bernard.reject');
    }

    /**
     * @test
     */
    public function it_tests_that_we_dont_cleanup_after_other_events()
    {
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
        $delegatedCleaner
            ->expects($this->never())
            ->method('cleanUp');

        $cleaner = new WhenEnvelopeAcknowledgedOrRejectedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('random-event');
    }
}
