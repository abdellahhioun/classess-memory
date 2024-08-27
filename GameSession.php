<?php
class GameSession {
    private $id;
    public $player_id;
    public $score;
    public $game_date;

    public function __construct($player_id, $score, $game_date = null, $id = null) {
        $this->id = $id;
        $this->player_id = $player_id;
        $this->score = $score;
        $this->game_date = $game_date ?? date('Y-m-d H:i:s');
    }

    public function save($pdo) {
        if ($this->id) {
            $sql = "UPDATE game_sessions SET player_id = ?, score = ?, game_date = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->player_id, $this->score, $this->game_date, $this->id]);
        } else {
            $sql = "INSERT INTO game_sessions (player_id, score, game_date) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->player_id, $this->score, $this->game_date]);
        }
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM game_sessions ORDER BY score DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT * FROM game_sessions WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
