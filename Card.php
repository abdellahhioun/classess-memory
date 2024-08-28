<?php
class Card {
    private $id;
    private $value;
    private $icon;

    public function __construct($id, $value, $icon) {
        $this->id = $id;
        $this->value = $value;
        $this->icon = $icon;
    }

    public function getId() {
        return $this->id;
    }

    public function getValue() {
        return $this->value;
    }

    public function getIcon() {
        return $this->icon;
    }
}
?>
