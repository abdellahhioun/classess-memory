<?php
class Player {
    private $id;
    public $name;
    public $best_score;

    public function __construct($name, $best_score = 0, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->best_score = $best_score;
    }

    public function save($pdo) {
        if ($this->id) {
            $sql = "UPDATE players SET name = ?, best_score = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->name, $this->best_score, $this->id]);
        } else {
            $sql = "INSERT INTO players (name, best_score) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->name, $this->best_score]);
        }
    }

    public static function getAll($pdo) {
        $sql = "SELECT * FROM players ORDER BY best_score DESC LIMIT 10";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($pdo, $id) {
        $sql = "SELECT * FROM players WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
