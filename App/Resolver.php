<?php

namespace App;

class Resolver
{
	/**
	 * Selected strategy implementation.
	 *
	 * @var string
	 */
	protected static $strategy;

	/**
	 * Selected rule engine implementation.
	 *
	 * @var string
	 */
	protected static $engine;

	/**
	 * Selected point value implementation.
	 *
	 * @var string
	 */
	protected static $value;

	/**
	 * Selected evaluator implementation.
	 *
	 * @var string
	 */
	protected static $evaluator;

	/**
	 * Configuration options.
	 *
	 * @var array
	 */
	protected static $config;

	/**
	 * Sets the strategy.
	 */
	public static function setStrategy()
	{
		self::$strategy = (include "bootstrap/strategies.php")[self::getConfiguration()["strategy"]["name"]];
	}

	/**
	 * Gets the strategy.
	 *
	 * @return \App\Strategy\SearchStrategy
	 */
	public static function getStrategy()
	{
		return new self::$strategy(self::getEngine(), self::getEvaluator());
	}

	/**
	 * Sets the engine.
	 */
	public static function setEngine()
	{
		self::$engine = (include "bootstrap/engines.php")[self::getConfiguration()["engine"]];
	}

	/**
	 * Gets the engine.
	 *
	 * @return \App\Engine\RuleEngine
	 */
	public static function getEngine()
	{
		return new self::$engine;
	}

	/**
	 * Sets the point value.
	 */
	public static function setPointValue()
	{
		self::$value = (include "bootstrap/values.php")[self::getConfiguration()["pointValue"]];
	}

	/**
	 * Gets the point value.
	 *
	 * @return \App\Value\PointValue
	 */
	public static function getPointValue()
	{
		return new self::$value(self::getEngine());
	}

	/**
	 * Sets the evaluator.
	 */
	public static function setEvaluator()
	{
		self::$evaluator = (include "bootstrap/evaluators.php")[self::getConfiguration()["evaluator"]];
	}

	/**
	 * Gets the evaluator.
	 *
	 * @return \App\Evaluator\Evaluator
	 */
	public static function getEvaluator()
	{
		return new self::$evaluator(self::getEngine(), self::getPointValue());
	}

	/**
	 * Sets the configuration options.
	 */
	public static function setConfiguration()
	{
		self::$config = include "config.php";
	}

	/**
	 * Gets the configuration options.
	 *
	 * @return array
	 */
	public static function getConfiguration()
	{
		return self::$config;
	}

	/**
	 * Gets the AI.
	 * This is an alias of getStrategy.
	 *
	 * @return \App\Strategy\SearchStrategy
	 */
	public static function getAi()
	{
		return new self::$strategy(self::getEngine(), self::getEvaluator());
	}

	/**
	 * Gets the strategy's configuration option.
	 *
	 * @param  integer|string $config
	 * @return mixed
	 */
	public static function getStrategyConfiguration($config)
	{
		return self::getConfiguration()["strategy"][$config];
	}
}
