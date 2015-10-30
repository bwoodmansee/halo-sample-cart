<?php

namespace Hautelook;

class Cart
{
	protected $products, $hasCoupon, $totalDiscountPercent;

    	public function __construct(){
       		$this->products = [];
      		$this->hasCoupon = false;
      		$this->totalDiscountPercent = 0;
    	}

	public function addProduct(Product $product){
       	$this->products[] = $product;
       	return $product;
	}

	public function getProducts(){
		return $this->products;
	}

	public function subtotal($subtotal = 0){
		foreach ($this->products as $product){
        	$subtotal += $product->getPrice();
        }
		return $subtotal;
	}

	public function getDiscount(){
		return $this->totalDiscountPercent;
	}
}
