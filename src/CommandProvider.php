<?php

declare(strict_types=1);

namespace Pest\Plugin;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Pest\Plugin\Commands\Dump;

/**
 * @internal
 */
final class CommandProvider implements CommandProviderCapability
{
    public function getCommands(): array
    {
        return [
            new Dump(),
        ];
    }
}
