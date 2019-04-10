<?php

namespace App;

class Response
{
	/**
	 * Response to send.
	 *
	 * @var array
	 */
	protected static $response;

	/**
	 * Sends the response.
	 */
	public static function send()
	{
		echo json_encode(self::$response);
	}

	/**
	 * Adds given parameter to response.
	 *
	 * @param array $response
	 */
	public static function add($response)
	{
		foreach ($response as $key => $value) {
			self::$response[$key] = $value;
		}
	}

	/**
	 * Adds all time values to response.
	 */
	public static function addTimes()
	{
		foreach (TimeKeeper::getAll() as $key => $values) {
			self::$response["time"][$key]["seconds"] = $values["seconds"];
			self::$response["time"][$key]["times"] = $values["times"];
		}
	}

	/**
	 * Adds given debug information to response.
	 *
	 * @param integer|string $info
	 */
	public static function addDebug($info)
	{
		if ( ! isset(self::$response["debug"])) {
			self::$response["debug"] = "";
		}
		self::$response["debug"] = self::$response["debug"] . "<br>" . $info ;
	}
}
