<?php

namespace App;

class GameState
{
	/**
	 * Game state in FEN (Forsyth–Edwards notation).
	 * FEN does not contain previous board positions, so it is impossible to determine threefold repetition just from this.
	 *
	 * @var string
	 */
	protected static $fen;

	/**
	 * Game state in PGN (Portable game notation).
	 *
	 * @var string
	 */
	protected static $pgn;

	/**
	 * Move history in SAN (Standard algebraic notation).
	 *
	 * @var array
	 */
	protected static $history;

	/**
	 * Construction type of game as "fen" or "pgn".
	 * This will be passed to the rule engine to determine how it will construct the game.
	 *
	 * @var string
	 */
	protected static $constructType;

	/**
	 * Sets the game state.
	 */
	public static function set()
	{
		self::$pgn = $_POST["pgn"] ?? "";
		self::$fen = $_POST["fen"] ?? "";
		self::$history = $_POST["history"] ?? "";
		self::$constructType = $_POST["construct_type"] ?? "";
	}

	/**
	 * Gets the PGN.
	 *
	 * @return string
	 */
	public static function getPgn()
	{
		return self::$pgn;
	}

	/**
	 * Gets the FEN.
	 *
	 * @return string
	 */
	public static function getFen()
	{
		return self::$fen;
	}

	/**
	 * Gets the history.
	 *
	 * @return array
	 */
	public static function getHistory()
	{
		return self::$history;
	}

	/**
	 * Gets the construction type of game as "fen" or "pgn".
	 *
	 * @return string
	 */
	public static function getConstructType()
	{
		return self::$constructType;
	}
}
