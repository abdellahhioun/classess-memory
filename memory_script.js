document.addEventListener('DOMContentLoaded', function() {
    fetchMemoryRankings();

    function fetchMemoryRankings() {
        fetch('get_memory_rankings.php')
            .then(response => response.json())
            .then(data => {
                const rankingsList = document.getElementById('memory-rankings-list');
                rankingsList.innerHTML = '';
                data.forEach(ranking => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${ranking.name}: ${ranking.score}`;
                    rankingsList.appendChild(listItem);
                });
            });
    }

    const gameBoard = document.getElementById('memory-game-board');
    if (gameBoard) {
        let firstCard = null;
        let secondCard = null;
        let matchedCards = 0;

        gameBoard.addEventListener('click', function(e) {
            const clickedCard = e.target;
            if (clickedCard.classList.contains('memory-card') && !clickedCard.classList.contains('matched')) {
                if (firstCard === null) {
                    firstCard = clickedCard;
                    firstCard.classList.add('flipped');
                } else if (secondCard === null && clickedCard !== firstCard) {
                    secondCard = clickedCard;
                    secondCard.classList.add('flipped');

                    // Compare the two cards
                    if (firstCard.dataset.value === secondCard.dataset.value) {
                        firstCard.classList.add('matched');
                        secondCard.classList.add('matched');
                        matchedCards += 2;

                        // Reset cards
                        firstCard = null;
                        secondCard = null;

                        // Check for win
                        if (matchedCards === gameBoard.children.length) {
                            const winMessage = document.createElement('div');
                            winMessage.id = 'win-message';
                            winMessage.textContent = 'You Won!';
                            document.querySelector('.memory-container').appendChild(winMessage);
                        }
                    } else {
                        setTimeout(() => {
                            firstCard.classList.remove('flipped');
                            secondCard.classList.remove('flipped');
                            firstCard = null;
                            secondCard = null;
                        }, 1000);
                    }
                }
            }
        });
    }
});
