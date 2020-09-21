<?php

namespace LongRunning\Tests\Plugin\SimpleBusRabbitMQPlugin;

use LongRunning\Core\Cleaner;
use LongRunning\Plugin\SimpleBusRabbitMQPlugin\WhenMessageConsumedOrFailedCleanUp;
use PHPUnit\Framework\TestCase;
use SimpleBus\RabbitMQBundleBridge\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WhenMessageConsumedOrFailedCleanUpTest extends TestCase
{
    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_consumed()
    {
        $delegatedCleaner = $this->createMock(Cleaner::class);
        $delegatedCleaner
            ->expects($this->once())
            ->method('cleanUp');

        $cleaner = new WhenMessageConsumedOrFailedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch(Events::MESSAGE_CONSUMED);
    }

    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_consumption_failed()
    {
        $delegatedCleaner = $this->createMock(Cleaner::class);
        $delegatedCleaner
            ->expects($this->once())
            ->method('cleanUp');

        $cleaner = new WhenMessageConsumedOrFailedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch(Events::MESSAGE_CONSUMPTION_FAILED);
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

        $cleaner = new WhenMessageConsumedOrFailedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('random-event');
    }
}
