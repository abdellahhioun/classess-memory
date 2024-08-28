<?php
require 'Card.php';

class GameSession {
    private $sessionId;
    private $playerId;
    private $cards = [];
    private $flips = 0;
    private $attempts = 0;
    private $mysqli;
    private $currentlyFlipped = [];

    public function __construct($playerId, $mysqli) {
        $this->playerId = $playerId;
        $this->mysqli = $mysqli;
        $this->startNewSession();
    }

    private function startNewSession() {
        $query = "INSERT INTO game_sessions (player_id) VALUES (?)";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $this->playerId);
        $stmt->execute();
        $this->sessionId = $stmt->insert_id;
    }

    public function selectAndShuffleCards($numPairs) {
        $query = "SELECT * FROM cards ORDER BY RAND() LIMIT ?";
        $stmt = $this->mysqli->prepare($query);
        $limit = $numPairs * 2;
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $icons = [
            "fa-heart", "fa-star", "fa-moon", "fa-sun", 
            "fa-bell", "fa-car", "fa-apple", "fa-leaf",
            "fa-music", "fa-plane", "fa-tree", "fa-umbrella"
        ];

        foreach ($result as $index => $row) {
            $icon = $icons[$index % count($icons)];
            $this->cards[] = new Card($row['id'], $row['card_value'], $icon);
        }

        shuffle($this->cards);
    }

    public function flipCard($position) {
        if (isset($this->cards[$position]) && count($this->currentlyFlipped) < 2) {
            $card = $this->cards[$position];
            if (!$card->isFlipped()) {
                $card->setFlipped(true);
                $this->currentlyFlipped[] = $position;
                $this->flips++;

                // Check for match if two cards are flipped
                if (count($this->currentlyFlipped) == 2) {
                    $this->checkMatch();
                }
            }
        }
    }

    private function checkMatch() {
        $firstPosition = $this->currentlyFlipped[0];
        $secondPosition = $this->currentlyFlipped[1];
        $firstCard = $this->cards[$firstPosition];
        $secondCard = $this->cards[$secondPosition];

        if ($firstCard->getValue() === $secondCard->getValue()) {
            $firstCard->setMatched(true);
            $secondCard->setMatched(true);
        } else {
            // If no match, flip the cards back after a delay
            // Use a delay mechanism in your front-end to flip them back visually.
            $firstCard->setFlipped(false);
            $secondCard->setFlipped(false);
        }

        // Clear the flipped cards list for the next attempt
        $this->currentlyFlipped = [];
        $this->attempts++;
    }

    public function endSession($score) {
        $query = "UPDATE game_sessions SET score = ?, flips = ?, attempts = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("iiii", $score, $this->flips, $this->attempts, $this->sessionId);
        $stmt->execute();
    }

    public function getCards() {
        return $this->cards;
    }

    public function getFlips() {
        return $this->flips;
    }

    public function getAttempts() {
        return $this->attempts;
    }
}
?>
