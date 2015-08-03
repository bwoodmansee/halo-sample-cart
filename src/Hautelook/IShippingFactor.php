<?php
namespace Hautelook;

interface IShippingFactor {
	public function calculate($cart);
}