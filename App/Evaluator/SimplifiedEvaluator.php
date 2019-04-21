<?php

namespace App\Evaluator;

use App\TimeKeeper;
use App\Value\PointValue;
use App\Engine\RuleEngine;

class SimplifiedEvaluator implements PositionEvaluator
{
	/**
	 * Rule engine that checks rules.
	 *
	 * @var \App\Engine\RuleEngine
	 */
	protected $engine;

	/**
	 * Point value for pieces.
	 *
	 * @var \App\Value\PointValue
	 */
	protected $value;

	/**
	 * Piece-square table for white pawn.
	 *
	 * @var array
	 */
	protected const WHITE_PAWN = [
		 0,  0,  0,  0,  0,  0,  0,  0,
		50, 50, 50, 50, 50, 50, 50, 50,
		10, 10, 20, 30, 30, 20, 10, 10,
		 5,  5, 10, 25, 25, 10,  5,  5,
		 0,  0,  0, 20, 20,  0,  0,  0,
		 5, -5,-10,  0,  0,-10, -5,  5,
		 5, 10, 10,-20,-20, 10, 10,  5,
		 0,  0,  0,  0,  0,  0,  0,  0,
	];

	/**
	 * Piece-square table for black pawn.
	 *
	 * @var array
	 */
	protected const BLACK_PAWN = [
		  0,  0,  0,  0,  0,  0,  0,  0,
		 -5,-10,-10, 20, 20,-10,-10, -5,
		 -5,  5, 10,  0,  0, 10,  5, -5,
		  0,  0,  0,-20,-20,  0,  0,  0,
		 -5, -5,-10,-25,-25,-10, -5, -5,
		-10,-10,-20,-30,-30,-20,-10,-10,
		-50,-50,-50,-50,-50,-50,-50,-50,
		  0,  0,  0,  0,  0,  0,  0,  0,
	];

	/**
	 * Piece-square table for white knight.
	 *
	 * @var array
	 */
	protected const WHITE_KNIGHT = [
		-50,-40,-30,-30,-30,-30,-40,-50,
		-40,-20,  0,  0,  0,  0,-20,-40,
		-30,  0, 10, 15, 15, 10,  0,-30,
		-30,  5, 15, 20, 20, 15,  5,-30,
		-30,  0, 15, 20, 20, 15,  0,-30,
		-30,  5, 10, 15, 15, 10,  5,-30,
		-40,-20,  0,  5,  5,  0,-20,-40,
		-50,-40,-30,-30,-30,-30,-40,-50,
	];

	/**
	 * Piece-square table for black knight.
	 *
	 * @var array
	 */
	protected const BLACK_KNIGHT = [
		50, 40, 30, 30, 30, 30, 40, 50,
		40, 20,  0, -5, -5,  0, 20, 40,
		30, -5,-10,-15,-15,-10, -5, 30,
		30,  0,-15,-20,-20,-15,  0, 30,
		30, -5,-15,-20,-20,-15, -5, 30,
		30,  0,-10,-15,-15,-10,  0, 30,
		40, 20,  0,  0,  0,  0, 20, 40,
		50, 40, 30, 30, 30, 30, 40, 50,
	];

	/**
	 * Piece-square table for white bishop.
	 *
	 * @var array
	 */
	protected const WHITE_BISHOP = [
		-20,-10,-10,-10,-10,-10,-10,-20,
		-10,  0,  0,  0,  0,  0,  0,-10,
		-10,  0,  5, 10, 10,  5,  0,-10,
		-10,  5,  5, 10, 10,  5,  5,-10,
		-10,  0, 10, 10, 10, 10,  0,-10,
		-10, 10, 10, 10, 10, 10, 10,-10,
		-10,  5,  0,  0,  0,  0,  5,-10,
		-20,-10,-10,-10,-10,-10,-10,-20,
	];

	/**
	 * Piece-square table for black bishop.
	 *
	 * @var array
	 */
	protected const BLACK_BISHOP = [
		20, 10, 10, 10, 10, 10, 10, 20,
		10, -5,  0,  0,  0,  0, -5, 10,
		10,-10,-10,-10,-10,-10,-10, 10,
		10,  0,-10,-10,-10,-10,  0, 10,
		10, -5, -5,-10,-10, -5, -5, 10,
		10,  0, -5,-10,-10, -5,  0, 10,
		10,  0,  0,  0,  0,  0,  0, 10,
		20, 10, 10, 10, 10, 10, 10, 20,
	];

	/**
	 * Piece-square table for white rook.
	 *
	 * @var array
	 */
	protected const WHITE_ROOK = [
		 0,  0,  0,  0,  0,  0,  0,  0,
		 5, 10, 10, 10, 10, 10, 10,  5,
		-5,  0,  0,  0,  0,  0,  0, -5,
		-5,  0,  0,  0,  0,  0,  0, -5,
		-5,  0,  0,  0,  0,  0,  0, -5,
		-5,  0,  0,  0,  0,  0,  0, -5,
		-5,  0,  0,  0,  0,  0,  0, -5,
		 0,  0,  0,  5,  5,  0,  0,  0,
	];

