# LongRunning Sentry

This is a read only split from the [LongRunning](https://github.com/LongRunning/LongRunning) mono repository.

If you want to make changes, please create a pull request [there](https://github.com/LongRunning/LongRunning/pulls).

This packages requires Sentry SDK 3.1 or higher.

## Installation

```
composer require long-running/sentry
```

## Symfony

If you are using Symfony, make sure to install the [Sentry Symfony SDK](https://github.com/getsentry/sentry-symfony).

```
composer require sentry/sentry-symfony
```

Then register the bundle:
```php
<?php
// config/bundles.php

return [
    // ...
    Sentry\SentryBundle\SentryBundle::class => ['all' => true],
    LongRunning\Core\Bundle\LongRunningBundle::class => ['all' => true],
    LongRunning\Sentry\Bundle\LongRunningSentryBundle::class => ['all' => true],
];
```
