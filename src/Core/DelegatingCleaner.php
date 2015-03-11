<?php

namespace LongRunning\Core;

class DelegatingCleaner implements Cleaner
{
    /**
     * @var Cleaner[]
     */
    private $cleaners;

    public function __construct(array $cleaners)
    {
        $this->cleaners = $cleaners;
    }

    public function cleanUp()
    {
        foreach ($this->cleaners as $cleaner) {
            $cleaner->cleanUp();
        }
    }
}
