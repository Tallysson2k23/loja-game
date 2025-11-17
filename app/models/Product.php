<?php

class Product {
    private $id;
    private $name;
    private $pointsRequired;
    private $description;

    public function __construct($id, $name, $pointsRequired, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->pointsRequired = $pointsRequired;
        $this->description = $description;
    }

    public static function getAll() {
        return [
            new Product(1, 'Vale-Cinema', 50, 'Troque por um ingresso de cinema'),
            new Product(2, 'Vale-Comida', 100, 'Troque por um vale-compras de supermercado')
        ];
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPointsRequired() {
        return $this->pointsRequired;
    }

    public function getDescription() {
        return $this->description;
    }
}
?>
