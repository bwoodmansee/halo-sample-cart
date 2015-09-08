<?php
namespace Hautelook;

class Cart
{
	private $items = [],
			$discount_percent = null;

    public function subtotal()
    {
        return array_sum(array_column($this->items, 'cost'));
    }

    public function getTotal() {
    	return $this->subtotal() + $this->addShippingCosts();
    }

    public function getCartCount() {
    	return count($this->items);
    }

    public function addItem($dollars, $product_name, $weight = null) {
		if($this->discount_percent)
			$dollars = $dollars - ($dollars * ($this->discount_percent * .01));

    	$this->items[] = ['name' => $product_name, 'cost' => $dollars, 'weight' => $weight];

    	return $this->getCartCount();
    }

    private function addShippingCosts() {
    	$subtotal = $this->subtotal();

    	$shipping_cost = 0;

    	$flat_ship_applied = false;

    	foreach($this->items as $item) {
    		if($item['weight'] > 10)
    			$shipping_cost += 20;
    		elseif($item['weight'] < 10 && $subtotal < 100 && !$flat_ship_applied) {
    			$shipping_cost += 5;

    			$flat_ship_applied = true;
    		}
    	}

    	return $shipping_cost;
    }

    public function getCountOfProduct($product_name) {
    	$count = 0;

    	array_walk($this->items, function($item, $idx) use ($product_name, &$count) {
    		if($item['name'] == $product_name)
    			$count++;
    	});

    	return $count;
    }

    public function cartContainsProductOfCost($product_name, $item_cost) {
    	foreach($this->items as $item)
    		if($item['name'] == $product_name && $item['cost'] == $item_cost)
    			return true;

    	return false;
    }

    public function applyDiscount($discount) {
    	$this->discount_percent = $discount;

    	$discounted_cost = 0;

    	foreach($this->items as $idx => $item) {
    		$new_cost = $item['cost'] - ($item['cost'] * ($discount * .01));

    		$discounted_cost += $new_cost;

    		$this->items[$idx]['cost'] = $new_cost;
    	}

    	return $discounted_cost;
    }

    public function cartContainsItemOfNameAndCost($item_cost, $product_name) {
    	foreach($this->items as $item)
    		if($item['name'] == $product_name && $item['cost'] == $product_cost)
    			return true;

    	return false;
    }

    public function getItems() {
    	return $this->items;
    }
} 
