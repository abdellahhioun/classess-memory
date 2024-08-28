<?php
class Card {
    public $id;
    public $value;
    public $isMatched;

    public function __construct($id, $value) {
        $this->id = $id;
        $this->value = $value;
        $this->isMatched = false;
    }
}
?>
