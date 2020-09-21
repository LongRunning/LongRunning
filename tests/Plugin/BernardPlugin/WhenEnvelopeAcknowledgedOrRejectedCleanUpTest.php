<?php

namespace LongRunning\Tests\Plugin\BernardPlugin;

use LongRunning\Core\Cleaner;
use LongRunning\Plugin\BernardPlugin\WhenEnvelopeAcknowledgedOrRejectedCleanUp;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WhenEnvelopeAcknowledgedOrRejectedCleanUpTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_envelope_acknowledge()
    {
        $delegatedCleaner = $this->createMock(Cleaner::class);
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
        $delegatedCleaner = $this->createMock(Cleaner::class);
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
        $delegatedCleaner = $this->createMock(Cleaner::class);
        $delegatedCleaner
            ->expects($this->never())
            ->method('cleanUp');

        $cleaner = new WhenEnvelopeAcknowledgedOrRejectedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('random-event');
    }
}
