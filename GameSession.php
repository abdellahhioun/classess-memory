<?php
require_once 'Card.php';

class GameSession {
    private $sessionId;
    private $playerId;
    private $cards = [];
    private $mysqli;

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
        $this->saveGameCards();
    }

    private function saveGameCards() {
        foreach ($this->cards as $position => $card) {
            $query = "INSERT INTO game_cards (session_id, card_id, position) VALUES (?, ?, ?)";
            $stmt = $this->mysqli->prepare($query);
            $cardId = $card->getId();
            $stmt->bind_param("iii", $this->sessionId, $cardId, $position);
            $stmt->execute();
        }
    }

    public function getCards() {
        return $this->cards;
    }

    public function endSession($score) {
        $query = "UPDATE game_sessions SET score = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("ii", $score, $this->sessionId);
        $stmt->execute();
    }
}
?>