	/**
	 * Piece-square table for black rook.
	 *
	 * @var array
	 */
	protected const BLACK_ROOK = [
		 0,  0,  0, -5, -5,  0,  0,  0,
		 5,  0,  0,  0,  0,  0,  0,  5,
		 5,  0,  0,  0,  0,  0,  0,  5,
		 5,  0,  0,  0,  0,  0,  0,  5,
		 5,  0,  0,  0,  0,  0,  0,  5,
		 5,  0,  0,  0,  0,  0,  0,  5,
		-5,-10,-10,-10,-10,-10,-10, -5,
		 0,  0,  0,  0,  0,  0,  0,  0,
	];

	/**
	 * Piece-square table for white queen.
	 *
	 * @var array
	 */
	protected const WHITE_QUEEN = [
		-20,-10,-10, -5, -5,-10,-10,-20,
		-10,  0,  0,  0,  0,  0,  0,-10,
		-10,  0,  5,  5,  5,  5,  0,-10,
		 -5,  0,  5,  5,  5,  5,  0, -5,
		  0,  0,  5,  5,  5,  5,  0, -5,
		-10,  5,  5,  5,  5,  5,  0,-10,
		-10,  0,  5,  0,  0,  0,  0,-10,
		-20,-10,-10, -5, -5,-10,-10,-20,
	];

	/**
	 * Piece-square table for black queen.
	 *
	 * @var array
	 */
	protected const BLACK_QUEEN = [
		20, 10, 10,  5,  5, 10, 10, 20,
		10,  0, -5,  0,  0,  0,  0, 10,
		10, -5, -5, -5, -5, -5,  0, 10,
		 0,  0, -5, -5, -5, -5,  0,  5,
		 5,  0, -5, -5, -5, -5,  0,  5,
		10,  0, -5, -5, -5, -5,  0, 10,
		10,  0,  0,  0,  0,  0,  0, 10,
		20, 10, 10,  5,  5, 10, 10, 20,
	];

	/**
	 * Piece-square table for white king.
	 *
	 * @var array
	 */
	protected const WHITE_KING = [
		-30,-40,-40,-50,-50,-40,-40,-30,
		-30,-40,-40,-50,-50,-40,-40,-30,
		-30,-40,-40,-50,-50,-40,-40,-30,
		-30,-40,-40,-50,-50,-40,-40,-30,
		-20,-30,-30,-40,-40,-30,-30,-20,
		-10,-20,-20,-20,-20,-20,-20,-10,
		 20, 20,  0,  0,  0,  0, 20, 20,
		 20, 30, 10,  0,  0, 10, 30, 20,
	];

	/**
	 * Piece-square table for black king.
	 *
	 * @var array
	 */
	protected const BLACK_KING = [
		-20,-30,-10,  0,  0,-10,-30,-20,
		-20,-20,  0,  0,  0,  0,-20,-20,
		 10, 20, 20, 20, 20, 20, 20, 10,
		 20, 30, 30, 40, 40, 30, 30, 20,
		 30, 40, 40, 50, 50, 40, 40, 30,
		 30, 40, 40, 50, 50, 40, 40, 30,
		 30, 40, 40, 50, 50, 40, 40, 30,
		 30, 40, 40, 50, 50, 40, 40, 30,
	];

	/**
	 * Injects rule engine and point value implementations.
	 *
	 * @param \App\Engine\RuleEngine $engine
	 * @param \App\Value\PointValue $value
	 */
	public function __construct(RuleEngine $engine, PointValue $value)
	{
		$this->engine = $engine;
		$this->value = $value;
	}

	/**
	 * Evaluates board position.
	 * The higher the value, the better is white position.
	 *
	 * @return integer
	 */
	public function evaluate()
	{
		TimeKeeper::start("Evaluation");

		if ($this->engine->gameOver()) {
			if ( ! $this->engine->getWinner()) {
				TimeKeeper::stop("Evaluation");
				return 0;
			}
			TimeKeeper::stop("Evaluation");
			return $this->engine->getWinner() == "white" ? PHP_INT_MAX : PHP_INT_MIN;
		}

		$value = $this->value->getValue();

		foreach ($this->engine->getPiecesByPosition() as $key => $piece) {
			switch ($piece) {
				case "white pawn":
					$value += self::WHITE_PAWN[$key];
					break;
				case "black pawn":
					$value += self::BLACK_PAWN[$key];
					break;
				case "white knight":
					$value += self::WHITE_KNIGHT[$key];
					break;
				case "black knight":
					$value += self::BLACK_KNIGHT[$key];
					break;
				case "white bishop":
					$value += self::WHITE_BISHOP[$key];
					break;
				case "black bishop":
					$value += self::BLACK_BISHOP[$key];
					break;
				case "white rook":
					$value += self::WHITE_ROOK[$key];
					break;
				case "black rook":
					$value += self::BLACK_ROOK[$key];
					break;
				case "white queen":
					$value += self::WHITE_QUEEN[$key];
					break;
				case "black queen":
					$value += self::BLACK_QUEEN[$key];
					break;
				case "white king":
					$value += self::WHITE_KING[$key];
					break;
				case "black king":
					$value += self::BLACK_KING[$key];
					break;
			}
		}

		TimeKeeper::stop("Evaluation");

		return $value;
	}
}
