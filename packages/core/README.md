# LongRunning Core

This is a read only split from the [LongRunning](https://github.com/LongRunning/LongRunning) mono repository.

If you want to make changes, please create a pull request [there](https://github.com/LongRunning/LongRunning/pulls).

## Installation

```
composer require long-running/core
```

## Symfony

If you are using Symfony, make sure to enable the bundle:
```php
<?php
// config/bundles.php

return [
    // ...
    LongRunning\Core\Bundle\LongRunningBundle::class => ['all' => true],
];
```

## How to use?

```php
<?php

final class MyCleaner implements \LongRunning\Core\Cleaner
{
    public function cleanUp() : void
    {
        echo "Cleaning up memory!";
    }
}

$cleaner = new \LongRunning\Core\DelegatingCleaner([
    new MyCleaner(),
]);

while (true) {
    // Do heavy work, like processing jobs from a queue
    echo "Doing heavy work";
    sleep(1);
    echo "Done with heavy work";

    // Cleanup things
    $cleaner->cleanUp();
}
```

If you are using Symfony, any service that implements the `LongRunning\Core\Cleaner` interface
will be autoconfigured and added to the `LongRunning\Core\DelegatingCleaner`.

The `LongRunning\Core\DelegatingCleaner` is aliased to `LongRunning\Core\Cleaner`.

That means that you can inject the `LongRunning\Core\Cleaner` service in your worker and it will
call all configured cleaners on `cleanUp()`:
```php
<?php

namespace App;

use LongRunning\Core\Cleaner;

final class Worker
{
    private Cleaner $cleaner;

    public function __construct(Cleaner $cleaner)
    {
        $this->cleaner = $cleaner;
    }

    public function doWork() : void
    {
        while (true) {
            // Do heavy work, like processing jobs from a queue
            echo "Doing heavy work";
            sleep(1);
            echo "Done with heavy work";

            // Cleanup things
            $this->cleaner->cleanUp();
        }
    }
}
```

## Existing cleaners

LongRunning provides 2 packages that add additional cleaners:

* [doctrine-orm](https://github.com/LongRunning/doctrine-orm)
* [sentry](https://github.com/LongRunning/sentry)
