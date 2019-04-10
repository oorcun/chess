<?php

namespace App\Evaluator;

use App\TimeKeeper;
use App\Value\PointValue;
use App\Engine\RuleEngine;

class MaterialEvaluator implements PositionEvaluator
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
				return 0;
			}
			return $this->engine->getWinner() == "white" ? PHP_INT_MAX : PHP_INT_MIN;
		}

		$value = $this->value->getValue();

		TimeKeeper::stop("Evaluation");

		return $value;
	}
}
