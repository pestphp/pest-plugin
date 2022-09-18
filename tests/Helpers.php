<?php

use Composer\Package\CompletePackage;
use Composer\Package\Link;
use Composer\Semver\Constraint\Constraint;

/**
 * Creates the plugin requirement in the composer instance.
 *
 * @param  string  $pluginName the name of the plugin to fake
 * @param array<int, string> plugin classes to load
 * @param  bool  $dev determines if it should be added as a dev dependency
 */
function fakePlugin(string $pluginName, array $classes, bool $dev = false): void
{
    $test = test();

    $requires = $dev ?
        $test->composer->getPackage()->getDevRequires() :
        $test->composer->getPackage()->getRequires();

    $link = new Link(
        'pestphp/pest-plugin',
        $pluginName,
        new Constraint('=', '9999999-dev'),
        'requires',
        'dev-master'
    );
    $requires[$pluginName] = $link;

    if ($dev) {
        $test->composer->getPackage()->setDevRequires($requires);
    } else {
        $test->composer->getPackage()->setRequires($requires);
    }

    $repository = $test->composer->getRepositoryManager()->getLocalRepository();
    $package = new CompletePackage($pluginName, '9999999-dev', 'dev-master');
    $package->setExtra([
        'pest' => [
            'plugins' => $classes,
        ],
    ]);
    $repository->addPackage($package);
}
