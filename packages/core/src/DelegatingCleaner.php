<?php

namespace LongRunning\Core;

final class DelegatingCleaner implements Cleaner
{
    /**
     * @var iterable<Cleaner>
     */
    private iterable $cleaners;

    /**
     * @param iterable<Cleaner> $cleaners
     */
    public function __construct(iterable $cleaners)
    {
        $this->cleaners = $cleaners;
    }

    public function cleanUp(): void
    {
        foreach ($this->cleaners as $cleaner) {
            $cleaner->cleanUp();
        }
    }
}
