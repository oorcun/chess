<?php

namespace App\Strategy;

use App\TimeKeeper;
use App\Engine\RuleEngine;
use App\Evaluator\PositionEvaluator;

class Random implements SearchStrategy
{
	/**
	 * Rule engine that checks chess rules.
	 *
	 * @var \App\Engine\RuleEngine $engine
	 */
	protected $engine;

	/**
	 * Evaluator that evaluates board positions.
	 *
	 * @var \App\Evaluator\Evaluator $evaluator
	 */
	protected $evaluator;

	/**
	 * Injects rule engine and board evaluator implementations.
	 *
	 * @param \App\Engine\RuleEngine $engine
	 * @param \App\Evaluator\PositionEvaluator $evaluator
	 */
	public function __construct(RuleEngine $engine, PositionEvaluator $evaluator)
	{
		$this->engine = $engine;
		$this->evaluator = $evaluator;
	}

	/**
	 * Makes a move.
	 * Must return the move in SAN.
	 *
	 * @return string
	 */
	public function makeMove()
	{
		TimeKeeper::start("Thinking");

		$moves = $this->engine->getPossibleMoves();
		$move = $moves[array_rand($moves)];

		$this->engine->makeMove($move);

		TimeKeeper::stop("Thinking");

		return $move;
	}
}
