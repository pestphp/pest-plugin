<?php

use Pest\Plugin\Loader;
use Tests\Stubs\AnotherDummyInterface;
use Tests\Stubs\DummyInterface;
use Tests\Stubs\Plugin1;
use Tests\Stubs\Plugin2;
use Tests\Stubs\Plugin3;

beforeEach(function () {
    file_put_contents(
        sprintf('%s/vendor/pest-plugins.json', getcwd()),
        json_encode(
            [
                Plugin1::class,
                Plugin2::class,
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

    assertCount(1, $plugins);
    assertInstanceOf(DummyInterface::class, $plugins[0]);
});

it('returns multiple plugin instances', function () {
    $plugins = Loader::getPlugins(AnotherDummyInterface::class);

    assertCount(2, $plugins);
    assertInstanceOf(AnotherDummyInterface::class, $plugins[0]);
    assertInstanceOf(AnotherDummyInterface::class, $plugins[1]);
});

it('return no plugins when plugin cache file is missing', function () {
    unlink(sprintf('%s/vendor/pest-plugins.json', getcwd()));
    $plugins = Loader::getPlugins(DummyInterface::class);

    assertEmpty($plugins);
});

it('returns no plugins when plugin cache file does not contain valid json', function () {
    file_put_contents(sprintf('%s/vendor/pest-plugins.json', getcwd()), 'abcd');
    $plugins = Loader::getPlugins(DummyInterface::class);

    assertEmpty($plugins);
});
