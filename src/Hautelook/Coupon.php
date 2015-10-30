<?php

namespace Hautelook;

class Coupon
{

    protected $percent;

    public function __construct($percent = 0){
        $this->percent += $percent;
    }

    public function calculate(Cart $cart){
        $subtotal = $cart->subtotal();
        $discount = round(($this->percent / 100) * $subtotal, 0);
        return $subtotal -= $discount;
    }
}
