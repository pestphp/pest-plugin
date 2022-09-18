<?php

use Pest\Plugin\Commands\DumpCommand;
use Pest\Plugin\PestCommandProvider;

it('exists')->assertTrue(class_exists(PestCommandProvider::class));

it('returns the dump command', function () {
    $commandProvider = new PestCommandProvider();
    $commands = $commandProvider->getCommands();

    $this->assertCount(1, $commands);
    $this->assertInstanceOf(DumpCommand::class, $commands[0]);
});
