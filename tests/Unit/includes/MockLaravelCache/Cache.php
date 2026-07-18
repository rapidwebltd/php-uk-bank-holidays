<?php

namespace Illuminate\Support\Facades;

abstract class Cache
{
    private static $root;
    private static $items = [];

    public static function setFacadeRoot($root)
    {
        self::$root = $root;
    }

    public static function getFacadeRoot()
    {
        return self::$root;
    }

    public static function setItems(array $items)
    {
        self::$items = $items;
    }

    public static function put($key, $value, $expiry)
    {
        self::$items[$key] = $value;
    }

    public static function get($key)
    {
        return isset(self::$items[$key]) ? self::$items[$key] : null;
    }
}
