<?php
namespace Hautelook;

class Promotion
{
    /**
     * @var float
     */
    private $percentOff;

    /**
     * @param $percentOff
     */
    public function __construct($percentOff)
    {
        $this->percentOff = $percentOff / 100;
    }

    /**
     * @return float
     */
    public function getPercentOff()
    {
        return $this->percentOff;
    }

}
