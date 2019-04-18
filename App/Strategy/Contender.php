<?php

namespace App\Strategy;

use App\Resolver;
use App\TimeKeeper;
use App\Engine\RuleEngine;
use App\Evaluator\PositionEvaluator;

class Contender implements SearchStrategy
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

		$this->turn = $this->engine->getTurn();
		$this->best["score"] = $this->turn == "white" ? PHP_INT_MIN : PHP_INT_MAX;
		$this->best["move"] = "";

		$this->minimax($this->depth);

		$this->engine->makeMove($this->best["move"]);

		TimeKeeper::stop("Searching");

		return $this->best["move"];
	}

	/**
	 * Determines best score and move for player.
	 *
	 * @param  integer $depth
	 * @return integer
	 */
	protected function minimax($depth)
	{
		$current["turn"] = $this->engine->getTurn();
		$current["score"] = $current["turn"] == "white" ? PHP_INT_MIN : PHP_INT_MAX;

		$moves = $depth > 0 ? $this->engine->getPossibleMoves() : $this->engine->getCapturedMoves();
		if ( ! $moves) {
			return $this->evaluator->evaluate();
		}

        foreach ($moves as $move) {
            $this->engine->makeMove($move);
            $current["score"] =
            	$current["turn"] == "white" ?
        		max($current["score"], $this->minimax($depth - 1)) :
        		min($current["score"], $this->minimax($depth - 1));
            if ($this->depth == $depth) {
            	$current["move"] = $move;
            	$this->updateBest($current);
            }
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
