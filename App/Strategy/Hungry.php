<?php

namespace App\Strategy;

use App\TimeKeeper;
use App\Engine\RuleEngine;
use App\Evaluator\PositionEvaluator;

class Hungry implements SearchStrategy
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
	 * Best things for computer, like best score and best move.
	 *
	 * @var array $best
	 */
	protected $best;

	/**
	 * Side to move as "white" or "black".
	 *
	 * @var string $turn
	 */
	protected $turn;

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

		$this->turn = $this->engine->getTurn();
		$this->best["score"] = $this->turn == "white" ? PHP_INT_MIN : PHP_INT_MAX;
		$this->best["move"] = "";

		foreach ($this->engine->getPossibleMoves() as $move) {
		 	$this->engine->makeMove($move);
		 	$current["score"] = $this->evaluator->evaluate();
		 	$current["move"] = $move;
		 	$this->updateBest($current);
		 	$this->engine->undoMove();
		}

		$this->engine->makeMove($this->best["move"]);

		TimeKeeper::stop("Thinking");

		return $this->best["move"];
	}

	/**
	 * Updates the best things for computer if it is necessary.
	 *
	 * @param array $current
	 */
	protected function updateBest($current)
	{
		if ($this->turn == "white" && $this->best["score"] < $current["score"] || $this->turn == "black" && $this->best["score"] > $current["score"]) {
			$this->best["score"] = $current["score"];
			$this->best["move"] = $current["move"];
		}
	}
}
