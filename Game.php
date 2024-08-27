<?php
// game.php
require_once 'Game.php';

$playerId = 1; // Example player ID; in practice, this should be dynamic
$pairsCount = 6; // Example pairs count; should be retrieved from the session or game settings

$game = new Game($pairsCount);
$game->startSession($playerId);

$cards = $game->getCards(); // Get shuffled cards for display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Play</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Memory Game - Play</h1>
        <div id="game-board" class="game-board">
            <?php foreach ($cards as $card): ?>
                <div class="card" data-id="<?php echo $card->getId(); ?>">
                    <?php echo $card->getValue(); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
