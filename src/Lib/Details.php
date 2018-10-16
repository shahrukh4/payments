<?php

namespace Shahrukh\Payments\Lib;

/**
 * 
 */
class Details{
	public function setShippingCharge($shipping_charge){
		$this->shipping = $shipping_charge;

		return $this;
	}

	public function setShippingTax($shipping_tax){
		$this->tax = $shipping_tax;

		return $this;
	}

	public function setSubtotal($subtotal){
		$this->subtotal = $subtotal;

		return $this;
	}

	public function getShippingCharge(){
		return $this->shipping;
	}

	public function getShippingTax(){
		return $this->tax;
	}

	public function getSubtotal(){
		return $this->subtotal;
	}
}