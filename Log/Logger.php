<?php

namespace Log;

class Logger
{
	/**
	 * Log file name.
	 *
	 * @var string
	 */
	protected const LOG_FILE = "Log/logs.log";

	/**
	 * Writes given content to the log file.
	 *
	 * @param string $content
	 */
	public static function log($content)
	{
		file_put_contents(self::LOG_FILE, "\n" . $content, FILE_APPEND);
	}

	/**
	 * Clears log file.
	 */
	public static function clear()
	{
		if (file_exists(self::LOG_FILE)) {
			unlink(self::LOG_FILE);
		}
	}
}
