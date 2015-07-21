<?php
namespace Hautelook;

use Hautelook\Product;
use Hautelook\Promotion;

class Cart
{
    /**
     * @var array
     */
    private $products = array();

    /**
     * @var Promotion
     */
    private $promotion;

    /**
     * @var int
     */
    private $shippingWeight = 0;

    /**
     * @var int
     */
    private $subTotal = 0;

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->products[] = $product;
        $this->subTotal += $product->getCost();
        $this->shippingWeight += $product->getWeight();
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getNumberOfProducts()
    {
        return sizeof($this->getProducts());
    }

    /**
     * @return int
     */
    public function getSubTotal()
    {
        $subTotal = $this->subTotal;
        if (!is_null($this->promotion)) {
            $subTotal -= $subTotal * $this->promotion->getPercentOff();
        }

        return $subTotal;
    }

    /**
     * @return int
     */
    public function getShippingCost()
    {
        $overWeightItems = ($this->getShippingWeight() > 10) ? $this->getNumberOfItemsOverWeight(10) : 0;
        $shippingCost = ($overWeightItems > 0) ? 20 : 0;

        if (($this->getSubTotal() < 100)) {
            if ($this->getShippingWeight() < 10) {
                $shippingCost = 5;
            } else if ($overWeightItems >= 2) {
                $shippingCost = 45;
            }
        } else {
            if ($this->getShippingWeight() < 10) {
                $shippingCost = 0;
            }
        }

        return $shippingCost;
    }

    /**
     * @return int
     */
    public function getShippingWeight()
    {
        return $this->shippingWeight;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->getSubTotal() + $this->getShippingCost();
    }

    /**
     * @param Promotion $promotion
     */
    public function addPromotion(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @param $weight
     * @return int
     */
    private function getNumberOfItemsOverWeight($weight)
    {
        $itemsOverWeight = 0;
        foreach ($this->getProducts() as $product) {
            if ($product->getWeight() > $weight) {
                $itemsOverWeight++;
            }
        }
        return $itemsOverWeight;
    }
}
