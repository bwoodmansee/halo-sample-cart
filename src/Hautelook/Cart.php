<?php
namespace Hautelook;

class Cart
{
	protected $_products;

	protected $_discount;

	const FLAT_SHIPPING_RATE = 5;

	const OVERWEIGHT_SHIPPING_RATE = 20;

	const MAX_ITEM_WEIGHT_FOR_FLAT_SHIPPING = 10;

	const MINIMUM_SUBTOTAL_FOR_FREE_SHIPPING = 100;

	public function __construct()
	{
		$this->_products = array();
		$this->_discount = 0;
	}

	public function subtotal()
	{
		$subtotal = 0;

		foreach($this->_products as $pid => $product)
		{
			$subtotal += $product['price'] * $product['qty'];
		}

		return $subtotal * (1 - $this->_discount);
	}

	public function calculateShipping()
	{
		$subtotal = $this->subtotal();

		$shipping = 0;

		if ($subtotal < self::MINIMUM_SUBTOTAL_FOR_FREE_SHIPPING)
			$shipping = self::FLAT_SHIPPING_RATE;

		foreach($this->_products as $pid => $product)
		{
			if ($product['weight'] >= 10)
			{
				$shipping += self::OVERWEIGHT_SHIPPING_RATE;
			}
		}

		return $shipping;
	}

	public function total()
	{
		return $this->subtotal() + $this->calculateShipping();
	}

	public function applyDiscount($discount)
	{
		$this->_discount = $discount/100;
	}

	public function add(Product $product)
	{
		//i don't like this but wrote it to satisy the test that adds an item twice using a product
		//name only. this would otherwise add the same product with 2 different ids so squirreliness
		//abounds to get the test to passing.
		if ($this->productExists($product->name))
		{
			$pid = $this->getProductIdByName($product->name);
			$this->_products[$pid]['qty'] += 1;
		} else
		{
			$this->_products[$product->id] = array(
				'name' => $product->name,
				'price' => $product->price,
				'weight' => $product->weight,
				'qty' => 1
			);
		}

		return true;
	}

	public function getProductIdByName($product_name)
	{
		foreach($this->_products as $pid => $product)
		{
			if ($product['name'] == $product_name)
				return $pid;
		}

		return false;
	}

	public function productExists($product_name)
	{
		return false != $this->getProductIdByName($product_name);
	}

	public function productQty($pid)
	{
		return $this->_products[$pid]['qty'];
	}
}