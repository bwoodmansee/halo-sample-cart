<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use PHPUnit_Framework_Assert as Assert;
use Hautelook\Cart;

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

// OK
// Scenario: Empty cart subtotal
//   Given I have an empty cart
//   Then My subtotal should be "0" dollars

    /**
     * @Given /^I have an empty cart$/
     */
    public function iHaveAnEmptyCart()
    {
        $this->cart = new Cart();
    }

// OK
// Scenario: Add a 10 dollar item to an empty cart
//   Given I have an empty cart
//   When I add a "10" dollar item named "shirt"
//   Then My subtotal should be "10" dollars

    /**
     * @When /^I add a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iAddADollarItemNamed($dollars, $product_name)
    {
        $this->cart->addNamedItem($product_name,$dollars);
//         throw new PendingException();
    }

// OK
// Scenario: When order is under $100, and all items under 10 lb, then shipping is $5 flat
//   Given I have an empty cart
//   When I add a "78" dollar "2" lb item named "dress"
//   And I add a "20" dollar "1" lb item named "skirt"
//   Then My subtotal should be "98" dollars
//   And My total should be "103" dollars

// OK
// Scenario: When order is $100 or more, and each individual item is under 10 lb, then shipping is free
//   Given I have an empty cart
//   When I add a "68" dollar "2" lb item named "dress"
//   And I add a "20" dollar "1" lb item named "skirt"
//   And I add a "20" dollar "1" lb item named "skirt"
//   Then My subtotal should be "108" dollars
//   And My total should be "108" dollars
//   And My quantity of products named "skirt" should be "2"

// OK
// Scenario: Items over 10 lb always cost $20 each to ship
//   Given I have an empty cart
//   When I add a "80" dollar "2" lb item named "dress"
//   And I add a "10" dollar "1" lb item named "tee"
//   And I add a "50" dollar "100" lb item named "couch"
//   Then My subtotal should be "140" dollars
//   And My total should be "160" dollars

    /**
     * @Then /^My subtotal should be "([^"]*)" dollars$/
     */
    public function mySubtotalShouldBeDollars($subtotal)
    {
        Assert::assertEquals($subtotal, $this->cart->subtotal());
    }

    /**
     * @When /^I add a "([^"]*)" dollar "([^"]*)" lb item named "([^"]*)"$/
     */
    public function iAddADollarItemWithWeight($dollars, $lb, $product_name)
    {
        $this->cart->addWeighedItem($product_name,$dollars,$lb);
//         throw new PendingException();
    }
    
    /**
     * @Then /^My total should be "([^"]*)" dollars$/
     */
    public function myTotalShouldBeDollars($total)
    {
        Assert::assertEquals($total, $this->cart->total());
//         throw new PendingException();
    }

// OK
// Scenario: Add an item twice
//   Given I have a cart with a "5" dollar item named "tee"
//   When I add a "5" dollar item named "tee"
//   Then My quantity of products named "tee" should be "2"

    /**
     * @Given /^I have a cart with a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iHaveACartWithADollarItem($item_cost, $product_name)
    {
        $this->cart = new Cart(); // must have this first to setup a non-empty cart.

        $this->cart->addNamedItem($product_name,$item_cost);
//         throw new PendingException();
    }

// OK
// Scenario: Add a 10 dollar item to a cart with a 5 dollar item
//   Given I have a cart with a "5" dollar item named "tee"
//   When I add a "10" dollar item named "shirt"
//   Then My subtotal should be "15" dollars

    /**
     * @Then /^My quantity of products named "([^"]*)" should be "([^"]*)"$/
     */
    public function myQuantityOfProductsShouldBe($product_name, $quantity)
    {
        Assert::assertEquals($quantity, $this->cart->quantityByDescription($product_name));
//         throw new PendingException();
    }
    
// OK
// Scenario: Apply a 10 percent coupon code to a cart with 10 dollars of items
//   Given I have a cart with a "10" dollar item named "shirt"
//   When I apply a "10" percent coupon code
//   Then My subtotal should be "9" dollars

    /**
     * @When /^I apply a "([^"]*)" percent coupon code$/
     */
    public function iApplyAPercentCouponCode($discount)
    {
    	$this->cart->addDiscount($discount);
//         throw new PendingException();
    }

    /**
     * @Then /^My cart should have "([^"]*)" item\(s\)$/
     */
    public function myCartShouldHaveItems($item_count)
    {
        throw new PendingException(); // not used.
    }

}
