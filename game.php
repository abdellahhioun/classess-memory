<?php
require_once 'db_connection.php';
require_once 'Card.php';
require_once 'GameSession.php';

session_start();

// Handle restarting the game
if (isset($_POST['restart'])) {
    session_destroy();
    session_start(); // Start a new session
    $_SESSION['game_session'] = new GameSession($mysqli, 6); // Initialize with 6 pairs
    header('Location: game.php');
    exit;
}

// Initialize the game session if not already set
if (!isset($_SESSION['game_session'])) {
    $_SESSION['game_session'] = new GameSession($mysqli, 6); // 6 pairs for 12 cards
}

$gameSession = $_SESSION['game_session'];

if (isset($_POST['flip'])) {
    $index = intval($_POST['flip']);
    $gameSession->flipCard($index);
    $gameSession->checkMatch();
}

// Update the session variable with the latest game session state
$_SESSION['game_session'] = $gameSession;

$cards = $gameSession->getCards();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="style_memory.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@vscode/codicons@0.0.26/dist/codicon.css">
</head>
<body>
    <div class="memory-container">
        <h1>Memory Game</h1>
        <form method="POST">
            <button type="submit" name="restart">Restart</button>
        </form>
        <div class="memory-game-board">
            <?php foreach ($cards as $index => $card): ?>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="flip" value="<?= $index ?>" class="memory-card <?= $card->isFlipped() ? 'flipped' : '' ?> <?= $card->isMatched() ? 'matched' : '' ?>">
                        <?= $card->isFlipped() ? '<i class="codicon ' . htmlspecialchars($card->getIcon()) . '"></i>' : '?' ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
