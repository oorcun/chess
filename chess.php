<?php

use App\Resolver;
use App\Response;
use App\GameState;
use App\TimeKeeper;

require __DIR__ . "/vendor/autoload.php";

try {

	TimeKeeper::start("Total");

	GameState::set();

	Resolver::setConfiguration();
	Resolver::setStrategy();
	Resolver::setEngine();
	Resolver::setPointValue();
	Resolver::setEvaluator();

	Response::add([
		"move" => Resolver::getAi()->makeMove()
	]);

	TimeKeeper::stop("Total");

	Response::addTimes();
	Response::send();

} catch (\Exception $e) {
	echo $e->getMessage();
}

