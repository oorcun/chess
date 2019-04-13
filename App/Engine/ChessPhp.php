<?php

namespace App\Engine;

use App\GameState;
use App\TimeKeeper;
use App\GlobalCache;
use Ryanhs\Chess\Chess;

class ChessPhp implements RuleEngine
{
	/**
	 * Rule engine implementation.
	 *
	 * @var \Ryanhs\Chess\Chess
	 */
	protected $engine;

	/**
	 * Mapping of colors from underlying implementation to this class.
	 *
	 * @var array
	 */
	protected const COLOR_MAP = [
		"w" => "white",
		"b" => "black"
	];

	/**
	 * Mapping of pieces from underlying implementation to this class.
	 *
	 * @var array
	 */
	protected const PIECE_MAP = [
		"p" => "pawn",
		"b" => "bishop",
		"n" => "knight",
		"r" => "rook",
		"q" => "queen",
		"k" => "king"
	];

	/**
	 * Square names consistent with piece-square table.
	 *
	 * @var array
	 */
	protected const SQUARE_NAMES = [
		"a8","b8","c8","d8","e8","f8","g8","h8",
		"a7","b7","c7","d7","e7","f7","g7","h7",
		"a6","b6","c6","d6","e6","f6","g6","h6",
		"a5","b5","c5","d5","e5","f5","g5","h5",
		"a4","b4","c4","d4","e4","f4","g4","h4",
		"a3","b3","c3","d3","e3","f3","g3","h3",
		"a2","b2","c2","d2","e2","f2","g2","h2",
		"a1","b1","c1","d1","e1","f1","g1","h1",
	];

	/**
	 * Constructs underlying class and game.
	 * Since game construction takes time towards the end, we put the game in the cache.
	 */
	public function __construct()
	{
		if (GlobalCache::has("game")) {
			$this->engine = GlobalCache::get("game");
		} else {
			TimeKeeper::start("Construction");
			$this->engine = $this->loadGame();
			TimeKeeper::stop("Construction");
			GlobalCache::put("game", $this->engine);
		}
	}

	/**
	 * Gets all possible moves.
	 *
	 * @return array
	 */
	public function getPossibleMoves()
	{
		return $this->engine->moves();
	}

	/**
	 * Makes a move.
	 *
	 * @param string
	 */
	public function makeMove($move)
	{
		$this->engine->move($move);
	}

	/**
	 * Take back last move.
	 *
	 * @param string
	 */
	public function undoMove()
	{
		$this->engine->undo();
	}

	/**
	 * Gets pieces on board as "color type" like "black queen".
	 *
	 * @param array
	 */
	public function getPieces()
	{
		foreach ($this->engine->export()->board as $piece) {
			if ( ! $piece) {
				continue;
			}
			$pieces[] = self::COLOR_MAP[$piece["color"]] . " " . self::PIECE_MAP[$piece["type"]];
		}

		return $pieces;
	}

	/**
	 * Gets side to move as "white" or "black".
	 *
	 * @param string
	 */
	public function getTurn()
	{
		return $this->engine->turn() == "w" ? "white" : "black";
	}

	/**
	 * Gets the game winner as "white" or "black".
	 *
	 * @return string|null
	 */
	public function getWinner()
	{
		if ( ! $this->engine->inCheckmate()) {
			return null;
		}

		return $this->engine->turn() == "w" ? "black" : "white";
	}

	/**
	 * Checks if game is over.
	 *
	 * @return boolean
	 */
	public function gameOver()
	{
		return $this->engine->gameOver();
	}

	/**
	 * Gets the pieces by position.
	 * For adaptability to piece-square tables the returned array must order pieces by a8, b8 to h8; a7, b7 to h7; to a1, b1 to h1.
	 * Pieces must be names as "color type" like "black queen".
	 *
	 * @return array
	 */
	public function getPiecesByPosition()
	{
		return array_map(function ($name) {
			$piece = $this->engine->get($name);
			if ($piece) {
				$piece = self::COLOR_MAP[$piece["color"]] . " " . self::PIECE_MAP[$piece["type"]];
			}
			return $piece;
		}, self::SQUARE_NAMES);
	}

	/**
	 * Loads and returns the game.
	 *
	 * @throws \Exception
	 * @return \Ryanhs\Chess\Chess
	 */
	protected function loadGame()
	{
		try {
			if (GameState::boardSet()) {
				return new Chess(GameState::getFen());
			}
			return (new Chess())->loadPgn(GameState::getPgn());
		} catch (\Exception $expression) {
			throw new \Exception("Unable to load game.");
		}
	}
}
