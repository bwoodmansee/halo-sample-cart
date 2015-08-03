<?php
namespace Hautelook;

class Product
{
	private $name;
	private $price;
   private $weight;
   
   function __construct($name, $price, $weight = 0) {
      $this->name = $name;
      $this->price = (float)$price;
      $this->weight = (float)$weight;
   }
	
	public function __get($property) {
      if (property_exists($this, $property)) {
         return $this->$property;
      }
   }
   
   public function __set($property, $value) {
      if (property_exists($this, $property)) {
         $this->$property = $value;
      }
   }
} 
