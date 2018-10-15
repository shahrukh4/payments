<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
use Omnipay\Omnipay;
use PayPal\Api\Refund;
use Illuminate\Http\Request;
use Shahrukh\Payments\Payment as Shahrukh;
//use Omnipay\Common\CreditCard;

/*-for paypal--*/
use PayPal\Api\Sale;
use Paypalpayment;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\CreditCard;

use Shahrukh\Payments\Handlers\Setter;

class PayPal extends Setter implements Shahrukh{
	/**
	 * [__construct description]
	 */
	public function __construct(){
		//self::setRules();

		/* setup PayPal api context */
        $this->_api_context = Paypalpayment::ApiContext(env('PAYPAL_CLIENT_ID'), env('PAYPAL_CLIENT_SECRET'));
	}

	/**
	 * [pay description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function pay(Request $request){
		$gateway = Omnipay::create('PayPal_Rest');
		$gateway->initialize([
	        'clientId' => env('PAYPAL_CLIENT_ID'),
	        'secret'   => env('PAYPAL_CLIENT_SECRET'),
	        'testMode' => true, // Or false when you are ready for live transactions
	    ]);

		$formInputData = [
		    'firstName' 		=> 'Shahrukh',
       		'lastName' 			=> 'Anwar',
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
		   'amount'        => '200.00',
		   'currency'      => 'USD',
		   'description'   => 'Test transactions',
		   'card'          => $card,
		));

		$response = $transaction->send();

		dd($response->getData());
	}

	/**
	 * [invoice description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function invoice(Request $request){
		$payment_id = "PAY-5E4488938X808170ULPCFKDQ"; //'PAY-16T71356CG9892356LPAGIOQ';

		$payment = Paypalpayment::getById($payment_id, $this->_api_context);	

		dd($payment);
	}

	/**
	 * [refund description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function refund(Request $request){
		try{
	        $transaction_id = "3S7574554G079694D";

	        $amt = new Amount();
			$amt->setTotal(1)
			->setCurrency('USD');

			$refund = new Refund();
			$refund->setAmount($amt)
			->setReason('It\'s on me what to do.');

			$sale = new Sale();
			$sale->setId($transaction_id);

			$refundedSale = $sale->refund($refund, $this->_api_context);

			dd($refundedSale);
		}
		catch(\Exception $e){
			dd($e);
		}
	}

	/**
	 * [paypal description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function paypal(Request $request){
		try{
			self::setRules();

			$validator = Validator::make($this->rules, [
				'item_name' 	 => 'required|string',
				'payment_method' => 'required|string',
				'price'			 => 'required',
				'tax'			 => 'numeric',
				'description'	 => 'string',
				'invoice_num'	 => 'alphanum',
				'currency'		 => 'string',
				'quantity'		 => 'integer',
				'total'			 => 'numeric',
				'return_url'	 => 'url',
				'cancel_url'	 => 'url',
			]);

			/**
			 * If validator fails
			 */
			if($validator->fails()){
				return $validator->messages()->toArray();
			}

			$payer = new Payer();
	        $payer->setPaymentMethod($this->payment_method);

	        $item = new Item();
	        $item->setName($this->getItemName())   //item name
	        ->setCurrency($this->getCurrency())	   //set currency
	        ->setQuantity($this->getQuantity())	   //set quantity
	        ->setTax($this->getTax())		   	   //set tax
	        ->setPrice($this->getPrice()); 	       //unit price

	        $item_list = new ItemList();
	        $item_list->setItems([ $item ]);

	        $amount = new Amount();
	        $amount->setCurrency($this->getCurrency())
	        ->setTotal($this->getTotal());

	        $transaction = new Transaction();
	        $transaction->setAmount($amount)
	        ->setItemList($item_list)
	        ->setDescription($this->getDescription())
	        ->setInvoiceNumber($this->getInvoiceNumber());

	        $redirect_urls = new RedirectUrls();

	        $redirect_urls->setReturnUrl($this->getReturnUrl()) //Specify return URL
	        ->setCancelUrl($this->getCancelUrl());				//Specify cancel URL

	        $payment = new Payment();

	        $payment->setIntent('sale')
	        ->setPayer($payer)
	        ->setRedirectUrls($redirect_urls)
	        ->setTransactions([$transaction]);

	        try {		
	            $payment->create($this->_api_context);

	            //if want only URL of redirectiction
	            if($this->extra_param['url']){
	            	return $payment->getApprovalLink();
	            }

	        } 
	        catch (\PayPal\Exception\PayPalConnectionException $ex) {
	            if (\Config::get('app.debug')) {
	                return redirect($this->getCancelUrl());
	            }
	             else {
	                return redirect($this->getCancelUrl());
	            }
	        }

	        $approval_url = $payment->getApprovalLink();
	        //header("Location: {$approval_url}");

	        /*foreach($payment->getLinks() as $link) {
	            if($link->getRel() == 'approval_url') {
	                $redirect_url = $link->getHref();
	                break;
	            }
	        }*/
		}
		catch(Exception $e){
			dd($e);
		}
	}

	public function vijay(){
		try{
			$shippingAddress = Paypalpayment::shippingAddress();
	        $shippingAddress->setLine1("3909 Witmer Road")
            ->setLine2("Niagara Falls")
            ->setCity("Niagara Falls")
            ->setState("NY")
            ->setPostalCode("14305")
            ->setCountryCode("US")
            ->setPhone("716-298-1822")
            ->setRecipientName("Jhone");

	        // ### CreditCard
	        $card = Paypalpayment::creditCard();
	        $card->setType("visa")
            ->setNumber("4758411877817150")
            ->setExpireMonth("05")
            ->setExpireYear("2019")
            ->setCvv2("456")
            ->setFirstName("Joe")
            ->setLastName("Shopper");
	        
	        $fi = Paypalpayment::fundingInstrument();
	        $fi->setCreditCard($card);

	        $payer = Paypalpayment::payer();
	        $payer->setPaymentMethod($this->getPaymentMethod())
	        ->setFundingInstruments([$fi]);

	        $item = Paypalpayment::item();
	        $item->setName('Ground Coffee 40 oz')
            ->setDescription('Ground Coffee 40 oz')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setTax($this->getTax())
            ->setPrice($this->getPrice());

	        $itemList = Paypalpayment::itemList();
	        $itemList->setItems([ $item ])
	        ->setShippingAddress($shippingAddress);


	        $details = Paypalpayment::details();
	        $details//->setShipping("1.2")
            //->setTax("1.3")
            //total of items prices
            ->setSubtotal($this->getSubTotal());

	        //Payment Amount
	        $amount = Paypalpayment::amount();
	        $amount->setCurrency($this->getCurrency())
            ->setTotal($this->getTotal())
            ->setDetails($details);

	        $transaction = Paypalpayment::transaction();
	        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->getDescription())
            ->setInvoiceNumber($this->getInvoiceNumber());

	        $payment = Paypalpayment::payment();

	        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]);

	        try {
	            $payment->create($this->_api_context);
	            
	            dd($payment);

	        } catch (\PPConnectionException $ex) {
	            return response()->json(["error" => $ex->getMessage()], 400);
	        }
		}
		catch(\Exception $e){
			dd($e);
		}
	}
}