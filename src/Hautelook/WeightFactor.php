<?php
namespace Hautelook;

class WeightFactor implements IShippingFactor {
	public function calculate($cart) {
		$heavyItems =  $cart->getCountOfItemsOfOrOver(10);
		
		return $cart->getCountOfItemsOfOrOver(10) * 20;
	}
} 