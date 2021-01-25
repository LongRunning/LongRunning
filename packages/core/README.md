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

final class MyCleaner implements \LongRunning\Core
{
    public function cleanUp() : void
    {
        echo "Cleaned!"
    }
}

$cleaner = new DelegatingCleaner([
    new MyCleaner(),
]);

while (true) {
    // Do heavy work, like processing jobs from a queue

    // Cleanup things
    $cleaner->cleanUp();
}
```

## Existing cleaners

LongRunning provides 2 packages that add additional cleaners:

* https://github.com/LongRunning/doctrine-orm
* https://github.com/LongRunning/sentry
