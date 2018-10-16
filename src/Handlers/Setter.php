<?php 

namespace Shahrukh\Payments\Handlers;

use Shahrukh\Payments\Lib\Card;
use Shahrukh\Payments\Lib\Details;
use Shahrukh\Payments\Lib\Shipping;
use Shahrukh\Payments\Contracts\BaseSetter;

class Setter implements BaseSetter{
	protected $tax;
	protected $type;
	protected $card;
	protected $rules;
	protected $price;
	protected $number;
	protected $intent;
	protected $amount;
	protected $reason;
	protected $details;
	protected $quantity;
	protected $currency;
	protected $item_name;
	protected $last_name;
	protected $cancel_url;
	protected $first_name;
	protected $return_url;
	protected $extra_param;
	protected $expire_year;
	protected $description;
	protected $expire_month;
	protected $payment_method;
	protected $unique_invoice_num;
	protected $shipping_details;


	/**
     * @return \PayPal\Api\ShippingAddress
     */
	public function shipping(){
	 	return new Shipping;
	}

	/**
     * @return \PayPal\Api\ShippingAddress
     */
	public function card(){
	 	return new Card;
	}

	/**
     * @return \PayPal\Api\ShippingAddress
     */
	public function details(){
	 	return new Details;
	}

	/**
	 * [setShippingDetails description]
	 * @param [type] $shipping_details [description]
	 */
	public function setShippingDetails($shipping_details){
		$this->shipping_details = $shipping_details;
		$this->rules['shipping_details'] = $shipping_details;

		return $this;
	}

	/**
	 * [setShippingDetails description]
	 * @param [type] $shipping_details [description]
	 */
	public function setCard($card){
		$this->card = $card;
		$this->rules['card'] = $card;

		return $this;
	}

	/**
	 * [setShippingDetails description]
	 * @param [type] $shipping_details [description]
	 */
	public function setAmount($amount){
		$this->amount = $amount;
		$this->rules['amount'] = $amount;

		return $this;
	}

	/**
	 * [setShippingDetails description]
	 * @param [type] $shipping_details [description]
	 */
	public function setReason($reason){
		$this->reason = $reason;
		$this->rules['reason'] = $reason;

		return $this;
	}

	/**
	 * [setShippingDetails description]
	 * @param [type] $shipping_details [description]
	 */
	public function setDetails($details){
		$this->details = $details;

		return $this;
	}

	/**
	 * [setPaymentMethod description]
	 * @param [type] $method [description]
	 */
	public function setPaymentMethod($method){
		$this->payment_method = $method;
		$this->rules['payment_method'] = $method;

		return $this;
	}
	
	/**
	 * [setReturnUrl description]
	 * @param [type] $return_url [description]
	 */
	public function setReturnUrl($return_url){
		$this->return_url = $return_url;
		$this->rules['return_url'] = $return_url;

		return $this;
	}

	/**
	 * [setExtraParam description]
	 * @param array $param [description]
	 */
	public function setExtraParam($param){
		$this->extra_param = $param;
		return $this;
	}

	/**
	 * [setCancelUrl description]
	 * @param [type] $cancel_url [description]
	 */
	public function setCancelUrl($cancel_url){
		$this->cancel_url = $cancel_url;
		$this->rules['cancel_url'] = $cancel_url;

		return $this;
	}

	/**
	 * [setItemName description]
	 * @param [type] $item_name [description]
	 */
	public function setItemName($item_name){
		$this->item_name = $item_name;
		$this->rules['item_name'] = $item_name;

		return $this;
	}

	/**
	 * [setInvoiceNumber description]
	 * @param [type] $unique_invoice_num [description]
	 */
	public function setInvoiceNumber($unique_invoice_num){
		$this->unique_invoice_num = $unique_invoice_num;
		$this->rules['invoice_num']	= $unique_invoice_num;

		return $this;
	}

	/**
	 * [setDescription description]
	 * @param [type] $description [description]
	 */
	public function setDescription($description){
		$this->description = $description;
		$this->rules['description'] = $description;

		return $this;
	}

	/**
	 * [setCurrency description]
	 * @param [type] $currency [description]
	 */
	public function setCurrency($currency){
		$this->currency = $currency;
		$this->rules['currency'] = $currency;

		return $this;
	}

	/**
	 * [setQuantity description]
	 * @param [type] $quantity [description]
	 */
	public function setQuantity($quantity){
		$this->quantity = $quantity;
		$this->rules['quantity'] = $quantity;

		return $this;
	}

	/**
	 * [setTax description]
	 * @param [type] $tax [description]
	 */
	public function setTax($tax){
		$this->tax = $tax;
		$this->rules['tax'] = $tax;

		return $this;
	}

