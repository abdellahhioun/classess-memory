<?php
// get_rankings.php
require_once 'Game.php';

$game = new Game(0); // Initialize game without pairs count
$rankings = $game->getTopScores();

header('Content-Type: application/json');
echo json_encode($rankings);
?>
