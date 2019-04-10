<?php

namespace App;

class GlobalCache
{
	/**
	 * Cache keys and values.
	 *
	 * @var array
	 */
	protected static $cache;

	/**
	 * Adds key value pair to cache.
	 *
	 * @param integer|string $key
	 * @param mixed $value
	 */
	public static function put($key, $value)
	{
		self::$cache[$key] = $value;
	}

	/**
	 * Gets value associated with given key from the cache.
	 *
	 * @param  integer|string $key
	 * @return mixed
	 */
	public static function get($key)
	{
		return self::$cache[$key] ?? null;
	}

	/**
	 * Checks if the value associated with the given key exists in the cache.
	 *
	 * @param  integer|string $key
	 * @return boolean
	 */
	public static function has($key)
	{
		return isset(self::$cache[$key]);
	}
}
