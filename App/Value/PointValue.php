<?php

namespace App\Value;

use App\Engine\RuleEngine;

interface PointValue
{
	/**
	 * Injects rule engine.
	 *
	 * @param \App\Engine\RuleEngine
	 */
	public function __construct(RuleEngine $engine);

	/**
	 * Calculates and returns point values sum.
	 * The higher the value, the better is white's point value.
	 *
	 * @return integer
	 */
	public function getValue();

	/**
	 * Gets the pawn value.
	 *
	 * @return integer
	 */
	public function getPawnValue();

	/**
	 * Gets the knight value.
	 *
	 * @return integer
	 */
	public function getKnightValue();

	/**
	 * Gets the bishop value.
	 *
	 * @return integer
	 */
	public function getBishopValue();

	/**
	 * Gets the rook value.
	 *
	 * @return integer
	 */
	public function getRookValue();

	/**
	 * Gets the queen value.
	 *
	 * @return integer
	 */
	public function getQueenValue();
}
