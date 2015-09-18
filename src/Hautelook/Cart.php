<?php
namespace Hautelook;

class Cart
{
	public $products_in_cart = [];
	public $discount_percent = 0;

	public function total() {
		$subtotal = $this->subtotal();
		$heavy_items = $this->get_cart_products_over_weight(10);

		// Calculate Shipping
		if ( ($this->subtotal() < 100) && ($this->get_cart_total_weight() < 10) && $heavy_items <= 0) {
			$shipping = 5;

		} else if ( ($this->subtotal() >= 100) && $heavy_items <= 0) {
			$shipping = 0;

		} else if ( ($this->subtotal() >= 100) && $heavy_items >=1 ) {
			$shipping = 20*$heavy_items;

		} else if ( ($this->subtotal() < 100) && $heavy_items >=1 ) {
			$shipping = 5+(20*$heavy_items);
		}

		return ($subtotal+$shipping);
	}

    public function subtotal()
    {
    	if (empty($this->products_in_cart)){
    		return 0;

    	} else {
    		$subtotal=0;

    		foreach ($this->products_in_cart as $product) {
    			$subtotal += $product->cost;
    		}
    		// Apply coupon 
    		$subtotal -= ($subtotal*$this->discount_percent);

    		return $subtotal;
    	}
    }

    public function add_product($name, $cost, $weight=0)
    {
    	$product = array(
		    "name" => $name,
		    "cost" => (int) $cost,
		    "weight"   => (int) $weight
		);
    	$this->products_in_cart[] = (object) $product;
    }

    public function get_quantity( $product_name )
    {
    	$q=0;
    	for ($i = 0; $i < count($this->products_in_cart); $i++) {
		    if ( $this->products_in_cart[$i]->name == $product_name ) {
		    	$q++;
		    }
		}
		return $q;
    }

    public function get_cart_total_weight()
    {
    	$w=0;
    	for ($i = 0; $i < count($this->products_in_cart); $i++) {
    		$w += $this->products_in_cart[$i]->weight;
		}
		return $w;
    }

    
    public function get_cart_products_over_weight( $weight )
    {
    	$p=0;
    	for ($i = 0; $i < count($this->products_in_cart); $i++) {
    		if ( $this->products_in_cart[$i]->weight >= $weight) $p++;
		}
		return $p;
    }

    public function apply_coupon( $discount_percent )
    {
    	$this->discount_percent = ($discount_percent/100);
    }


} 
