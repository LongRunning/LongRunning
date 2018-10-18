<?php

namespace LongRunning\Plugin\EnqueuePlugin;


interface ObjectManager extends \Doctrine\Common\Persistence\ObjectManager
{
    public function isOpen();
}