<?php
class CardMemory {
    private $id;
    private $value;
    private $isMatched;

    public function __construct($id, $value, $isMatched = false) {
        $this->id = $id;
        $this->value = $value;
        $this->isMatched = $isMatched;
    }

    public function getId() {
        return $this->id;
    }

    public function getValue() {
        return $this->value;
    }

    public function isMatched() {
        return $this->isMatched;
    }

    public function setMatched($matched) {
        $this->isMatched = $matched;
    }
}
?>
