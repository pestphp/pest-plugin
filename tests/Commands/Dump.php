<?php

use Composer\Factory;
use Composer\IO\NullIO;
use Pest\Plugin\Commands\Dump;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\Stubs\Plugin1;
use Tests\Stubs\Plugin2;

beforeEach(function () {
    $this->io = new NullIO();
    $this->composer = (new Factory())->createComposer($this->io);
    $this->dump = new Dump();
    $this->dump->setComposer($this->composer);
});

it('exists')->assertTrue(class_exists(Dump::class));

it('should find a single plugin with one plugin class', function () {
    fakePlugin('pestphp/plugin1', [Plugin1::class]);

    $this->dump->run(new ArrayInput([]), new NullOutput());

    $plugins = json_decode(file_get_contents('vendor/pest-plugins.json'), true);

    assertCount(1, $plugins);
    assertEquals(Plugin1::class, $plugins[0]);
});

it('should find a single plugin with multiple plugin classes', function () {
    fakePlugin('pestphp/plugin1', [Plugin1::class, Plugin2::class]);

    $this->dump->run(new ArrayInput([]), new NullOutput());

    $plugins = json_decode(file_get_contents('vendor/pest-plugins.json'), true);

    assertCount(2, $plugins);
    assertEquals(Plugin1::class, $plugins[0]);
    assertEquals(Plugin2::class, $plugins[1]);
});

it('should find multiple plugins', function () {
    fakePlugin('pestphp/plugin1', [Plugin1::class]);
    fakePlugin('pestphp/plugin2', [Plugin2::class]);

    $this->dump->run(new ArrayInput([]), new NullOutput());

    $plugins = json_decode(file_get_contents('vendor/pest-plugins.json'), true);

    assertCount(2, $plugins);
    assertEquals(Plugin1::class, $plugins[0]);
    assertEquals(Plugin2::class, $plugins[1]);
});

it('should find a dev plugin', function () {
    fakePlugin('pestphp/plugin1', [Plugin1::class], true);

    $this->dump->run(new ArrayInput([]), new NullOutput());

    $plugins = json_decode(file_get_contents('vendor/pest-plugins.json'), true);

    assertCount(1, $plugins);
    assertEquals(Plugin1::class, $plugins[0]);
});
