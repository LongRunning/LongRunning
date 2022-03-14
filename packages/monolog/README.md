# LongRunning Monolog

This is a read only split from the [LongRunning](https://github.com/LongRunning/LongRunning) mono repository.

If you want to make changes, please create a pull request [there](https://github.com/LongRunning/LongRunning/pulls).

## Installation

```
composer require long-running/monolog
```

## Symfony

If you are using Symfony, make sure to install the [Symfony Monolog bundle](https://symfony.com/doc/current/logging.html#monolog).

```
composer require symfony/monolog-bundle
```

Then register the bundle:

```php
<?php
// config/bundles.php

return [
    // ...
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    LongRunning\Core\Bundle\LongRunningBundle::class => ['all' => true],
    LongRunning\Monolog\Bundle\LongRunningMonologBundle::class => ['all' => true],
];
```
