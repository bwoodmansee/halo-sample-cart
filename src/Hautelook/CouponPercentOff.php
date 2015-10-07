<?php
namespace Hautelook;

class CouponPercentOff implements CouponInterface
{
    /**
     * @var integer
     */
    public $percent;

    /**
     * @param $percent
     */
    public function __construct($percent)
    {
        $this->percent = $percent;
    }

    /**
     * @param $total
     * @return float
     */
    public function newPrice($total)
    {
        $discounted = ($this->percent / 100) * $total;
        return $total - $discounted;
    }
} 
