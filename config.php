<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rule Engine
    |--------------------------------------------------------------------------
    |
    | Rule engines checks game rules, makes or undoes moves, loads games,
    | extracts game information, etc. Basically, everything about game except
    | AI. All available rule engine implementations are in engines.php.
    |
    */

	"engine" => "Chess.php",


	/*
    |--------------------------------------------------------------------------
    | Search Strategy
    |--------------------------------------------------------------------------
    |
    | Search strategy for AI. You can also give its depth as a ply (half move).
    | If the strategy does not use depth, this value is ignored. All available
    | search strategy implementations are in strategies.php.
    |
    */

	"strategy" => [
		"name" => "alphabeta",
		"depth" => 2
	],


	/*
    |--------------------------------------------------------------------------
    | Point Value
    |--------------------------------------------------------------------------
    |
    | Point value for AI. This is the piece relative strength in potential
    | exchanges based on human experience and learning. All available point
    | value implementations are in values.php.
    |
    */

	"pointValue" => "stockfish",


	/*
    |--------------------------------------------------------------------------
    | Position Evaluators
    |--------------------------------------------------------------------------
    |
    | Position evaluator for AI. This is the relative value of board position.
    | This could be seen as chances of winning. All available position
    | evaluation implementations are in values.php.
    |
    */

	"evaluator" => "simplified"

];
