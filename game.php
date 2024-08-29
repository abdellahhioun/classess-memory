<?php
require_once 'db_connection.php';
require_once 'Card.php';
require_once 'GameSession.php';

session_start();

if (!isset($_SESSION['game_session'])) {
    $_SESSION['game_session'] = new GameSession($mysqli, 4);  // 4 pairs by default
}

$gameSession = $_SESSION['game_session'];

if (isset($_POST['flip'])) {
    $index = intval($_POST['flip']);
    $gameSession->flipCard($index);
    $gameSession->checkMatch();
}

if (isset($_POST['restart'])) {
    session_destroy();
    header('Location: game.php');
    exit;
}

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
