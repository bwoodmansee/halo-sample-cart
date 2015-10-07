<?php
namespace Hautelook;

class ShippingCalculator
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @returns integer
     */
    public function calculateShipping()
    {
        $cart = $this->cart;
        $price = $cart->subtotal();

        // Shipping is always $5 if weight is under 10 lb
        if ($price < 100 && $cart->totalWeight() < 10) {
            return 5;
        }

        // Items over 10 pounds always cost $20 each to ship
        $shipping = 0;
        foreach ($cart->getProducts() as $product) {
            if ($product->weight >= 10) {
                $shipping += 20;
            } else if ($price < 100 && $product->weight <= 10) {
                $shipping += 5;
            }
        }
        return $shipping;
    }
} 
