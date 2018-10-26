<?php

namespace LongRunning\Tests\Bundle\LongRunningBundle\DependencyInjection\Compiler;

use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\MonologCleanersPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class MonologCleanersPassFingersCrossedTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Definition
     */
    private $fingersCrossedHandlersDefinition;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();

        $this->fingersCrossedHandlersDefinition = new Definition('LongRunning\Plugin\MonologPlugin\ClearFingersCrossedHandlers', array(array()));
        $this->container->setDefinition('long_running.monolog.clear_fingers_crossed_handlers', $this->fingersCrossedHandlersDefinition);

        $this->container->addCompilerPass(new MonologCleanersPass());
    }

    /**
     * @test
     */
    public function it_gets_fingers_crossed_handlers()
    {
        $this->createFingersCrossedHandler('monolog.handler.handler1');
        $this->createFingersCrossedHandler('monolog.handler.handler2');
        $this->createFingersCrossedHandler('monolog.handler.handler3');

        $this->container->compile();

        $this->resolverContainsFingersCrossedHandlers(array('monolog.handler.handler1', 'monolog.handler.handler2', 'monolog.handler.handler3'));
    }

    private function createFingersCrossedHandler($name)
    {
        $handler = new Definition('Monolog\Handler\FingersCrossedHandler', array(array()));
        $this->container->setDefinition($name, $handler);
    }

    private function resolverContainsFingersCrossedHandlers($expectedResolverIds)
    {
        $actualResolverIds = [];
        foreach ($this->fingersCrossedHandlersDefinition->getArgument(0) as $argument) {
            $this->assertInstanceOf(
                'Symfony\Component\DependencyInjection\Reference',
                $argument
            );
            $actualResolverIds[] = (string) $argument;
        }
        $this->assertEquals($expectedResolverIds, $actualResolverIds);
    }
}
