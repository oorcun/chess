<?php

namespace App\Value;

use App\TimeKeeper;
use App\Engine\RuleEngine;

class Stockfish implements PointValue
{
	/**
	 * Rule engine that checks rules.
	 *
	 * @var \App\Engine\RuleEngine
	 */
	protected $engine;

	/**
	 * Injects rule engine.
	 *
	 * @param \App\Engine\RuleEngine
	 */
	public function __construct(RuleEngine $engine)
	{
		$this->engine = $engine;
	}

	/**
	 * Calculates and returns point values sum.
	 * The higher the value, the better is white's point value.
	 *
	 * @return integer
	 */
	public function getValue()
	{
		TimeKeeper::start("Point value");

		$sum = array_reduce($this->engine->getPieces(), function($sum, $piece) {
			list($color, $type) = explode(" ", $piece);
			$coefficient = $color == "white" ? 1 : -1;
			switch ($type) {
				case "pawn":
					$value = $this->getPawnValue();
					break;
				case "knight":
					$value = $this->getKnightValue();
					break;
				case "bishop":
					$value = $this->getBishopValue();
					break;
				case "rook":
					$value = $this->getRookValue();
					break;
				case "queen":
					$value = $this->getQueenValue();
					break;
				default:
					$value = 0;
			}
			return $sum + $coefficient * $value;
		});

		TimeKeeper::stop("Point value");

		return $sum;
	}

	/**
	 * Gets the pawn value.
	 *
	 * @return integer
	 */
	public function getPawnValue()
	{
		return 204;
	}

	/**
	 * Gets the knight value.
	 *
	 * @return integer
	 */
	public function getKnightValue()
	{
		return 832;
	}

	/**
	 * Gets the bishop value.
	 *
	 * @return integer
	 */
	public function getBishopValue()
	{
		return 832;
	}

	/**
	 * Gets the rook value.
	 *
	 * @return integer
	 */
	public function getRookValue()
	{
		return 1285;
	}

	/**
	 * Gets the queen value.
	 *
	 * @return integer
	 */
	public function getQueenValue()
	{
		return 2560;
	}
}
