<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Shahrukh\Payments\Payment;
use Omnipay\Common\CreditCard;

class PayPal implements Payment{
	public function __construct(){

	}

	public function pay(Request $request){
		/*$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername('shahrukh-buyer@amail.club');
		$gateway->setPassword('hestabit');
		$gateway->setApiKey('ELxSo6f58tfjIG0eKusgoEKhM2iTPA86JOUJbt-SwPYZMfhwZNxBQ3TBDOkjovRzmcAO5ErG2L_13NgZ');

		// Example form data
		$formData = [
		    'number' => '4242424242424242',
		    'expiryMonth' => '6',
		    'expiryYear' => '2016',
		    'cvv' => '123'
		];

		// Send purchase request
		$response = $gateway->purchase(
		    [
		        'amount' 	=> '10.00',
		        'currency' 	=> 'USD',
		        'returnUrl' => 'www.google.com',
		        'cancelUrl' => 'www.google.com'
		        //'card' => $formData
		    ]
		)->send();*/

		$gateway = Omnipay::create('PayPal_Rest');
		$gateway->initialize([
	        'clientId' => env('PAYPAL_CLIENT_ID'),
	        'secret'   => env('PAYPAL_CLIENT_SECRET'),
	        'testMode' => true, // Or false when you are ready for live transactions
	    ]);

		//$gateway->setUsername('qasim-facilitator@amail.club');
		//$gateway->setPassword('hestabit4');

		$formInputData = array(
		    'firstName' 	=> 'Bobby',
       		'lastName' 		=> 'Tables',
		    'number' 		=> '4111111111111111',
			'expiryMonth' 	=> '6',
			'expiryYear' 	=> '2019',
			'cvv' 			=> '123',
			'city'			=> 'CA',
			'currency'		=> 'USD',
			'countryCode'	=> 'US',
			'amount'		=> 10
		);

		$card = new CreditCard($formInputData);

		/*$requestss = $gateway->authorize(array(
		    'amount' 	=> '10.00', // this represents $10.00
		    'card' 		=> $card,
		    'returnUrl' => 'https://www.example.com/return',
		));*/

		$response = $gateway->purchase(array(
			'amount' => '10.00',
			'card' => $card
		))->send();

		dd($response);
	}

	public function invoice(Request $request){
		return 'shahrukh';
	}
}