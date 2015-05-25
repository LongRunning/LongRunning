<?php

namespace LongRunning\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FirstTryTest extends KernelTestCase
{
    protected static function getKernelClass()
    {
        return 'LongRunning\Tests\Functional\TestKernel';
    }

    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @test
     */
    public function it_trys_to_boot()
    {

    }

}
