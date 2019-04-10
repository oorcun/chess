### What

This is a highly customizable chess application written with PHP. It is intended for those experimenters who wants to quickly start writing AI parts without dealing with trivial things that requires chess programs to run. It is not intended to create a strong AI.

### Structure

Application consists of four parts. One part is rule engine that checks game rules, makes or undoes moves, loads games, extracts game information, etc. Basically, everything about game except AI. Other three parts constitutes AI. Point values calculates piece relative strengths. Position evaluators calculates values of board positions. Search strategies determines which moves to search. You can use any combination of these four parts to create your own custom AI.

### How

To add your own extension; create a class that implements related interface, register it, and select it. For example; to implement a new strategy, create a class that implements App\Strategy\SearchStrategy interface, register that class in bootstrap\strategies.php, and select your implementation in config.php.
