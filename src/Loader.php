<?php

declare(strict_types=1);

namespace Pest\Plugin;

use JsonException;

/**
 * @internal
 */
final class Loader
{
    /**
     * determines if the plugin cache file was loaded.
     *
     * @var bool
     */
    private static $loaded = false;

    /**
     * holds the list of cached plugin instances.
     *
     * @var array<int, object>
     */
    private static $instances = [];

    /**
     * returns an array of pest plugins to execute.
     *
     * @param string $interface the interface for the hook to execute
     *
     * @return array<int, object> list of plugins
     */
    public static function getPlugins(string $interface): array
    {
        return array_values(
            array_filter(
                static::getPluginInstances(),
                function ($plugin) use ($interface): bool {
                    return $plugin instanceof $interface;
                }
            )
        );
    }

    public static function reset(): void
    {
        static::$loaded    = false;
        static::$instances = [];
    }

    /**
     * returns the list of plugins instances.
     *
     * @return array<int, object>
     */
    private static function getPluginInstances(): array
    {
        if (!static::$loaded) {
            $cachedPlugins = sprintf('%s/vendor/pest-plugins.json', getcwd());

            if (!file_exists($cachedPlugins)) {
                return [];
            }

            $content = file_get_contents($cachedPlugins);
            if ($content === false) {
                return [];
            }

            try {
                $pluginClasses = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $ex) {
                $pluginClasses = [];
            }

            static::$instances = array_map(
                function ($class) {
                    return new $class();
                },
                $pluginClasses
            );
            static::$loaded = true;
        }

        return static::$instances;
    }
}
