<?php

namespace LongRunning\Plugin\SimpleBusRabbitMQPlugin\Tests;

use LongRunning\Plugin\SimpleBusRabbitMQPlugin\WhenMessageConsumedOrFailedCleanUp;
use SimpleBus\RabbitMQBundleBridge\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcher;

class WhenMessageConsumedOrFailedCleanUpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_tests_that_we_cleanup_after_consumed()
    {
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
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
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
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
        $delegatedCleaner = $this->getMock('LongRunning\Core\Cleaner');
        $delegatedCleaner
            ->expects($this->never())
            ->method('cleanUp');

        $cleaner = new WhenMessageConsumedOrFailedCleanUp($delegatedCleaner);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($cleaner);
        $dispatcher->dispatch('random-event');
    }
}
