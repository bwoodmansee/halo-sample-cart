<?php

namespace Hautelook;

class Cart
{
    private $subtotal   = 0;
    private $discount   = 0;
    private $weight     = 0;
    private $total      = 0;
    private $heavyitems = 0;
    private $basket     = array();

    const FREE_SHIPPING_LIMIT = 100;
    const SHIPPING_RATE_FLAT  = 5;
    const SHIPPING_RATE_HEAVY = 20;
    const HEAVY_ITEM_LIMIT    = 10;

    public function addProduct(Product $product)
    {
        // check to see if in basket already
        if(array_key_exists($product->name, $this->basket))
        {
            // increase qty instead of duplicate items
            $this->basket[$product->name]->qty++;
        }

        else 
        {
            $this->basket[$product->name] = $product;    
        }

        // should this be calculated when getting subtotal
        // instead of each time an item gets added?
        // $this->subtotal += $product->price * $this->basket[$product->name]->qty;
    }


    public function getItems($key=NULL)
    {
        if(is_null($key))
        {
            return $this->basket;    
        }
        
        else
        {
            return $this->basket[$key];
        }
    }


    public function applyDiscount($discount)
    {
        $this->discount = $discount;
    }


    private function calculateSubTotalAndWeight()
    {
        $subtotal = 0;
        $weight = 0;

        foreach($this->basket as $key => $item)
        {
            $subtotal += $item->price * $item->qty;
            $weight += $item->qty * $item->weight;

            if($item->weight > self::HEAVY_ITEM_LIMIT) {
                $this->heavyitems += $item->qty;
            }
        }

        $this->weight = $weight;

        // applying discount, if there is one
        // $discount_amount = $subtotal * $this->discount / 100;
        $this->subtotal = $subtotal - ($subtotal * $this->discount / 100);
    }


    public function subtotal()
    {
        $this->calculateSubTotalAndWeight();
        return $this->subtotal;
    }



    public function calculateTotalWithShipping()
    {
        if($this->subtotal < self::FREE_SHIPPING_LIMIT)
            // && $this->weight < 10)
        {
            $this->total = $this->subtotal + self::SHIPPING_RATE_FLAT;
        }

        else if($this->subtotal >= self::FREE_SHIPPING_LIMIT)
        {
            $this->total = $this->subtotal;
        }

        $this->total += $this->heavyitems * self::SHIPPING_RATE_HEAVY;
    }


    public function total()
    {
        $this->calculateTotalWithShipping();
        return $this->total;
    }
} 
