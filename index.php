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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to the Memory Game</h1>
        <form action="game.php" method="post">
            <label for="player_name">Enter Your Name:</label>
            <input type="text" id="player_name" name="player_name" required>
            
            <label for="pairs">Select Number of Pairs:</label>
            <select id="pairs" name="pairs">
                <option value="3">3 Pairs</option>
                <option value="4">4 Pairs</option>
                <option value="5">5 Pairs</option>
                <option value="6">6 Pairs</option>
                <option value="7">7 Pairs</option>
                <option value="8">8 Pairs</option>
                <option value="9">9 Pairs</option>
                <option value="10">10 Pairs</option>
                <option value="11">11 Pairs</option>
                <option value="12">12 Pairs</option>
            </select>

            <button type="submit">Start Game</button>
        </form>
    </div>
</body>
</html>
