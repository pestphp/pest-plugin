<?php

use Pest\Plugin\Commands\DumpCommand;
use Pest\Plugin\PestCommandProvider;

it('exists')->assertTrue(class_exists(PestCommandProvider::class));

it('returns the dump command', function () {
    $commandProvider = new PestCommandProvider();
    $commands = $commandProvider->getCommands();

    assertCount(1, $commands);
    assertInstanceOf(DumpCommand::class, $commands[0]);
});
