<?php

namespace Medilies\TryingPhpGlfw\Common;

trait BasicSingletonTrait
{
    private static $instance;

    public static function make(): static
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return new static;
    }

    private function __construct()
    {
    }
}
