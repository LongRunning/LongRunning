<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::DEFAULT_BRANCH_NAME, 'main');
    $parameters->set(Option::DATA_TO_APPEND, [
        ComposerJsonSection::REQUIRE_DEV => [
            'phpunit/phpunit' => '^9.5',
            'symplify/monorepo-builder' => '^9.0',
        ],
    ]);
    $parameters->set(Option::PACKAGE_DIRECTORIES, [__DIR__ . '/packages']);
};
