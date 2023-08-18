<?php

namespace Medilies\TryingPhpGlfw\Common;

trait BasicSingletonTrait
{
    /** @phpstan-ignore-next-line */
    private static $instance;

    private function __construct()
    {
    }

    public static function make(): static
    {
        if (! self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
