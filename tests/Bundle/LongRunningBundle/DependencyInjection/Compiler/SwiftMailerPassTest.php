<?php

namespace LongRunning\Tests\Bundle\LongRunningBundle\DependencyInjection\Compiler;

use LongRunning\Bundle\LongRunningBundle\DependencyInjection\Compiler\SwiftMailerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class SwiftMailerPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var Definition
     */
    private $swiftMailerCleaner;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();

        $this->swiftMailerCleaner = new Definition('LongRunning\Plugin\SwiftMailerPlugin\ClearSpools', array(array(), array()));
        $this->container->setDefinition('long_running.swift_mailer.clear_spools', $this->swiftMailerCleaner);
        $this->container->setParameter('swiftmailer.mailers', []);

        $this->container->addCompilerPass(new SwiftMailerPass());
    }

    /**
     * @test
     */
    public function it_has_no_definition()
    {
        $this->container->removeDefinition('long_running.swift_mailer.clear_spools');

        $this->container->compile();
    }

    /**
     * @test
     */
    public function it_has_no_mailers()
    {
        $this->container->compile();
    }

    /**
     * @test
     */
    public function it_gets_mailers()
    {
        $this->createMailer('mailer1', true);
        $this->createMailer('mailer2');
        $this->createMailer('mailer3');

        $this->container->compile();

        $this->matchSpools(array('swiftmailer.mailer.mailer1.spool', 'swiftmailer.mailer.mailer2.spool', 'swiftmailer.mailer.mailer3.spool'));
        $this->matchTransports(array('swiftmailer.mailer.mailer1.transport.real', 'swiftmailer.mailer.mailer2.transport.real', 'swiftmailer.mailer.mailer3.transport.real'));
        $this->matchNames();
    }

    private function createMailer($name, $default = false)
    {
        $mailer = new Definition('Swift_Mailer');
        $this->container->setDefinition(sprintf('swiftmailer.mailer.%s', $name), $mailer);

        if ($default) {
            $this->container->setAlias('swiftmailer.mailer', sprintf('swiftmailer.mailer.%s', $name));
        }

        $this->container->setParameter(sprintf('swiftmailer.mailer.%s.spool.enabled', $name), true);

        $mailers = $this->container->getParameter('swiftmailer.mailers');
        $mailers[$name] = 'swiftmailer.mailer.' . $name;
        $this->container->setParameter('swiftmailer.mailers', $mailers);

        $transport = new Definition('Swift_Transport_SpoolTransport');
        $this->container->setDefinition(sprintf('swiftmailer.mailer.%s.transport', $name), $transport);

        $spool = new Definition('Swift_MemorySpool');
        $this->container->setDefinition(sprintf('swiftmailer.mailer.%s.spool', $name), $spool);

        $realTransport = new Definition('Swift_SmtpTransport');
        $this->container->setDefinition(sprintf('swiftmailer.mailer.%s.transport.real', $name), $realTransport);

    }

    private function matchSpools($expectedResolverIds)
    {
        $actualResolverIds = [];
        foreach ($this->swiftMailerCleaner->getArgument(0) as $name => $argument) {
            $this->assertInstanceOf(
                'Symfony\Component\DependencyInjection\Reference',
                $argument
            );

            $this->assertEquals(sprintf('swiftmailer.mailer.%s.spool', $name), (string) $argument);
            $actualResolverIds[] = (string) $argument;
        }

        $this->assertEquals($expectedResolverIds, $actualResolverIds);
    }

    private function matchTransports($expectedResolverIds)
    {
        $actualResolverIds = [];
        foreach ($this->swiftMailerCleaner->getArgument(1) as $name => $argument) {
            $this->assertInstanceOf(
                'Symfony\Component\DependencyInjection\Reference',
                $argument
            );

            $this->assertEquals(sprintf('swiftmailer.mailer.%s.transport.real', $name), (string) $argument);
            $actualResolverIds[] = (string) $argument;
        }

        $this->assertEquals($expectedResolverIds, $actualResolverIds);
    }

    private function matchNames()
    {
        $spoolNames = array_keys($this->swiftMailerCleaner->getArgument(0));
        $transportNames = array_keys($this->swiftMailerCleaner->getArgument(1));

        $this->assertEquals($spoolNames, $transportNames);
    }
}
