<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
use Stripe\Error\Card;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Shahrukh\Payments\Payment;

use Shahrukh\Payments\Handlers\Setter;

class StripePay extends Setter implements Payment{
	public function validator($rules){
		$args = [];

		if(!empty($rules['card'])){
			foreach ($rules['card'] as $key => $value) {
				if(!is_object($value)){
					$rules[$key] = $value;
				}
			}
		}

		return $rules;
	}

	/**
	 * [pay description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function pay(){
	    /*$validator = Validator::make($this->rules, [
			"card.*.number" 		=> 'required',
			//'expire_month' => 'required',
			//'expire_year'	=> 'required',
			//'cvv2' 	=> 'required',
			'amount' 		=> 'required',
		]);*/

			/**
			 * If validator fails
			 */
			/*if($validator->fails()){
				return $validator->messages()->toArray();
			}*/

		//if ($validator->passes()) { 
			//$input = array_except($input,array('	_token'	));
			$stripe = Stripe::make(config('payments.stripe.secret'));

			try {
				$token = $stripe->tokens()->create([
					'card' => [
						'number' 	=> $this->getCard()->number,
						'exp_month' => $this->getCard()->expire_month,
						'exp_year' 	=> $this->getCard()->expire_year,
						'cvc' 		=> $this->getCard()->cvv2
					],
				]);
				
				if (!isset($token['id'])) {
					return 'There are some technical issues, transaction not able to take place';
				}

				$charge = $stripe->charges()->create([
					'card' 			=> $token['id'],
					'currency' 		=> $this->getCurrency(),
					'amount' 		=> $this->getAmount(),
					'description' 	=> $this->getDescription(),
				]);

				if($charge['status'] == 'succeeded') {					
					return $charge;
				} 
				else {
					return 'Not able to connect to Stripe.';
				}
			} 
			catch (\Exception $e) {
				dd($e);
			} 
			catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
				dd($e);
			} 
			catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
				dd($e);
			}
		//}
	}

	/**
	 * [invoice description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * 'ch_1DK0jWB108n83JtHxlqSCqrK'
	 */
	public function invoice($payment_id){
		try{
			$stripe = Stripe::make(config('payments.stripe.secret'));

			$charge = $stripe->charges()
			->find($payment_id);

			return $charge;
		}
		catch(\Exception $e){
			dd($e);
		}
	}

	/**
	 * [refund description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * ch_1DK0jWB108n83JtHxlqSCqrK
	 */
	public function refund($transaction_id){
		try{
			$stripe = Stripe::make(config('payments.stripe.secret'));

			$refund = $stripe->refunds()
			->create($transaction_id, $this->getAmount(), [
				'reason' => !empty($this->getReason()) ? $this->getReason() : 'requested_by_customer'
			]);

			return $refund;
		}
		catch(\Exception $e){
			dd($e);
		}
	}
}