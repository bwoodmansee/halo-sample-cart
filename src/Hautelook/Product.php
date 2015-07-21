<?php
namespace Hautelook;

class Product
{
    private $name;
    private $cost;
    private $weight;

    /**
     * @param $name
     * @param $cost
     * @param int $weight
     */
    public function __construct($name, $cost, $weight = 0)
    {
        $this->name = $name;
        $this->cost = $cost;
        $this->weight = $weight;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
