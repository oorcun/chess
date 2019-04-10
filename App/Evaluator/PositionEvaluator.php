<?php

namespace App\Evaluator;

use App\Value\PointValue;
use App\Engine\RuleEngine;

interface PositionEvaluator
{
	/**
	 * Injects rule engine and point value implementations.
	 *
	 * @param \App\Engine\RuleEngine $engine
	 * @param \App\Value\PointValue $value
	 */
	public function __construct(RuleEngine $engine, PointValue $value);

	/**
	 * Evaluates board position.
	 * The higher the value, the better is white position.
	 *
	 * @return integer
	 */
	public function evaluate();
}
