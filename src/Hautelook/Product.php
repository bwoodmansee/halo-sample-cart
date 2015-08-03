<?php

namespace Hautelook;

class Product
{
    public $name;
    public $price;
    public $qty = 1;
    public $weight = 0;

    public function __construct($name=NULL, $price=0, $weight=0)
    {
        $this->name = $name;
        $this->price = $price;
        $this->weight = $weight;
    }
}