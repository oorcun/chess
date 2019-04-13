$(document).ready(function() {

	// "white" or "black".
	var player;

	// The game contains game information.
	var game;

	// The board contains board information.
	var board;

	// Contains who (if any) resigned from game, "white" or "black".
	var resigned;

	// True if the board set up by FEN.
	var board_set;

	// Write last move to screen
	var write_move = function() {
		var history = game.history();
		var turn = game.turn();
		$("#history").append((turn == "b" ? Math.ceil(history.length / 2) : "") + " " + history[history.length - 1] + (turn == "b" ? "" : "<br>"));
	};

	// Callback for a random move for testing.
	var make_random_move = function() {
		var moves = game.moves();
		game.move(moves[Math.floor(Math.random() * moves.length)]);
		board.position(game.fen());
		write_move();
		update_status();
	};

	// Write response to screen for debugging.
	var debug = function(data) {
		if (typeof data.debug != "undefined") {
            $("#debug").html(data.debug);
		}
	};

	// Initialize the game.
	var initialize_game = function() {
		board.start(false);
		game = new Chess();
		board.orientation(player);
		$("#history").empty();
		$("#status").text("Playing");
		$("#resign").prop("disabled", false);
		resigned = false;
		board_set = false;
	};

	// Set up the game.
	var set_game = function(fen) {
		board.position(fen, false);
		game = new Chess(fen);
		board.orientation(player);
		$("#history").empty();
		$("#status").text("Playing");
		$("#resign").prop("disabled", false);
		resigned = false;
		board_set = true;
	};

	// Update the status section.
	var update_status = function() {
		if (game.game_over()) {
			if (game.in_checkmate()) {
				game.turn() == "b" ? $("#status").html("Checkmate<br>1 - 0") : $("#status").html("Checkmate<br>0 - 1");
			} else if (game.in_stalemate()) {
				$("#status").html("Stalemate<br>&#189; - &#189;");
			} else if (game.in_threefold_repetition()) {
				$("#status").html("Threefold repetition<br>&#189; - &#189;");
			} else if (game.insufficient_material()) {
				$("#status").html("Insufficient material<br>&#189; - &#189;");
			} else if (game.in_draw()) {
				$("#status").html("50-move rule<br>&#189; - &#189;"); // Only drawing alternative that is left is 50-move rule.
			}
			$("#resign").prop("disabled", true);
		}
		else if (resigned) {
			resigned == "white" ? $("#status").html("White resigned<br>0 - 1") : $("#status").html("Black resigned<br>1 - 0");
		}
	};

	// Get a move from PHP and make that move.
	var make_move = function() {
		$("#spinner").show();
		$.ajax({
            url: "chess.php",
            method: "POST",
            data: {
            	fen: game.fen(),
            	pgn: game.pgn(),
            	history: game.history(),
            	board_set: board_set
            },
            dataType: "json",
            success: function(data) {
            	var html = "";
            	for (time in data.time) {
            		html += time + ": "  + data.time[time].seconds.toFixed(3) + " seconds " + data.time[time].times + " times" + "<br>";
            	}
                $("#time").html(html);
                if ( ! data.move) {
                	resigned = player == "white" ? "black" : "white"; // If computer can't find move, then it is certain loss.
                } else {
                	game.move(data.move);
					board.position(game.fen());
					write_move();
                }
				update_status();
				$("#spinner").hide();
				debug(data);
            },
            error: function(data) {
                alert("Error: " + data.responseText);
            }
        });
	};

	// Callback for when player starting to drag a piece.
	var onDragStart = function(source, piece, position, orientation) {
		var player_piece = player.charAt(0); // Piece is "w" or "b".
		var piece_cannot_pickable =
			game.game_over() || // Do not pick up piece if game is over.
			game.turn() != player_piece || // Do not pick up piece if it is not player's turn.
			piece.charAt(0) != player_piece || // Do not pick up opponent's piece.
			resigned; // Do not pick up piece if someone resigned.
		if (piece_cannot_pickable) {
			return false;
		}
	};

	// Callback for when player drops a piece.
	var onDrop = function(source, target, piece) {
		var row = target.charAt(1);
		if (piece.charAt(1) == "P" && (row == "1" || row == "8")) { // Check if move is a promotion.
			var promoted_piece = prompt('Select piece, "q" for queen, "r" for rook, "b" for bishop, "n" for knight:', "q");
			if (["q", "r", "b", "n"].indexOf(promoted_piece) == -1) { // If no piece selected..
				return "snapback"; // Return the piece to its original location.
			}
		}
		var move = game.move({ // Attempt a move.
			from: source,
			to: target,
			promotion: promoted_piece
		});
		if ( ! move) return "snapback"; // If move is illegal return the piece to its original position.
		write_move();
		update_status();
		if ( ! game.game_over()) {
			make_move();
			// setTimeout(make_random_move, 100); // We need to wait for move animation to finish.
			return true;
		}
	};

	// Configuration for chess board.
	var config = {
	 	pieceTheme: "chessboardjs-0.3.0/img/chesspieces/wikipedia/{piece}.png",
	 	draggable: true,
	 	onDragStart: onDragStart,
	 	onDrop: onDrop
	};

	// Get the board.
	board = ChessBoard("board", config);

	// Start the game as white.
	$("#start-as-white").on("click", function() {
		player = "white";
		initialize_game();
	});

	// Start the game as black.
	$("#start-as-black").on("click", function() {
		player = "black";
		initialize_game();
		setTimeout(make_move, 200); // Wait for board to draw.
	});

	// Resign from game.
	$("#resign").on("click", function() {
		$(this).prop("disabled", true);
		resigned = player;
		update_status();
	});

	// Set game and play as white.
	$("#play-as-white").on("click", function() {
		player = "white";
		set_game($("#fen").val());
		if (game.turn() == "b") {
			setTimeout(make_move, 200); // Wait for board to draw.
		}
	});

	// Set game and play as black.
	$("#play-as-black").on("click", function() {
		player = "black";
		set_game($("#fen").val());
		if (game.turn() == "w") {
			setTimeout(make_move, 200); // Wait for board to draw.
		}
	});

});
