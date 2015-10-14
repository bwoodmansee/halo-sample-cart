<?php
namespace Hautelook;
class Cart
{	
	
    public function subtotal($item, $amount, $product)
    {			   
		global $quantity, $total;
		var weight;
		
		switch ($item)
		{
			case 0: 
				return 0;
			case $item:			
				if($product == 'shirt' && $amount==10){
					$quantity= $this->updateQuantity(1);
					$discount = $this->discount(.1,$amount);
					$total= ($total+amount) - $discount;
				}
				elseif($product=='tee' && $amount==5) {
					$quantity=$this->updateQuantity(1);
					$total =$total+amount; 
				}
				elseif ($product=='tank' && $amount==10){
					$quantity=$this->updateQuantity(1);
					$discount = $this->discount(.1,$amount);
					$total =($total+amount) - $discount; 
				}
				elseif ($product=='dress' && $amount==30){
					$quantity=$this->updateQuantity(1);
					$discount = $this->discount(.1,$amount);
					$total = ($total+$amount) - $discount; 
				}			
				else {
					$total= $total+$amount;
				}
				
		}		
		

		if($product=='dress' )
		{
			$weight=2;
		}
		elseif ($product =='skirt' || $product=='tee'){
			$weight=1;
		}
		elseif ($product=='couch') {
			$weight=100;
		}
		elseif ($product=='lamp') {
			$weight=15;
		}
		elseif ($product=='end table')
		{
			$weight=25;
		}
		
		$shippingAmount = $this->shippingAmount($amount,$weight);					
		
		return $total + $shippingAmount ;

    }
	
	public function updateQuantity($item)
	{
		$item= $item+1;
		return $item;
	}
	
	public function discount($discountPercent, $amount)
	{		
			$discountTotal = $amount x $discountPercent;
			$return $discountTotal;		
	}
	
	public function shippingAmount($amount, $weight)
	{      
		if ($amount > 100 && $weight<10){
			$shippingCost=0;
		}		
		elseif ($amount <100 && $weight>10) {
			$shippingCost=45;
		}		
		elseif ($weight>10){
			$shippingCost=20;
		}
		
		return $shippingCost;
	}
} 

