<?php
namespace Hautelook;

interface CouponInterface
{
    /**
     * @param $total
     * @return float
     */
    public function newPrice($total);
} 
