<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
use Omnipay\Omnipay;
use Illuminate\Http\Request;
use Shahrukh\Payments\Payment;
use Omnipay\Common\CreditCard;

/*-for paypal--*/
use Paypalpayment;

class PayPal implements Payment{
	public function __construct(){
		/* setup PayPal api context */
        $this->_api_context = Paypalpayment::ApiContext(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
	}

	public function pay(Request $request){
		$gateway = Omnipay::create('PayPal_Rest');
		$gateway->initialize([
	        'clientId' => env('PAYPAL_CLIENT_ID'),
	        'secret'   => env('PAYPAL_CLIENT_SECRET'),
	        'testMode' => true, // Or false when you are ready for live transactions
	    ]);

		$formInputData = [
		    'firstName' 		=> 'Bobby',
       		'lastName' 			=> 'Tables',
		    'number' 			=> '4032038634486363',
			'expiryMonth' 		=> '11',
			'expiryYear' 		=> '2023',
			'cvv' 				=> '123',
			'billingAddress1'   => '1 Scrubby Creek Road',
            'billingCountry'    => 'AU',
            'billingCity'       => 'Scrubby Creek',
            'billingPostcode'   => '4999',
            'billingState'      => 'QLD',
		];

		$card = new CreditCard($formInputData);

		// Do an authorisation transaction on the gateway
		$transaction = $gateway->authorize(array(
		   'amount'        => '15.00',
		   'currency'      => 'USD',
		   'description'   => 'Using for transactions',
		   'card'          => $card,
		));

		$response = $transaction->send();

		dd($response->getData()['payer']);
	}

	public function invoice(Request $request){
		$payment_id = 'PAY-16T71356CG9892356LPAGIOQ';

		$payment = Paypalpayment::getById($payment_id, $this->_api_context);	

		dd($payment);
	}
}