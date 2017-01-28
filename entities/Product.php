<?php

class Product {

    public $ProductId, $Product, $Prijs;

    public function __construct($ProductId, $Product, $Prijs) {

        $this->ProductId = $ProductId;
        $this->Product = $Product;
        $this->Prijs = $Prijs;
    }

}
