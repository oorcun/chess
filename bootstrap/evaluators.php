<?php

return [

	/*
    | This evaluator simply sums (and substracts) point values.
    */

	"material" => App\Evaluator\MaterialEvaluator::class,


	/*
    | This is an extremely basic evaluation by using piece-square tables. This
    | evaluation method developed and tested by Tomasz Michniewski.
    */

	"simplified" => App\Evaluator\SimplifiedEvaluator::class,

];
