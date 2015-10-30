<?php

namespace Hautelook;

class Weight
{

    protected $weight;

    public function __construct($weight = 0){
        $this->weight = $weight;
    }

    public function setWeight($weight){
        $this->weight = $weight;
        return $this;
    }

    public function getWeight(){
        return $this->weight;
    }
}
