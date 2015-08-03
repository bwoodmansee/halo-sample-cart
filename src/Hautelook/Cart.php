<?php
namespace Hautelook;

class Cart
{
   private $products = array();
   private $discount = 0;
   
   public function subtotal()
   {
      $subtotal = 0;
      
      foreach ($this->products as &$product) {
         $subtotal = $subtotal + $product->price;
      }
      
      if ($this->discount > 0) {
         $subtotal = $subtotal - ($subtotal * $this->discount);
      }
       
      return $subtotal;
    }
    
    public function total() {
       $calculator = new ShippingCalculator();
       $shipping = $calculator->calculate($this);
       
       return $this->subtotal() + $shipping;
    }
    
    public function add($product) {
       $this->products[] = $product;
    }
    
    public function getCountOfItemsOfOrOver($lbs) {
       return count(array_filter($this->products, function ($v) use($lbs) {
          return $v->weight >= $lbs;
       }));
    }
    
    public function getQuantity($name) {
       if (empty($name)) {
          return count($this->products);
       } else {
         return count(array_filter($this->products, function ($v) use($name) {
            return $v->name === $name;
         })); 
       }
    }
    
    public function setDiscount($discount) {
       if (is_numeric($discount) && ((float)$discount) <= 1) {
          $this->discount = (float)$discount;
       }
    }
} 
