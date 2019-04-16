<?php

return [

	/*
    | These values are from Larry Kaufman at
    | http://www.talkchess.com/forum3/viewtopic.php?topic_view=threads&p=487051
    */

	"kaufman" => App\Value\LarryKaufman::class,


	/*
    | Stockfish chess engine's middle game values at
    | https://github.com/daylen/stockfish-mac/blob/master/Chess/value.h
    */

	"stockfish" => App\Value\Stockfish::class

];
