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
	 * Checks if board set up as other than starting position.
	 *
	 * @var boolean
	 */
	protected static $boardSet;

	/**
	 * Sets the game state.
	 */
	public static function set()
	{
		self::$pgn = $_POST["pgn"] ?? "";
		self::$fen = $_POST["fen"] ?? "";
		self::$history = $_POST["history"] ?? "";
		self::$boardSet = $_POST["board_set"] ?? false;
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
	 * Sets the history.
	 *
	 * @param array $history
	 */
	public static function setHistory($history)
	{
		self::$history = $history;
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
	 * Checks if board set up as other than starting position.
	 *
	 * @return boolean
	 */
	public static function boardSet()
	{
		return self::$boardSet;
	}
}
