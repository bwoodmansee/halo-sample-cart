<?php
	
use Hautelook\CostFactor;

namespace Hautelook;

class ShippingCalculator {
	private $factors;
	
	function __construct() {
		$this->factors = array(
			new CostFactor(),
			new WeightFactor()
		);
	}
	
	public function calculate($cart) {
		$cost = 0;
		
		foreach ($this->factors as &$factor) {
			$cost = $cost + $factor->calculate($cart);
		}

		return $cost;
	}
}