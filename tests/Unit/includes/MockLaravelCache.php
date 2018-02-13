<?php

namespace Illuminate\Support\Facades;

abstract class MockLaravelCache
{
    public static function put($key, $value, $expiry)
    {
    }

    public static function get($key)
    {
    }
}
