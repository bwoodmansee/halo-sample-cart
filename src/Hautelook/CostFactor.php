<?php
namespace Hautelook;

class CostFactor implements IShippingFactor {
	public function calculate($cart) {
		if ($cart->subtotal() < 100) {
			return 5;
		}
		
		return 0;
	}
}

class WeightFactor implements IShippingFactor {
	public function calculate($cart) {
		$heavyItems =  $cart->getCountOfItemsOfOrOver(10);
		
		return $cart->getCountOfItemsOfOrOver(10) * 20;
	}
} 