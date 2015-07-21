<?php

namespace Hautelook;

use Hautelook\Product;

class Cart
{

	/*
	* @property array item
    * The array that will hold all cart items 
    * since we are not persisting in a database. 
	*/
	private $items = array();

	/*
	* @property integer cart_weight
	* The running total for weight of the entire cart.
	*/
	private $cart_weight;


	/*
	* @property integer $discount
	* If a discount is applied it is percentage. 
	*/
	private $discount;


	/*
	* @property integer $discount_weight
	* If weight is equal or greater than 100 pounds
	* the grant free shipping
	*/
	private $discount_weight = 100;

	/*
	* @property_string $free_shipping
	* Determine if the cart qualifies for free shipping. 
	*/
	private $free_shipping = 0;

	/* 
	* @property integer @shipping
	* The total for the shipping.
	*/
	private $shipping;

	/*
	* @property integer $total
	* The total (items + discounts + shipping ) for the cart. 
	*/
	private $total;




	public function __construct()
	{
		$this->discount = 0;
	}



	/*
	* @method countItems()
	* @return integer 
	*/
	public function countItems()
	{
		return count($this->items);
	}


	/*
	* @method subtotal()
	* @return integer $subtotal
	*/
    public function subtotal()
    {
    	$subtotal = 0;
    	$total_weight = 0;
    	$num_items_over_ten_pounds = 0;
    	$extra_weight_charge = 0;

    	if(count($this->items) > 0)
    	{

    		// Iterate through the items array to: 
    		// 1.) calculate the subtotal. 
    		// 2.) calculate the total weight of the cart. 
    		// 3.) Figure out if there are any special / heavy items for shipping fees. 
    		foreach($this->items as $item)
    		{
    				$subtotal += ($item['price'] * $item['quantity']);
    				$total_weight += ($item['quantity'] * $item['weight']);
    				if($item['weight'] > 10)
    				{
    					$num_items_over_ten_pounds++;
    					$extra_weight_charge += $item['quantity'] * 20;
    				}
    		}

    		// Additional business logic: 
    		// If the subtotal is less than $100
    		// and the number of items over 10 pounds is 2
    		// then apply a flat extra charge of $45. 
    		if($subtotal < 100 && $num_items_over_ten_pounds == 2)
    		{
    			$extra_weight_charge = 45;
    		}


    		// Apply any discount
    		if($this->getDiscount() > 0)
    		{ 
    			#echo 'Discount exists so let us apply it...' . $this->getDiscount() . '<br/>';
     			$subtotal = ((100 - $this->getDiscount()) / 100) * $subtotal; 
    		}

    		// Calculate the total based on the subtotal. 
    		$this->calculateTotalLessThan100($subtotal,$total_weight);


    		// Add additional charges to the total based on heavy items.
    		$this->calculateTotalForHeavyItems($extra_weight_charge);

    	}

    	return $subtotal;

    }

    /* 
    * @method addItem()
    * @param object $product
    * We add items to the cart this way. 
    */
    public function addItem($product)
    {

    	// if the total number of items in the array are 0 then we know we have an empty cart. 
    	// however if the number of items in the array are greater than 0, then we can use that value 
    	// as our value for the $next key when we add a new object
    	$count      = count($this->items);
    	$next_key   = $count > 0 ? $count : 0;
    	$counter    = 0;
    	$item_exists= FALSE;

    		// See if any of the items is the same as the product being added. 
    		foreach($this->items as $item)
	    	{
	    		// Ff it is, then flag the existing item so we can update the quantity on it. 
	    		if($item['name'] == $product->getName())
	    		{
	    			$item_exists 	= TRUE;
	    			$key 			= $counter;
	    		}
	    		$counter++;    		
	    	}

	    	// If the item actually existed then proceed with the next step...
	    	if($item_exists == TRUE)
	    	{
	    		// and Update the quantity on the existing product.
	    		$this->items[$key]['quantity']   	= $this->items[$key]['quantity'] + 1;
	    	}
	    	else
	    	{
	    		// otherwise the product does not exist so add it to the end of the array. 
	    		$this->items[$next_key]['name']  	= $product->getName();
	    		$this->items[$next_key]['price']  	= $product->getPrice();
	    		$this->items[$next_key]['weight'] 	= $product->getWeight();
	    		$this->items[$next_key]['quantity'] = 1;

	    	}
    }


    /*
	* @method getQuantity()
	* @param string $product_name
	* @return integer $quantity
    */
    public function getQuantity($product_name)
   	{
   		$quantity = 0;

   		foreach($this->items as $item)
   		{

   			if($item['name'] == $product_name)
   			{
   				$quantity = $item['quantity'];
   			}
   			
   		}

   		return $quantity;
   	}



   	/*
	* @method setDiscount()
	* @param integer $discount
	* Just setting the value of the discount to the private property. 
   	*/
   	public function setDiscount($discount)
   	{
		$this->discount = intval($discount);
   	}


   	/*
	* @method getDiscount()
	* @return integer $discount 
	* The discount integer value. 
   	*/
   	public function getDiscount()
   	{
		return $this->discount;
   	}   


   	/* 
   	* @method setTotal()
   	* Get the total from the array. 
   	* @return integer $total;
   	*/
   	public function setTotal($subtotal, $extra_fee = 0)
   	{
   		$this->total = $subtotal + $extra_fee;
   	}   		


   	/* 
   	* @method getTotal()
   	* Get the total from the array. 
   	* @return integer $total;
   	*/
   	public function getTotal()
   	{
   		$total = 0;
   		$total = $this->total;

   		return $total;
   	}


   	/*
   	* @method calculateTotalWhenSubtotalLessThan100()
   	* @param integer $subtotal
   	* @param integer $total_weight
   	*/
   	private function calculateTotalLessThan100($subtotal, $total_weight)
   	{
    	if($subtotal < 100 && $total_weight < 10)
    	{
    		$this->setTotal($subtotal,5);
    	}
    	else
    	{
    		$this->setTotal($subtotal,0); 
    	}
   	}


   	/*
   	* @method calculateTotalForHeavyItems()
   	* @param integer $extra_weight_charge
   	*/
   	private function calculateTotalForHeavyItems($extra_weight_charge)
   	{
    	$this->total += $extra_weight_charge;
   	}   	

} 
