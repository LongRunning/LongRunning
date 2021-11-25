<?php

namespace LongRunning\CI\Command;

use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\Constraint\MultiConstraint;
use Composer\Semver\VersionParser;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class RebuildSymfonyRequirementsCommand extends Command
{
    private const PACKAGE = 'package';
    private const VERSION = 'version';
    private const DRY_RUN = 'dry-run';
    private const IGNORED_PACKAGES = ['symfony/monolog-bundle'];

    private VersionParser $versionParser;

    public function __construct()
    {
        parent::__construct();

        $this->versionParser = new VersionParser();
    }

    protected function configure(): void
    {
        $this->setName('rebuild-symfony-requirements');
        $this->addArgument(
            self::PACKAGE,
            InputArgument::REQUIRED
        );
        $this->addArgument(
            self::VERSION,
            InputArgument::REQUIRED
        );
        $this->addOption(
            self::DRY_RUN,
            null,
            InputOption::VALUE_NONE
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $package = $input->getArgument(self::PACKAGE);
        $newVersion = $input->getArgument(self::VERSION);
        $dryRun = $input->getOption(self::DRY_RUN);

        if (!is_string($package)) {
            throw new LogicException('Package argument should be a string');
        }

        if (!is_string($newVersion)) {
            throw new LogicException('Version argument should be a string');
        }

        $path = __DIR__.'/../../packages/'.$package.'/composer.json';

        if (false === $content = file_get_contents($path)) {
            throw new LogicException('composer.json content could not be read');
        }

        $content = json_decode(
            $content,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $content['require'] = $this->replace($content['require'], $newVersion);
        $content['require-dev'] = $this->replace($content['require-dev'], $newVersion);

        $json = json_encode($content, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($dryRun) {
            $output->writeln($json);
            return self::SUCCESS;
        }

        file_put_contents($path, $json.PHP_EOL);

        return self::SUCCESS;
    }

    /**
     * @param array<string, string> $require
     *
     * @return array<string, string>
     */
    private function replace(array $require, string $newVersion): array
    {
        $newVersionConstraint = $this->versionParser->parseConstraints($newVersion);

        foreach ($require as $package => $version) {
            if (0 !== strpos($package, 'symfony/')) {
                continue;
            }

            if (in_array($package, self::IGNORED_PACKAGES, true)) {
                continue;
            }

            $constraints = $this->versionParser->parseConstraints($version);
            $newConstraint = $this->matches($constraints, $newVersionConstraint, $version, $newVersion);
            $newVersion = sprintf('^%s', $newConstraint->getLowerBound()->getVersion());

            echo sprintf("Change %s \"%s\" to \"%s\"\n", $package, $version, $newVersion);

            $require[$package] = $newVersion;
        }

        return $require;
    }

    private function matches(
        ConstraintInterface $multi,
        ConstraintInterface $provider,
        string $version,
        string $newVersion
    ): ConstraintInterface {
        if (!$multi instanceof MultiConstraint) {
            throw new LogicException(sprintf('The constraint is not a %s', MultiConstraint::class));
        }

        if (false === $multi->isConjunctive()) {
            foreach ($multi->getConstraints() as $constraint) {
                if ($provider->matches($constraint)) {
                    return $constraint;
                }
            }

            throw new LogicException(sprintf('Cannot match %s with %s', $version, $newVersion));
        }

        foreach ($multi->getConstraints() as $constraint) {
            if (!$provider->matches($constraint)) {
                throw new LogicException(sprintf('Cannot match %s with %s', $version, $newVersion));
            }
        }

        return $provider;
    }
}
