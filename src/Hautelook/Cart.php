<?php

// How to:
// MacBook-Pro:halo-sample-cart-master wls$ /opt/vagrant/bin/vagrant ssh
// Last login: Sun Sep 20 23:30:57 2015 from 10.0.2.2
// vagrant@vagrant-ubuntu-trusty-64:~$ cd /vagrant
// vagrant@vagrant-ubuntu-trusty-64:/vagrant$ behat
// •••
// 	10 scenarios (10 passed)
// 	44 steps (44 passed)
// 	0m0.22s

namespace Hautelook;

class Cart
{
// 	private $myCart = array('description'=>'total', 'price'=>0.00, 'weight'=>0, 'quantity'=> 1);
	private $myCart = array();
	private $subtotal = 0;
	private $total    = 0;
	private $shipping = 0;
	private $discount = 0; // save this for the whole order.
	private $weight = 0;
	private $dbg = 0;
	
    public function orig_subtotal()
    {
        return 0;
    }
    
    public function addDiscount($discount) // add a discount to the cart.
    {
    	$this->discount += $discount; // += to support an additional discount to the whole order.
    }
    
    public function subtotal() // does not include shipping.
    {
    	$this->subtotal = 0;
    	foreach ($this->myCart as $k=>$v) {
			$this->subtotal += $v['quantity'] * $this->discountItems($v['price']);
    	}
    	return $this->subtotal;
    }

    public function total() // includes shipping charges.
    {
    	$this->total    = 0;
    	$this->shipping = 0;
    	$this->weight   = 0;
    	foreach ($this->myCart as $k=>$v) {
    		$this->total += $v['quantity'] * $this->discountItems($v['price']);
    		if ($v['weight'] > 10) {
    			$this->shipping += 20; // surcharge for a heavy item.
    		} else {
    			$this->weight += $v['weight']; // total of non-heavy items.
    		}
    	}
    	if (($this->total < 100) && ($this->weight < 10)) {
        	$this->shipping += 5; // flat rate shipping in addition to the $20 for over 10 lbs.
        }
    	return $this->total + $this->shipping;
    }

    public function subtotalByDescription($description)
    {
    	$this->subtotal = 0;
    	$this->var_dumper('subtotalByDescription',$this->myCart);
    	foreach ($this->myCart as $k=>$v) {
    		if ($v['description'] == $description) {
				$this->subtotal += $v['quantity'] * $this->discountItems($v['price']);
				$this->var_dumper('subtotal loop',$this->subtotal);
    		}
    	}
    	$this->var_dumper('subtotal',$this->subtotal);
    	return $this->subtotal;
    }

    public function quantityByDescription($description)
    {
    	$this->quantity = 0;
    	$this->var_dumper('quantityByDescription',$this->myCart);
    	foreach ($this->myCart as $k=>$v) {
    		if ($v['description'] == $description) {
				$this->quantity += $v['quantity'];
				$this->var_dumper('quantity loop',$this->quantity);
    		}
    	}
    	$this->var_dumper('quantity',$this->quantity);
    	return $this->quantity;
    }

    public function discountItems($price)
    {
		return ($price * ((100 - $this->discount)/100));
    }

    public function addNamedItem($description,$price,$quantity=1)
    {
    	$this->myCart[] = array('description'=>$description, 'price'=>$price, 'weight'=> 0, 'quantity'=> $quantity);
    	$this->var_dumper('addNamedItem',$this->myCart);
    }

    public function addWeighedItem($description,$price,$weight)
    {
    	$this->myCart[] = array('description'=>$description, 'price'=>$price, 'weight'=> $weight, 'quantity'=> 1);
    	$this->var_dumper('addWeighedItem',$this->myCart);
    }

    private function var_dumper($msg,$var)
    {
    	if ($this->dbg) {
			var_dump($msg);
			var_dump($var);
    	}
    }
} 
