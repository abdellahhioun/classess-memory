<?php
require 'db_connection.php';
require 'Player.php';
require 'GameSession.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="style_memory.css">
</head>
<body>
    <div class="memory-container">
        <h1>Welcome to the Memory Game</h1>
        <form action="game.php" method="post">
            <label for="player_name">Enter Your Name:</label>
            <input type="text" id="player_name" name="player_name" required>

            <label for="pairs">Select Number of Pairs:</label>
            <select id="pairs" name="pairs">
                <?php for ($i = 3; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> Pairs</option>
                <?php endfor; ?>
            </select>

            <button type="submit">Start Game</button>
        </form>
    </div>
</body>
</html>
