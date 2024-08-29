<?php
require_once 'Card.php';

class GameSession {
    private $cards = [];
    private $flippedIndices = [];

    public function __construct($mysqli, $numPairs) {
        $this->selectAndShuffleCards($mysqli, $numPairs);
    }

    private function selectAndShuffleCards($mysqli, $numPairs) {
        $icons = [
            "fa-heart", "fa-star", "fa-moon", "fa-sun", 
            "fa-bell", "fa-car", "fa-apple", "fa-leaf",
            "fa-music", "fa-plane", "fa-tree", "fa-umbrella"
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
        if (isset($this->cards[$index]) && !in_array($index, $this->flippedIndices)) {
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
                $firstCard->setFlipped(false);
                $secondCard->setFlipped(false);
            }

            $this->flippedIndices = [];
        }
    }

    public function getFlippedIndices() {
        return $this->flippedIndices;
    }
}
?>
