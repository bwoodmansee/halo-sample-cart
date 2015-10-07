<?php
namespace Hautelook;

class Cart
{
	/**
	 * @var array
	 */
	protected $products = [];

	/**
	 * @var CouponInterface
	 */
	protected $coupon;

	/**
	 * @param Product $product
	 */
	public function addProduct(Product $product)
	{
		$this->products[] = $product;
	}

	/**
	 * @param CouponInterface $coupon
	 */
	public function applyCoupon(CouponInterface $coupon)
	{
		$this->coupon = $coupon;
	}

	/**
	 * @return array
	 */
	public function getProducts()
	{
		return $this->products;
	}

	/**
	 * Resets products
	 */
	public function reset()
	{
		$this->products = [];
	}

	/**
	 * @param string $name
	 * @return integer
	 */
	public function quantityByProduct($name)
	{
		$total = 0;
		foreach ($this->products as $product) {
			if ($product->name === $name) {
				$total++;
			}
		}
		return $total;
	}

	/**
	 * @return int
	 */
	public function totalWeight()
	{
		$weight = 0;
		foreach ($this->products as $product) {
			$weight += $product->weight;
		}

		return $weight;
	}

	/**
	 * @param boolean $includeShipping
	 * @return integer
	 */
    public function subtotal($includeShipping = false)
    {
	    $total = 0;
	    foreach ($this->products as $product) {
		    $total += $product->price;
	    }

        $totalWithCoupon = $this->coupon ? $this->coupon->newPrice($total) : $total;

	    if ($includeShipping) {
			$shippingCalculator = new ShippingCalculator($this);
		    return $shippingCalculator->calculateShipping() + $totalWithCoupon;
	    } else {
		    return $totalWithCoupon;
	    }
    }
} 
