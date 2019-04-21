<?php

namespace App;

class TimeKeeper
{
	/**
	 * Recorded times.
	 *
	 * @var array
	 */
	protected static $times;

	/**
	 * Starts timer.
	 *
	 * @param string $key
	 */
	public static function start($key)
	{
		self::$times[$key]["microtime"] = microtime(true);
	}

	/**
	 * Stops timer.
	 *
	 * @param string $key
	 */
	public static function stop($key)
	{
		if ( ! isset(self::$times[$key]["seconds"])) {
			self::$times[$key]["seconds"] = 0;
		}
		self::$times[$key]["seconds"] += microtime(true) - self::$times[$key]["microtime"];

		if ( ! isset(self::$times[$key]["times"])) {
			self::$times[$key]["times"] = 0;
		}
		++self::$times[$key]["times"];
	}

	/**
	 * Gets all time values.
	 *
	 * @return array
	 */
	public static function getAll()
	{
		return self::$times;
	}

	/**
	 * Gets the elapsed time corresponding to given key.
	 *
	 * @param  string $key
	 * @return double
	 */
	public static function getElapsedTime($key)
	{
		return microtime(true) - self::$times[$key]["microtime"];
	}
}
