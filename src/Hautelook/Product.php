<?php
namespace Hautelook;

//this serves the purpose of creating products
//these would otherwise exist in a db
class Product
{
	public $id;
	public $name;
	public $price;
	public $weight;

	public function __construct($name, $price, $weight = 0)
	{
		$this->id = mt_rand(0, 9999); //generate a random product id
		$this->name = $name;
		$this->price = $price;
		$this->weight = $weight;
	}

	//don't ask for something we don't have
	public function __get($property)
	{
		throw new \Exception('property '.$property.' does not exist.');
	}
	
	//don't set something that we haven't defined
	public function __set($property, $val)
	{
		throw new \Exception('unexpected property '.$property);
	}
}