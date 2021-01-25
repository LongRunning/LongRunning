# LongRunning Doctrine ORM

This is a read only split from the [LongRunning](https://github.com/LongRunning/LongRunning) mono repository.

If you want to make changes, please create a pull request [there](https://github.com/LongRunning/LongRunning/pulls).

This packages requires Doctrine ORM 2.7 or higher.

## Installation

```
composer require long-running/doctrine-orm
```

## Symfony

If you are using Symfony, make sure to install the [DoctrineBundle](https://github.com/doctrine/DoctrineBundle).

```
composer require doctrine/doctrine-bundle
```

Then register the bundle:
```php
<?php
// config/bundles.php

return [
    // ...
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    LongRunning\Core\Bundle\LongRunningBundle::class => ['all' => true],
    LongRunning\DoctrineORM\Bundle\LongRunningDoctrineORMBundle::class => ['all' => true],
];
```
