<?php
class GameCard {
    private $id;
    public $session_id;
    public $card_id;
    public $position;

    public function __construct($session_id, $card_id, $position, $id = null) {
        $this->id = $id;
        $this->session_id = $session_id;
        $this->card_id = $card_id;
        $this->position = $position;
    }

    public function save($pdo) {
        if ($this->id) {
            $sql = "UPDATE game_cards SET session_id = ?, card_id = ?, position = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->session_id, $this->card_id, $this->position, $this->id]);
        } else {
            $sql = "INSERT INTO game_cards (session_id, card_id, position) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->session_id, $this->card_id, $this->position]);
        }
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM game_cards";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getBySession($pdo, $session_id) {
        $sql = "SELECT * FROM game_cards WHERE session_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$session_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
