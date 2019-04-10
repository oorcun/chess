<?php

return [

	/*
    | This rule engine implements Chess.php available at
    | https://github.com/ryanhs/chess.php. Constructing a game with this engine
    | will take more time towards end of the game where move history grows up.
    */

	"Chess.php" => App\Engine\ChessPhp::class,

];
