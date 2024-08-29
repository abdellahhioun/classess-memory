<?php
require_once 'db_connection.php';
require_once 'Card.php';
require_once 'GameSession.php';
require_once 'Player.php';

session_start();

// Handle restarting the game
if (isset($_POST['restart'])) {
    session_destroy();
    session_start();
    $numPairs = isset($_POST['numPairs']) ? intval($_POST['numPairs']) : 6;
    $_SESSION['game_session'] = new GameSession($mysqli, $numPairs);
    header('Location: game.php');
    exit;
}

// Handle resetting the game (new shuffle, same difficulty)
if (isset($_POST['reset'])) {
    $gameSession = $_SESSION['game_session'];
    $numPairs = count($gameSession->getCards()) / 2; // Determine the number of pairs
    $_SESSION['game_session'] = new GameSession($mysqli, $numPairs);
    header('Location: game.php');
    exit;
}

if (!isset($_SESSION['game_session'])) {
    $numPairs = 6; // Default to 6 pairs
    $_SESSION['game_session'] = new GameSession($mysqli, $numPairs);
}

$gameSession = $_SESSION['game_session'];

if (isset($_POST['flip'])) {
    $index = intval($_POST['flip']);
    $gameSession->flipCard($index);
    $gameSession->checkMatch();
    $_SESSION['game_session'] = $gameSession;
}

// Handle game completion
if ($gameSession->isCompleted()) {
    $playerName = 'Player1'; // Replace with actual player logic
    $player = new Player($playerName, $mysqli);
    $player->updateBestScore($gameSession->getMoves());

    // Start a new game session after completion
    session_destroy();
    session_start();
    $numPairs = isset($_POST['numPairs']) ? intval($_POST['numPairs']) : 6;
    $_SESSION['game_session'] = new GameSession($mysqli, $numPairs);

    header('Location: game.php'); // Redirect to the same page to avoid resubmission
    exit;
}

$cards = $gameSession->getCards();
$moves = $gameSession->getMoves();
$topPlayers = Player::getTopPlayers($mysqli);
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
            <label for="numPairs">Select Difficulty:</label>
            <select name="numPairs" id="numPairs">
                <option value="6">Easy (6 pairs)</option>
                <option value="8">Medium (8 pairs)</option>
                <option value="10">Hard (10 pairs)</option>
            </select>
            <button type="submit" name="restart">Start New Game</button>
            <button type="submit" name="reset">Reset Game</button>
        </form>
        <p>Moves: <?= $moves ?></p>
        <div class="memory-game-board">
            <?php foreach ($cards as $index => $card): ?>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="flip" value="<?= $index ?>" class="memory-card <?= $card->isFlipped() ? 'flipped' : '' ?> <?= $card->isMatched() ? 'matched' : '' ?>">
                        <?= $card->isFlipped() ? '<i class="codicon ' . htmlspecialchars($card->getIcon()) . '"></i>' : '?' ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>

        <h2>Leaderboard</h2>
        <ol>
            <?php foreach ($topPlayers as $player): ?>
                <li><?= htmlspecialchars($player['name']) ?> - Best Score: <?= htmlspecialchars($player['best_score']) ?></li>
            <?php endforeach; ?>
        </ol>

        <!-- Sound Effects -->
        <audio id="flip-sound" src="flip.wav"></audio>
        <audio id="match-sound" src="match.wav"></audio>

        <script>
            // Play sound when a card is flipped
            document.querySelectorAll('.memory-card').forEach(card => {
                card.addEventListener('click', () => {
                    document.getElementById('flip-sound').play();
                });
            });

            // Play sound when a match is made
            if (<?= $gameSession->isCompleted() ? 'true' : 'false' ?>) {
                document.getElementById('match-sound').play();
            }
        </script>
    </div>
</body>
</html>
