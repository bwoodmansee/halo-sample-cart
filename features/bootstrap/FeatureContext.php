<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step\Given,
   Behat\Behat\Context\Step\When;
use PHPUnit_Framework_Assert as Assert;
use Hautelook\Cart;
use Hautelook\Product;

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
        Assert::assertEquals($subtotal, $this->cart->subtotal());
    }

    /**
     * @When /^I add a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iAddADollarItemNamed($dollars, $product_name)
    {  
        $this->cart->add(new Product($product_name, $dollars));
    }
    
    /**
     * @When /^I add a "([^"]*)" dollar "([^"]*)" lb item named "([^"]*)"$/
     */
    public function iAddADollarItemWithWeight($dollars, $lb, $product_name)
    {
        $this->cart->add(new Product($product_name, $dollars, $lb));
    }
    
    /**
     * @Then /^My total should be "([^"]*)" dollars$/
     */
    public function myTotalShouldBeDollars($total)
    {
        Assert::assertEquals($total, $this->cart->total($total));
    }

    /**
     * @Then /^My quantity of products named "([^"]*)" should be "([^"]*)"$/
     */
    public function myQuantityOfProductsShouldBe($product_name, $quantity)
    {
        Assert::assertEquals($quantity, $this->cart->getQuantity($product_name));
    }
    

    /**
     * @Given /^I have a cart with a "([^"]*)" dollar item named "([^"]*)"$/
     */
    public function iHaveACartWithADollarItem($item_cost, $product_name)
    {
       return array(
         new Given("I have an empty cart"),
         new When("I add a \"{$item_cost}\" dollar item named \"{$product_name}\"")
       );
    }

    /**
     * @When /^I apply a "([^"]*)" percent coupon code$/
     */
    public function iApplyAPercentCouponCode($discount)
    {
        $this->cart->setDiscount($discount/100);
    }

    /**
     * @Then /^My cart should have "([^"]*)" item\(s\)$/
     */
    public function myCartShouldHaveItems($item_count)
    {
        throw new PendingException();
    }
}
