<?php

namespace LongRunning\Tests\Plugin\EnqueuePlugin;

interface ObjectManager extends \Doctrine\Common\Persistence\ObjectManager
{
    public function isOpen();
}
