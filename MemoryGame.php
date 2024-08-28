<?php
require_once 'memory_config.php';
require_once 'Card.php';

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
            $this->cards[] = new Card($card['id'], $card['card_value']);
        }
        shuffle($this->cards);
    }

    public function getCards() {
        return $this->cards;
    }
}
?>
