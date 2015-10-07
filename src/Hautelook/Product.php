<?php
namespace Hautelook;

class Product
{
    /**
     * @var int
     */
    public $price = 0;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $weight;

    /**
     * @param $price
     * @param $name
     * @param $weight
     */
    public function __construct($price, $name, $weight = 0)
    {
        $this->price = $price;
        $this->name = $name;
        $this->weight = $weight;
    }
} 
