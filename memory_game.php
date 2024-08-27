<?php
require_once 'MemoryGame.php';

$playerId = 1; // Example player ID; in practice, this should be dynamic
$pairsCount = 6; // Example pairs count; should be retrieved from the session or game settings

$game = new MemoryGame($pairsCount);
$cards = $game->getCards(); // Get shuffled cards for display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Play</title>
    <link rel="stylesheet" href="style_memory.css">
</head>
<body>
    <div class="memory-container">
        <h1>Memory Game - Play</h1>
        <div id="memory-game-board" class="memory-game-board">
            <?php foreach ($cards as $card): ?>
                <div class="memory-card" data-id="<?php echo $card->getId(); ?>" data-value="<?php echo $card->getValue(); ?>">
                    <?php echo $card->getValue(); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="memory_script.js"></script>
</body>
</html>
