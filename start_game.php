<?php
// start_game.php
require_once 'Game.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pairsCount = intval($_POST['pairs']);
    if ($pairsCount < 3 || $pairsCount > 12) {
        die("Number of pairs must be between 3 and 12.");
    }

    $playerId = 1; // Example player ID; in practice, this should be dynamic
    $game = new Game($pairsCount);
    $game->startSession($playerId);

    header('Location: game.php');
    exit();
}
?>
