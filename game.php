<?php
require 'db_connection.php';
require 'Player.php';
require 'GameSession.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cards = []; // Ensure $cards is defined

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $playerName = $_POST['player_name'];
    $numPairs = $_POST['pairs'];

    // Initialize Player
    $player = new Player($playerName, $mysqli);

    // Start a new Game Session
    $gameSession = new GameSession($player->getId(), $mysqli);
    $gameSession->selectAndShuffleCards($numPairs);
    $cards = $gameSession->getCards();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Memory Game</h1>
        <div class="game-board">
            <?php if (!empty($cards)): ?>
                <?php foreach ($cards as $card): ?>
                    <div class="card" data-card-value="<?= htmlspecialchars($card->getValue()); ?>">
                        <div class="card-front"></div>
                        <div class="card-back">
                            <i class="fas <?= htmlspecialchars($card->getIcon()); ?>"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No cards available. Please start a new game.</p>
            <?php endif; ?>
        </div>
        <button onclick="location.href='./index.php'">Restart</button>
    </div>
    <script src="./scripts.js"></script>
</body>
</html>
