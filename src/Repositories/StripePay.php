<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
use Stripe\Error\Card;
use Illuminate\Http\Request;
use Cartalyst\Stripe\Stripe;
use Shahrukh\Payments\Payment;

class StripePay implements Payment{
	/**
	 * [pay description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function pay(Request $request){
	    $validator = Validator::make($request->all(), [
			'card_no' 		=> 'required',
			'ccExpiryMonth' => 'required',
			'ccExpiryYear'	=> 'required',
			'cvvNumber' 	=> 'required',
			//’amount’ => ‘required’,
		]);

		$input = $request->all();

		if($validator->fails()){//dd($validator->messages());
			$msg = $validator->messages()->toArray();

			foreach ($msg as $key => $value) {
				dd($value[0]);
			}
		}

		if ($validator->passes()) { 
			$input = array_except($input,array('	_token'	));
			$stripe = Stripe::make(env('STRIPE_SECRET'));

			try {
				$token = $stripe->tokens()->create([
					'card' => [
						'number' 	=> $request->get('card_no'),
						'exp_month' => $request->get('ccExpiryMonth'),
						'exp_year' 	=> $request->get('ccExpiryYear'),
						'cvc' 		=> $request->get('cvvNumber'),
					],
				]);

				// $token = $stripe->tokens()->create([
				// ‘card’ => [
				// ‘number’ => 	,
				// ‘exp_month’ => 10,
				// ‘cvc’ => 314,
				// ‘exp_year’ => 2020,
				// ],
				// ]);
				
				if (!isset($token['id'])) {
					dd('payment cant create');
					//return redirect()->route('addmoney.paywithstripe');
				}

				$charge = $stripe->charges()->create([
					'card' 			=> $token['id'],
					'currency' 		=> 'USD',
					'amount' 		=> 2,
					'description' 	=> 'Testing for stripe wallet',
				]);
dd($charge);
				if($charge['status'] == 'succeeded') {
					/**
					* Write Here Your Database insert logic.
					*/
					print_r($charge);exit();
					return redirect()->route('addmoney.paywithstripe');
				} 
				else {
					\Session::put('error', 'Money not add in wallet!!');
					return redirect()->route('addmoney.paywithstripe');
				}
			} 
			catch (\Exception $e) {
				dd($e);
				\Session::put('error',$e->getMessage());
				return redirect()->route('addmoney.paywithstripe');
			} 
			catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
				\Session::put('error',$e->getMessage());
				return redirect()->route('addmoney.paywithstripe');
			} 
			catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
				\Session::put('error',$e->getMessage());
				return redirect()->route('addmoney.paywithstripe');
			}
		}
	}

	/**
	 * [invoice description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function invoice(Request $request){
		$stripe = Stripe::make(env('STRIPE_SECRET'));

		$charge = $stripe->charges()->find('ch_1DK0jWB108n83JtHxlqSCqrK');

		return $charge;
	}

	/**
	 * [refund description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function refund(Request $request){
		try{
			$stripe = Stripe::make(env('STRIPE_SECRET'));

			$refund = $stripe->refunds()->create('ch_1DK0jWB108n83JtHxlqSCqrK');

			dd($refund);
		}
		catch(\Exception $e){
			dd($e);
		}
	}
}