<?php

namespace Shahrukh\Payments\Lib;

/**
 * 
 */
class Shipping{
	public $city;
	public $line1;
	public $phone;
	//public $rules;
	public $line2;
	public $state;
	public $postal_code;
	public $country_code;
	public $recipient_name;

	/**
	 * [setLine1 description]
	 * @param [type] $line1 [description]
	 */
	public function setLine1($line1){
		$this->line1 = $line1;
		//$this->rules['line1'] = $line1;

		return $this;
	}

	/**
	 * [setLine2 description]
	 * @param [type] $line2 [description]
	 */
	public function setLine2($line2){
		$this->line2 = $line2;
		//$this->rules['line2'] = $line2;

		return $this;
	}

	/**
	 * [setCity description]
	 * @param [type] $city [description]
	 */
	public function setCity($city){
		$this->city = $city;
		//$this->rules['city'] = $city;

		return $this;
	}

	/**
	 * [setState description]
	 * @param [type] $state [description]
	 */
	public function setState($state){
		$this->state = $state;
		//$this->rules['state'] = $state;

		return $this;
	}

	/**
	 * [setPostalCode description]
	 * @param [type] $postal_code [description]
	 */
	public function setPostalCode($postal_code){
		$this->postal_code = $postal_code;
		//$this->rules['postal_code'] = $postal_code;

		return $this;
	}

	/**
	 * [setCountryCode description]
	 * @param [type] $country_code [description]
	 */
	public function setCountryCode($country_code){
		$this->country_code = $country_code;
		//$this->rules['country_code'] = $country_code;

		return $this;
	}

	/**
	 * [setPhone description]
	 * @param [type] $phone [description]
	 */
	public function setPhone($phone){
		$this->phone = $phone;
		//$this->rules['phone'] = $phone;

		return $this;
	}

	/**
	 * [setRecipientName description]
	 * @param [type] $recipient_name [description]
	 */
	public function setRecipientName($recipient_name){
		$this->recipient_name = $recipient_name;
		//$this->rules['recipient_name'] = $recipient_name;

		return $this;
	}

	/**
	 * [getLine1 description]
	 * @return [type] [description]
	 */
	public function getLine1(){
		return $this->line1;
	}

	/**
	 * [getLine2 description]
	 * @return [type] [description]
	 */
	public function getLine2(){
		return $this->line2;
	}

	/**
	 * [getCity description]
	 * @return [type] [description]
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * [getState description]
	 * @return [type] [description]
	 */
	public function getState(){
		return $this->state;
	}

	/**
	 * [getPostalCode description]
	 * @return [type] [description]
	 */
	public function getPostalCode(){
		return $this->postal_code;
	}

	/**
	 * [getCountryCode description]
	 * @return [type] [description]
	 */
	public function getCountryCode(){
		return $this->country_code;
	}

	/**
	 * [getPhone description]
	 * @return [type] [description]
	 */
	public function getPhone(){
		return $this->phone;
	}

	/**
	 * [getRecipientName description]
	 * @return [type] [description]
	 */
	public function getRecipientName(){
		return $this->recipient_name;
	}
}