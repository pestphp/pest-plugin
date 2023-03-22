<?php

use Pest\Plugin\Loader;
use Pest\Plugins\PluginOne;
use Pest\Plugins\PluginTwo;
use Tests\Stubs\AnotherDummyInterface;
use Tests\Stubs\DummyInterface;
use Tests\Stubs\Plugin1;
use Tests\Stubs\Plugin2;
use Tests\Stubs\Plugin3;
use Tests\Stubs\YetAnotherDummyInterface;

beforeEach(function () {
    file_put_contents(
        sprintf('%s/vendor/pest-plugins.json', getcwd()),
        json_encode(
            [
                // An "official" Pest plugin
                PluginOne::class,
                Plugin1::class,
                Plugin2::class,
                // Another "official" Pest plugin
                PluginTwo::class,
                Plugin3::class,
            ],
            JSON_PRETTY_PRINT
        )
    );
});

afterEach(function () {
    Loader::reset();
});

it('exists')->assertTrue(class_exists(Loader::class));

it('returns a single plugin instance', function () {
    $plugins = Loader::getPlugins(DummyInterface::class);

    $this->assertCount(1, $plugins);
    $this->assertInstanceOf(DummyInterface::class, $plugins[0]);
});

it('returns multiple plugin instances', function () {
    $plugins = Loader::getPlugins(AnotherDummyInterface::class);

    $this->assertCount(2, $plugins);
    $this->assertInstanceOf(AnotherDummyInterface::class, $plugins[0]);
    $this->assertInstanceOf(AnotherDummyInterface::class, $plugins[1]);
});

it('return no plugins when plugin cache file is missing', function () {
    unlink(sprintf('%s/vendor/pest-plugins.json', getcwd()));
    $plugins = Loader::getPlugins(DummyInterface::class);

    $this->assertEmpty($plugins);
});

it('returns no plugins when plugin cache file does not contain valid json', function () {
    file_put_contents(sprintf('%s/vendor/pest-plugins.json', getcwd()), 'abcd');
    $plugins = Loader::getPlugins(DummyInterface::class);

    $this->assertEmpty($plugins);
});

it('places Pest plugins after 3rd party plugins', function () {
    $plugins = Loader::getPlugins(YetAnotherDummyInterface::class);

    expect($plugins)
        ->toHaveCount(5)
        ->{3}->toBeInstanceOf(PluginOne::class)
        ->{4}->toBeInstanceOf(PluginTwo::class);
});
