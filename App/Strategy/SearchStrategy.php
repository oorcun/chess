<?php

namespace App\Strategy;

use App\Engine\RuleEngine;
use App\Evaluator\PositionEvaluator;

interface SearchStrategy
{
	/**
	 * Injects rule engine and board evaluator implementations.
	 *
	 * @param \App\Engine\RuleEngine $engine
	 * @param \App\Evaluator\PositionEvaluator $evaluator
	 */
	public function __construct(RuleEngine $engine, PositionEvaluator $evaluator);

	/**
	 * Makes a move.
	 * Must return the move in SAN.
	 *
	 * @return string
	 */
	public function makeMove();
}
