<?php

namespace LongRunning\Tests\Bundle\LongRunningBundle\DependencyInjection\Compiler;

use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\RegisterCleanersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RegisterCleanersPassTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Definition
     */
    private $delegatingCleanerDefinition;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();

        $this->delegatingCleanerDefinition = new Definition('LongRunning\Core\DelegatingCleaner', array(array()));
        $this->container->setDefinition('long_running.delegating_cleaner', $this->delegatingCleanerDefinition);

        $this->container->addCompilerPass(new RegisterCleanersPass());
    }

    /**
     * @test
     */
    public function it_has_no_definition()
    {
        $this->container->removeDefinition('long_running.delegating_cleaner');

        $this->container->compile();
    }

    /**
     * @test
     */
    public function it_gets_cleaners()
    {
        $this->createCleaner('cleaner1');
        $this->createCleaner('cleaner2');
        $this->createCleaner('cleaner3');

        $this->container->compile();

        $this->resolverContainsCleaners(array('cleaner1', 'cleaner2', 'cleaner3'));
    }

    private function createCleaner($name)
    {
        $cleaner = new Definition('LongRunning\Core\Cleaner');
        $cleaner->addTag('long_running.cleaner');
        $this->container->setDefinition($name, $cleaner);
    }

    private function resolverContainsCleaners($expectedResolverIds)
    {
        $actualResolverIds = [];
        foreach ($this->delegatingCleanerDefinition->getArgument(0) as $argument) {
            $this->assertInstanceOf(
                'Symfony\Component\DependencyInjection\Reference',
                $argument
            );
            $actualResolverIds[] = (string) $argument;
        }
        $this->assertEquals($expectedResolverIds, $actualResolverIds);
    }
}
