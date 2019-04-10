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
	 * Sets the FEN.
	 *
	 * @param string $fen
	 */
	public static function setFen($fen)
	{
		self::$fen = $fen;
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
	 * Sets the PGN.
	 *
	 * @param string $pgn
	 */
	public static function setPgn($pgn)
	{
		self::$pgn = $pgn;
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
}
