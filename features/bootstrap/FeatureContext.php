<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use Hautelook\Cart;
use Hautelook\Product;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

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
        $this->cart = new Cart();
    }

    /**
     * @Given /^I have an empty cart$/
     */
    public function iHaveAnEmptyCart()
    {
        Assert::assertEquals($this->cart, new Cart());
    }

    /**
     * @Then /^My subtotal should be "([^"]*)" dollars$/
     */
    public function mySubtotalShouldBeDollars($subtotal)
    {
        Assert::assertEquals($subtotal, $this->cart->subtotal());
    }

    /**
     * @When /^I add a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iAddADollarItemNamed($dollars, $product_name)
    {
        $item = new Product($product_name, $dollars);
        $this->cart->addProduct($item);
        // throw new PendingException();
    }

    /**
     * @Given /^I have a cart with a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iHaveACartWithADollarItemNamed($item_cost, $product_name)
    { 
        $item = new Product($product_name, $item_cost);
        $this->cart->addProduct($item);
        // throw new PendingException();
    }

    /**
     * @Then /^My quantity of products named "([^"]*)" should be "([^"]*)"$/
     */
    public function myQuantityOfProductsNamedShouldBe($product_name, $quantity)
    {
        $item = $this->cart->getItems($product_name);
        Assert::assertEquals($quantity, $item->qty);
        // throw new PendingException();
    }

    /**
     * @When /^I apply a "([^"]*)" percent coupon code$/
     */
    public function iApplyAPercentCouponCode($discount)
    {
        $this->cart->applyDiscount($discount);
        // throw new PendingException();
    }

    /**
     * @When /^I add a "([^"]*)" dollar "([^"]*)" lb item named "([^"]*)"$/
     */
    public function iAddADollarLbItemNamed($dollar, $lb, $product_name)
    { 
        // die(chr(10));
        $item = new Product($product_name, $dollar, $lb);
        $this->cart->addProduct($item);
        // throw new PendingException();
    }

    /**
     * @Given /^My total should be "([^"]*)" dollars$/
     */
    public function myTotalShouldBeDollars($total)
    {
        Assert::assertEquals($total, $this->cart->total());
        // throw new PendingException();
    }
}
