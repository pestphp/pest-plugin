<?php

declare(strict_types=1);

namespace Pest\Plugin\Commands;

use Composer\Command\BaseCommand;
use Pest\Plugin\Manager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
final class DumpCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->setName('pest:dump-plugins');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $vendorDirectory = $this->getComposer()->getConfig()->get('vendor-dir');
        $plugins         = [];

        $repository       = $this->getComposer()->getRepositoryManager()->getLocalRepository();
        $requiredPackages = array_merge(
            $this->getComposer()->getPackage()->getRequires(),
            $this->getComposer()->getPackage()->getDevRequires()
        );

        foreach ($requiredPackages as $link) {
            $package = $repository->findPackage($link->getTarget(), $link->getConstraint() ?? '');

            if ($package !== null) {
                $extra   = $package->getExtra();
                $plugins = array_merge($plugins, $extra['pest']['plugins'] ?? []);
            }
        }

        file_put_contents(
            implode(DIRECTORY_SEPARATOR, [$vendorDirectory, Manager::PLUGIN_CACHE_FILE]),
            json_encode($plugins, JSON_PRETTY_PRINT)
        );

        return 0;
    }
}