	/**
	 * Payment intent.
 * Valid Values: ["sale", "authorize", "order"] : default -> sale
	 * @param  string $intent [description]
	 * @return [type]         [description]
	 */
	public function setIntent($intent = 'sale'){
		$this->intent = $intent;
		$this->rules['intent'] = $intent;

		return $this;
	}

	/**
	 * [setTotal description]
	 * @param [type] $total [description]
	 */
	public function setTotal($total){
		$this->total = $total;
		$this->rules['total'] = $total;

		return $this;
	}

	public function setSubTotal($subtotal){
		$this->subtotal = $subtotal;
		$this->rules['subtotal'] = $subtotal;

		return $this;
	}

	/**
	 * [setPrice description]
	 * @param [type] $price [description]
	 */
	public function setPrice($price){
		$this->price = $price;
		$this->rules['price'] = $price;

		return $this;
	}

	/**
	 * [getTax description]
	 * @return [type] [description]
	 */
	public function getTax(){
		return !empty($this->tax) ? $this->tax : 0.0;
	}

	/**
	 * [getIntent description]
	 * 
	 * @return [type] [description]
	 */
	public function getIntent(){
		return !empty($this->intent) ? $this->intent : 'sale';
	}

	/**
	 * [getDescription description]
	 * @return [type] [description]
	 */
	public function getDescription(){
		return !empty($this->description) ? $this->description : 'Default description.';
	}

	/**
	 * [getInvoiceNumber description]
	 * @return [type] [description]
	 */
	public function getInvoiceNumber(){
		return !empty($this->unique_invoice_num) ? $this->unique_invoice_num : uniqid();
	}

	/**
	 * [getTotal description]
	 * @return [type] [description]
	 */
	public function getTotal(){
		return !empty($this->total) ? $this->total : $this->price;
	}

	/**
	 * [getTotal description]
	 * @return [type] [description]
	 */
	public function getSubTotal(){
		return !empty($this->subtotal) ? $this->subtotal : $this->price;
	}

	/**
	 * [getPaymentMethod description]
	 * @return [type] [description]
	 */
	public function getPaymentMethod(){
		return $this->payment_method;
	}

	/**
	 * [getQuantity description]
	 * @return [type] [description]
	 */
	public function getQuantity(){
		return !empty($this->quantity) ? $this->quantity : 1;
	}

	/**
	 * [getItemName description]
	 * @return [type] [description]
	 */
	public function getItemName(){
		return $this->item_name;
	}

	/**
	 * [getCurrency description]
	 * @return [type] [description]
	 */
	public function getCurrency(){
		return !empty($this->currency) ? $this->currency : 'USD';
	}

	/**
	 * [getPrice description]
	 * @return [type] [description]
	 */
	public function getPrice(){
		return $this->price;
	}

	public function getReason(){
		return $this->reason;
	}

	/**
	 * [getCancelUrl description]
	 * @return [type] [description]
	 */
	public function getCancelUrl(){
		return $this->cancel_url;
	}

	/**
	 * [getReturnUrl description]
	 * @return [type] [description]
	 */
	public function getReturnUrl(){
		return $this->return_url;
	}
	
	/**
	 * [getType description]
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function getType(	){
		return !empty($this->type) ? $this->type : 'visa';
	}

	/**
	 * [getExtraParam description]
	 * @return [type] [description]
	 */
	public function getExtraParam(){
		return !empty($this->extra_param) ? $this->extra_param : false;
	}

	/**
	 * [getShippingDetails description]
	 * @return [type] [description]
	 */
	public function getShippingDetails(){
		return $this->shipping_details;
	}

	/**
	 * [getShippingDetails description]
	 * @return [type] [description]
	 */
	public function getCard(){
		return $this->card;
	}

	/**
	 * [getShippingDetails description]
	 * @return [type] [description]
	 */
	public function getAmount(){
		return $this->amount;
	}

	/**
	 * [getShippingDetails description]
	 * @return [type] [description]
	 */
	public function getDetails(){
		return $this->details;
	}


	/**
	 * [setRules description]
	 */
	public function setRules(){
		$this->rules = [
			'total'				=> $this->getTotal(),
			'price'				=> $this->getPrice(),
			'currency'			=> $this->getCurrency(),
			'quantity'			=> $this->getQuantity(),
			'item_name'			=> $this->getItemName(),
			'return_url'		=> $this->getReturnUrl(),
			'cancel_url'		=> $this->getCancelUrl(),
			'invoice_num'		=> $this->getInvoiceNumber(),
			'description'		=> $this->getDescription(),
			'payment_method'	=> $this->getPaymentMethod(),
		];
	}
}