<?php

return [

	/*
    | This strategy makes a random move from all possible moves. Since it is a
    | fast decider, it is perfect for testing.
    */

	"random" => App\Strategy\Random::class,


	/*
    | This strategy simply make a move, evaluate board and then undo move; and
    | repeat this process for all possible moves. It makes a move that gives
    | the best board score for itself. This will make a capture oriented AI if
    | board evaluation is materialistic.
    */

	"hungry" => App\Strategy\Hungry::class,


    /*
    | This is a Minimax algorithm. This strategy basically chooses the best
    | move, taking into account its and opponent's best moves in the search
    | tree. It considers all possible moves for both sides, and tries to come
    | up with best line for itself.
    */

    "minimax" => App\Strategy\Minimax::class,


    /*
    | This is a Minimax algorithm with Alpha-Beta pruning. This strategy
    | eliminates the need to search large portions of the game tree by applying
    | lower and upper bounds to evaluations without overlooking a better move.
    */

    "alphabeta" => App\Strategy\AlphaBeta::class,


    /*
    | This is a minimax algorithm that also searches all moves that makes a
    | capture.
    */

    "contender" => App\Strategy\Contender::class,

];
