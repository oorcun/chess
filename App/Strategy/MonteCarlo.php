<?php

namespace App\Strategy;

use App\Resolver;
use App\TimeKeeper;
use App\Engine\RuleEngine;
use App\Evaluator\PositionEvaluator;

class MonteCarlo implements SearchStrategy
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
	 * Algorithm depth as ply.
	 *
	 * @var string $depth
	 */
	protected $depth;

	/**
	 * Thinking time of algorithm.
	 *
	 * @var string $time
	 */
	protected $time;

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
		TimeKeeper::start("Searching");

		$this->depth = Resolver::getStrategyConfiguration("depth");
		if ( ! is_int($this->depth) || $this->depth <= 0) {
			throw new \Exception("Corrupt depth value.");
		}

		$this->time = Resolver::getStrategyConfiguration("time");
		if ( ! is_int($this->time) || $this->time <= 0) {
			throw new \Exception("Corrupt time value.");
		}

		$this->turn = $this->engine->getTurn();
		$this->best["score"] = $this->turn == "white" ? PHP_INT_MIN : PHP_INT_MAX;
		$this->best["move"] = "";

		$this->chooseMove(0);

		$this->engine->makeMove($this->best["move"]);

		TimeKeeper::stop("Searching");

		return $this->best["move"];
	}

	/**
	 * Chooses a random move and evaluate board position for that move.
	 *
	 * @param  integer $depth
	 * @return integer
	 */
	protected function chooseMove($depth)
	{
		if ($this->depth == $depth) {
			$evaluation = $this->evaluator->evaluate();
			$this->engine->undoMove();
			return $evaluation;
		}

		$moves = $this->engine->getPossibleMoves();
		if ( ! $moves) {
			$evaluation = $this->evaluator->evaluate();
			$this->engine->undoMove();
			return $evaluation;
		}

    	$current["move"] = $moves[array_rand($moves)];
		$this->engine->makeMove($current["move"]);

		$current["score"] = $this->chooseMove($depth + 1);

    	$this->engine->undoMove();

        while ( ! $depth) {
    		$this->updateBest($current);

			if (TimeKeeper::getElapsedTime("Searching") > $this->time) {
				break;
			}

        	$current["move"] = $moves[array_rand($moves)];
			$this->engine->makeMove($current["move"]);

			$current["score"] = $this->chooseMove($depth + 1);

			$this->engine->undoMove();
        }

        return $current["score"];
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
