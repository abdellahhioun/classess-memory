<?php
require_once 'MemoryGame.php';

$pairsCount = 6; // Default to 6 pairs (12 cards)
if (isset($_GET['pairs'])) {
    $pairsCount = max(3, min(12, intval($_GET['pairs']))); // Ensure the number is between 3 and 12
}

$game = new MemoryGame($pairsCount);
$cards = $game->getCards();
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
    <h1>Memory Game</h1>
    <div class="game-board">
        <?php foreach ($cards as $index => $card): ?>
            <div class="card" data-id="<?= $card->id ?>" data-index="<?= $index ?>">
                <div class="card-front">?</div>
                <div class="card-back"><?= $card->value ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        let flippedCards = [];
        let matchedPairs = 0;
        const totalPairs = <?php echo $pairsCount; ?>;

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', () => {
                if (flippedCards.length < 2 && !card.classList.contains('flipped')) {
                    card.classList.add('flipped');
                    flippedCards.push(card);

                    if (flippedCards.length === 2) {
                        setTimeout(checkForMatch, 1000);
                    }
                }
            });
        });

        function checkForMatch() {
            const [card1, card2] = flippedCards;

            if (card1.dataset.id === card2.dataset.id) {
                matchedPairs++;
                if (matchedPairs === totalPairs) {
                    setTimeout(() => {
                        alert('You Won!');
                    }, 500);
                }
            } else {
                card1.classList.remove('flipped');
                card2.classList.remove('flipped');
            }

            flippedCards = [];
        }
    </script>
</body>
</html>
