<?php

use Composer\Factory;
use Composer\IO\NullIO;
use Pest\Plugin\Manager;
use Pest\Plugin\PestCommandProvider;

beforeEach(function () {
    $this->manager = new Manager();
    $this->io = new NullIO();
    $this->composer = (new Factory())->createComposer($this->io);
});

it('exists')->assertTrue(class_exists(Manager::class));

it('removes the cached plugins file on uninstall', function () {
    touch('vendor/pest-plugins.json');

    $this->manager->uninstall($this->composer, $this->io);

    $this->assertFileDoesNotExist('vendor/pest-plugins.json');
});

it('should create the cached plugins file', function () {
    $this->manager->activate($this->composer, $this->io);
    $this->manager->registerPlugins();

    $this->assertFileExists('vendor/pest-plugins.json');
});

it('subscribes for the post-autoload-dump event', function () {
    $this->assertArrayHasKey('post-autoload-dump', $this->manager->getSubscribedEvents());
});

it('has the capability for the dump command', function () {
    $this->assertContains(PestCommandProvider::class, $this->manager->getCapabilities());
});
