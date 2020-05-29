<?php

use Pest\Plugin\CommandProvider;
use Pest\Plugin\Commands\Dump;

it('exists')->assertTrue(class_exists(CommandProvider::class));

it('returns the dump command', function () {
    $commandProvider = new CommandProvider();
    $commands = $commandProvider->getCommands();

    assertCount(1, $commands);
    assertInstanceOf(Dump::class, $commands[0]);
});
