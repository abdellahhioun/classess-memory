<?php
require_once 'MemoryGame.php';

$game = new MemoryGame(0); // Initialize game without pairs count
$rankings = $game->getTopScores();

header('Content-Type: application/json');
echo json_encode($rankings);
?>
