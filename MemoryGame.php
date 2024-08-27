<?php
require_once 'CardMemory.php';
require_once 'memory_config.php';

class MemoryGame {
    private $pdo;
    private $cards = [];

    public function __construct($pairsCount) {
        global $pdo;
        $this->pdo = $pdo;

        if ($pairsCount > 0) {
            $this->loadCards($pairsCount);
        }
    }

    private function loadCards($pairsCount) {
        $stmt = $this->pdo->query('SELECT * FROM cards ORDER BY RAND() LIMIT ' . ($pairsCount * 2));
        $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cards as $card) {
            $this->cards[] = new CardMemory($card['id'], $card['card_value']);
        }
        shuffle($this->cards);
    }

    public function startSession($playerId) {
        $stmt = $this->pdo->prepare('INSERT INTO game_sessions (player_id, score) VALUES (?, 0)');
        $stmt->execute([$playerId]);
    }

    public function getCards() {
        return $this->cards;
    }

    public function endSession($playerId, $score) {
        $stmt = $this->pdo->prepare('UPDATE players SET best_score = GREATEST(best_score, ?) WHERE id = ?');
        $stmt->execute([$score, $playerId]);
    }

    public function getTopScores() {
        $stmt = $this->pdo->query('SELECT name, best_score AS score FROM players ORDER BY best_score DESC LIMIT 10');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
