<?php

namespace App\Engine;

interface RuleEngine
{
	/**
	 * Constructs object and loads a game state representation.
	 * Game state must have complete information to determine rules if the AI should receive complete information.
	 */
	public function __construct();

	/**
	 * Gets all possible moves in SAN.
	 *
	 * @return array
	 */
	public function getPossibleMoves();

	/**
	 * Makes a move in SAN.
	 *
	 * @param string
	 */
	public function makeMove($move);

	/**
	 * Take back last move.
	 *
	 * @param string
	 */
	public function undoMove();

	/**
	 * Gets pieces on board as "color type" like "black queen".
	 *
	 * @param array
	 */
	public function getPieces();

	/**
	 * Gets side to move as "white" or "black".
	 *
	 * @param string
	 */
	public function getTurn();

	/**
	 * Gets the game winner as "white" or "black".
	 *
	 * @return string|null
	 */
	public function getWinner();

	/**
	 * Checks if game is over.
	 *
	 * @return boolean
	 */
	public function gameOver();

	/**
	 * Gets the pieces by position.
	 * For adaptability to piece-square tables the returned array must order pieces by a8, b8 to h8; a7, b7 to h7; to a1, b1 to h1.
	 * Pieces must be names as "color type" like "black queen".
	 *
	 * @return array
	 */
	public function getPiecesByPosition();
}
