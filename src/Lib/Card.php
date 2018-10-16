<?php

namespace Shahrukh\Payments\Lib;

/**
 * 
 */
class Card{
	/*
	For Cards
	 */
	public function setFirstname($first_name){
		$this->first_name = $first_name;

		return $this;
	}

	public function setLastname($last_name){
		$this->last_name = $last_name;

		return $this;
	}

	public function setCvv($cvv){
		$this->cvv2 = $cvv;

		return $this;
	}

	public function setExpireYear($expire_year){
		$this->expire_year = $expire_year;

		return $this;
	}

	public function setExpireMonth($expire_month){
		$this->expire_month = $expire_month;

		return $this;
	}

	public function setNumber($number){
		$this->number = $number;

		return $this;
	}

	public function setType($type){
		$this->type = $type;

		return $this;
	}


	/*
	For Cards
	 */
	
	/**
	 * [getFirstname description]
	 * @param  [type] $first_name [description]
	 * @return [type]             [description]
	 */
	public function getFirstname(){
		return $this->first_name;
	}
	
	/**
	 * [getLastname description]
	 * @param  [type] $last_name [description]
	 * @return [type]            [description]
	 */
	public function getLastname(){
		return $this->last_name;
	}
	
	/**
	 * [getCvv description]
	 * @param  [type] $cvv [description]
	 * @return [type]      [description]
	 */
	public function getCvv(){
		return $this->cvv2;
	}
	
	/**
	 * [getExpireYear description]
	 * @param  [type] $expire_year [description]
	 * @return [type]              [description]
	 */
	public function getExpireYear(){
		return $this->expire_year;
	}
	
	/**
	 * [getExpireMonth description]
	 * @param  [type] $expire_month [description]
	 * @return [type]               [description]
	 */
	public function getExpireMonth(){
		return $this->expire_month;
	}
	
	/**
	 * [getNumber description]
	 * @param  [type] $number [description]
	 * @return [type]         [description]
	 */
	public function getNumber(){
		return $this->number;
	}
}