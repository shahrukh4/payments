<?php

namespace Shahrukh\Payments\Contracts;

/**
 * 
 */
interface BaseSetter{
	/**
	 * [setPaymentMethod description]
	 * @param [type] $method [description]
	 */
	public function setPaymentMethod($method);
	
	/**
	 * [getPrice description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getPrice();
	
	/**
	 * [getCancelUrl description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getCancelUrl();
	
	/**
	 * [getReturnUrl description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getReturnUrl();
	
	/**
	 * [getExtraParam description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getExtraParam();
	
	/**
	 * [getCurrency description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getCurrency();
	
	/**
	 * [getItemName description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getItemName();
	
	/**
	 * [getQuantity description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getQuantity();
	
	/**
	 * [getTotal description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getTotal();
	
	/**
	 * [getInvoiceNumber description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getInvoiceNumber();
	
	/**
	 * [getDescription description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getDescription();
	
	/**
	 * [getTax description]
	 * @param  [type] $method [description]
	 * @return [type]         [description]
	 */
	public function getTax();
	
	/**
	 * [setPrice description]
	 * @param [type] $method [description]
	 */
	public function setPrice($method);
	
	/**
	 * [setTotal description]
	 * @param [type] $method [description]
	 */
	public function setTotal($method);
	
	/**
	 * [setTax description]
	 * @param [type] $method [description]
	 */
	public function setTax($method);
	
	/**
	 * [setQuantity description]
	 * @param [type] $method [description]
	 */
	public function setQuantity($method);
	
	/**
	 * [setCurrency description]
	 * @param [type] $method [description]
	 */
	public function setCurrency($method);
	
	/**
	 * [setDescription description]
	 * @param [type] $method [description]
	 */
	public function setDescription($method);
	
	/**
	 * [setInvoiceNumber description]
	 * @param [type] $method [description]
	 */
	public function setInvoiceNumber($method);
	
	/**
	 * [ItemName description]
	 * @param [type] $method [description]
	 */
	public function	setItemName($method);
	
	/**
	 * [setCancelUrl description]
	 * @param [type] $method [description]
	 */
	public function setCancelUrl($method);
	
	/**
	 * [setExtraParam description]
	 * @param [type] $method [description]
	 */
	public function setExtraParam($extra_param);
	
	/**
	 * [setReturnUrl description]
	 * @param [type] $method [description]
	 */
	public function setReturnUrl($method);
}