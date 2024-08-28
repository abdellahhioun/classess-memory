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
