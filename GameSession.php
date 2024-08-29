<?php
require_once 'Card.php';

class GameSession {
    private $cards = [];
    private $flippedIndices = [];

    public function __construct($mysqli, $numPairs) {
        $this->selectAndShuffleCards($numPairs);
    }

    private function selectAndShuffleCards($numPairs) {
        // Updated icons array with 12 icons for 6 matches
        $icons = [
            "codicon-heart", "codicon-star", "codicon-check", "codicon-flame", 
            "codicon-git-pull-request", "codicon-git-merge", "codicon-paintcan", 
            "codicon-github-alt", "codicon-mortar-board", "codicon-tools", 
            "codicon-rocket", "codicon-beaker"
        ];

        $cards = [];
        for ($i = 0; $i < $numPairs; $i++) {
            $icon = $icons[$i];
            $cards[] = new Card($i * 2, $i, $icon);
            $cards[] = new Card($i * 2 + 1, $i, $icon);
        }

        shuffle($cards);
        $this->cards = $cards;
    }

    public function getCards() {
        return $this->cards;
    }

    public function flipCard($index) {
        if (isset($this->cards[$index]) && !in_array($index, $this->flippedIndices) && !$this->cards[$index]->isMatched()) {
            $card = $this->cards[$index];
            $card->setFlipped(true);
            $this->flippedIndices[] = $index;
        }
    }

    public function checkMatch() {
        if (count($this->flippedIndices) === 2) {
            $firstIndex = $this->flippedIndices[0];
            $secondIndex = $this->flippedIndices[1];
            $firstCard = $this->cards[$firstIndex];
            $secondCard = $this->cards[$secondIndex];

            if ($firstCard->getValue() === $secondCard->getValue()) {
                $firstCard->setMatched(true);
                $secondCard->setMatched(true);
            } else {
                // Only flip back the unmatched cards
                $firstCard->setFlipped(false);
                $secondCard->setFlipped(false);
            }

            // Clear flipped indices after checking for matches
            $this->flippedIndices = [];
        }
    }

    public function getFlippedIndices() {
        return $this->flippedIndices;
    }
}
?>
