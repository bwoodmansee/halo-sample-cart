<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use Hautelook\Cart,
    Hautelook\Product,
    Hautelook\Coupon,
    Hautelook\Weight;

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    private $cart;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
    }

    /**
     * @Given /^I have an empty cart$/
     */
    public function iHaveAnEmptyCart()
    {
        $this->cart = new Cart();
    }

    /**
     * @Then /^My subtotal should be "([^"]*)" dollars$/
     */
    public function mySubtotalShouldBeDollars($subtotal)
    {
       $check = isset($this->coupon);
	   $this->coupon = $check ? $this->coupon : new Coupon(); 
       Assert::assertEquals($subtotal, $this->coupon->calculate($this->cart));
    }

    /**
     * @When /^I add a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iAddADollarItemNamed($dollars, $product_name)
    {
    	$pretotal = $this->cart->subtotal();
        $discount = $this->cart->getDiscount();
    	$this->cart->addProduct(new Product($product_name, $dollars));
    	Assert::assertEquals($this->cart->subtotal(), $pretotal + ($dollars - $discount));
    }

    /**
     * @When /^I add a "([^"]*)" dollar "([^"]*)" lb item named "([^"]*)"$/
     */
    public function iAddADollarItemWithWeight($dollars, $lb, $product_name)
    {
        $this->product = new Product($product_name, $dollars, $lb);
        $this->cart->addProduct($this->product);
        Assert::assertInstanceOf(get_class($this->product), $this->product->setWeight($lb));
    }

    /**
     * @Then /^My total should be "([^"]*)" dollars$/
     */
    public function myTotalShouldBeDollars($total)
    {

        $subtotal = $this->cart->subtotal();
        $shippingCost = (int)0;

        // When order is under $100,
        $passedOrderUnder100Test = $subtotal < 100;
        // and all items under 10 lb,
        $products = $this->cart->getProducts();
        foreach ($products as $key => $value) {
            $weights[] = $value->getWeight() < 10;
        }
        $passedLbsUnder10Test = count(array_filter($weights)) == count($products);
            // then shipping is $5 flat
            if($passedOrderUnder100Test && $passedLbsUnder10Test){
                $shippingCost = 5;
            }

        // When order is $100 or more,
        $passedOrderOver100Test = $subtotal > 100;
        // and each individual item is under 10 lb,
        $passedLbsUnder10Test = $passedLbsUnder10Test;
            // then shipping is free
            if($passedOrderOver100Test && $passedLbsUnder10Test){
                $shippingCost = 0;
            }

        // Items over 10 lb
       foreach ($products as $key => $value) {
            if($value->getWeight() > 10){
                // always cost $20 each to ship
                $shippingCost += 20;
            }
        }

        Assert::assertEquals($total, $subtotal + $shippingCost);
    }

    /**
     * @Then /^My quantity of products named "([^"]*)" should be "([^"]*)"$/
     */
    public function myQuantityOfProductsShouldBe($product_name, $quantity)
    {
    	foreach($this->cart->getProducts() as $product){
    		if($product->getName() == $product_name){
    			$hits[] = $product;
    		}
    	}
    	Assert::assertEquals($quantity, count($hits));
    }


    /**
     * @Given /^I have a cart with a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iHaveACartWithADollarItem($item_cost, $product_name)
    {
    	$this->iHaveAnEmptyCart();
    	$item = new Product($product_name, $item_cost);
    	$this->cart->addProduct($item);
    	foreach($this->cart->getProducts() as $product){
    		Assert:assertContains($product->getName(), $product_name);
    	}
    }

    /**
     * @When /^I apply a "([^"]*)" percent coupon code$/
     */
    public function iApplyAPercentCouponCode($discount)
    {
       $this->coupon = new Coupon($discount);
       $total = $this->coupon->calculate($this->cart);
	   Assert::assertLessThan($this->cart->subtotal(), $total);
    }

    /**
     * @Then /^My cart should have "([^"]*)" item\(s\)$/
     */
    public function myCartShouldHaveItems($item_count)
    {
        throw new PendingException();
    }
}
