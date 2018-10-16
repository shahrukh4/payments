<?php

namespace Shahrukh\Payments\Repositories;

use Validator;
//use Omnipay\Omnipay;
use PayPal\Api\Refund;
use Illuminate\Http\Request;
use Shahrukh\Payments\Payment as Shahrukh;
//use Omnipay\Common\CreditCard;

/*-for paypal--*/
use Paypalpayment;
use PayPal\Api\Sale;
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
	/*public function pay(Request $request){
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
	}*/

	
	/*
	|--------------------------------------------------------------------------
	| Getting the details of given transaction
	| Created By- Shahrukh Anwar(16-10-2018)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects
	|
	| 	 $payment_id = 'PAY-1JK339012V705711ALPC3QSI';  	: Required(fetch this id, when you are making transaction and store it safe)
	| 	
    |    $payment_details = \Payment::invoice($payment_id);
	*/
	public function invoice($payment_id){
		try{
			
			return Paypalpayment::getById($payment_id, $this->_api_context);	
		}
		catch(\Exception $e){
			dd($e);
		}
	}

    /*
	|--------------------------------------------------------------------------
	| To refund the paid amount
	| Created By- Shahrukh Anwar(16-10-2018)
	|--------------------------------------------------------------------------
	| reason 			: string  (Optional)
	| amount 			: numeric (Required)
	| transaction_id	: integer (Required)
	|--------------------------------------------------------------------------
	| Below are the example how you can set the objects
	|  
	| 	 	$transaction_id = '3S7574554G079694D';  	: Required(fetch this id, when you are making transaction and store
	|		$refund = \Payment::invoice($payment_id);
	| 
	*/
	public function refund($transaction_id){
		try{
	        $amt = new Amount();
			$amt->setTotal($this->getTotal()) 		
			->setCurrency($this->getCurrency());

			$refund = new Refund();
			$refund->setAmount($amt)
			->setReason($this->getReason());

			$sale = new Sale();
			$sale->setId($transaction_id);

			$refundedSale = $sale->refund($refund, $this->_api_context);

			return $refundedSale;
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

	//card payment
	public function pays(){
		try{
			/*$shippingAddress = Paypalpayment::shippingAddress();
	        $shippingAddress->setLine1("3909 Witmer Road")
            ->setLine2("Niagara Falls")
            ->setCity("Niagara Falls")
            ->setState("NY")
            ->setPostalCode("14305")
            ->setCountryCode("US")
            ->setPhone("716-298-1822")
            ->setRecipientName("Jhone");*/

    		//dd($shippingAddress->city);

			//dd($this->getShippingDetails());
	        
	        // ### CreditCard
	        /*$card = Paypalpayment::creditCard();
	        $card->setType('visa')
            ->setNumber(4032038634486363)
            ->setExpireMonth(11)
            ->setExpireYear(2023)
            ->setCvv2(123)
            ->setFirstName('Abhishek')
            ->setLastName('Rana');

            dd($card);*/
	        
	        if(!empty($this->getCard())){
		        $fi = Paypalpayment::fundingInstrument();
		        $fi->setCreditCard($this->getCard()); 		//getting card details
		    }

	        $payer = Paypalpayment::payer();
	        $payer->setPaymentMethod($this->getPaymentMethod());
	        
	        if(!empty($this->getCard())){
	        	$payer->setFundingInstruments([$fi]);
	        }

	        $item = Paypalpayment::item();
	        $item->setName($this->getItemName())
            ->setDescription($this->getDescription())
            ->setCurrency($this->getCurrency())
            ->setQuantity($this->getQuantity())
            ->setTax($this->getTax())
            ->setPrice($this->getPrice());

	        $itemList = Paypalpayment::itemList();
	        $itemList->setItems([ $item ]);
	        
	        //if shipping details are provided
	        if(!empty($this->getShippingDetails())){
	        	$itemList->setShippingAddress($this->getShippingDetails());
	        }


	        /*$details = Paypalpayment::details();
	        $details//->setShipping("1.2")
            //->setTax("1.3")
            //total of items prices
            ->setSubtotal($this->getSubTotal());*/

	        //Payment Amount
	        $amount = Paypalpayment::amount();
	        $amount->setCurrency($this->getCurrency())
            ->setTotal($this->getTotal());
            
            //if details are provided
            if(!empty($this->getDetails())){
            	$amount->setDetails($this->getDetails());
            }

	        $transaction = Paypalpayment::transaction();
	        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->getDescription())
            ->setInvoiceNumber($this->getInvoiceNumber());

	        $payment = Paypalpayment::payment();

	        if($this->getPaymentMethod() === 'paypal'){
	        	$redirect_urls = new RedirectUrls();

		        $redirect_urls->setReturnUrl($this->getReturnUrl()) //Specify return URL
		        ->setCancelUrl($this->getCancelUrl());				//Specify cancel URL
	        }

	        $payment->setIntent($this->getIntent())
            ->setPayer($payer)
            ->setTransactions([$transaction]);

            if($this->getPaymentMethod() === 'paypal'){
            	$payment->setRedirectUrls($redirect_urls);
            }

	        try {
	            $payment->create($this->_api_context);
	            
	            //if want only URL of redirectiction
	            if( ($this->getPaymentMethod() === 'paypal') && ($this->extra_param['url']) ){
	            	return $payment->getApprovalLink();
	            }

	            //if card payment selected
	            if($this->getPaymentMethod() === 'credit_card'){
	            	return $payment;	
	            }

	            //dd($payment->toArray());
	            //return response()->json(["done" => "done payment card"], 200);

	        } catch (\PPConnectionException $ex) {
	            return response()->json(["error" => $ex->getMessage()], 400);
	        }

	        $approval_url = $payment->getApprovalLink();
	        header("Location: {$approval_url}");

	        foreach($payment->getLinks() as $link) {
	            if($link->getRel() == 'approval_url') {
	                $redirect_url = $link->getHref();
	                break;
	            }
	        }
		}
		catch(\Exception $e){
			dd($e);
		}
	}

	/*
	|------------------------------------------------------
	| For paying via credit_card, paypal(PayPal Express)
	| Created By- Shahrukh Anwar(16-10-2018)
	|------------------------------------------------------
	| Below are the example how you can set the objects
	|
	|	 //for shipping details 					: (All are optional fields in shipping)
    |    $shipping = \Payment::shipping();
    |    $shipping->setLine1("3909 Witmer Road")
    |    ->setLine2("Niagara Falls")
    |    ->setCity("Niagara Falls")
    |    ->setState("NY")
    |    ->setPostalCode("14305")
    |    ->setCountryCode("US")
    |    ->setPhone("716-298-1822")
    |    ->setRecipientName("Jhone");
	|	
	| 	 //if making payment via credit_card 			: (Required, when you select payment_method as credit_card)
	|	 $card = \Payment::card();
    |    $card->setType('visa')							: string  (Optional)
    |    ->setNumber(4032038634486363)					: longInt (Required)
    |    ->setExpireMonth(11)							: integer (Required)
    |    ->setExpireYear(2023)							: integer (Required)
    |    ->setCvv(123)									: integer (Required)
    |    ->setFirstName('Abhishek')						: string  (Optional)
    |    ->setLastName('Rana');							: string  (Optional)
	|	
	|	 //if user wants to add some extra details  	: (All fields are optional in details)
	|	 $details = \Payment::details();
    |    $details//>setShippingCharge(1.0)				: numeric (Optional)
    |    ->setShippingTax(1.0)							: numeric (Optional)
    |    ->setSubtotal(2.0);							: numeric (Optional)
	|
	|	 $pay = \Payment::setPaymentMethod('paypal')  	: string  (Required)(e.g paypal, credit_card)
    |    ->setTax(0.2)									: numeric (Optional)
    |    ->setPrice(1)                           		: numeric (Required)
    |    ->setQuantity(1)								: numeric (Optional)(Default : 1)
    |    ->setSubTotal(1)								: string  (Optional, If no tax provided)(Default : equals to total)
    |    ->setTotal(1.0)                           		: numeric (Required)
    |    ->setCurrency('USD')							: string  (Optional)(Default : 'USD')(e.g USD, AUD)
    |    ->setDescription('New description')			: string  (Optional)(Default : 'Default description.')
    |    ->setItemName('New Items for shopping') 		: string  (Required)
    |    ->setShippingDetails($shipping)				: object  (Optional)(If shipping initialised, then Required)
    |    ->setCard($card)								: object  (Optional)(If payment_method is credit_card, then Required)
    |    ->setReturnUrl(url('/payment?success=true'))	: string  (Optional)(if payment_method is paypal, then Required)(Must be a full and clean URl)
    |    ->setCancelUrl(url('/payment?success=false'))	: string  (Optional)(if payment_method is paypal, then Required)(Must be a full and clean URl)
    |    ->setExtraParam([		
    |        'url' => true 								: object  (Optional)(If user want only redirection-link of payment, then mark it true)
    |    ])
    |    ->setIntent('sale')							: string  (Optional)(Default : sale)(e.g. sale, order, authorize)
    |    ->setDetails($details)							: object  (Optional)(If details are initialised, then Required)
    |    ->pay();
	*/
	public function pay(){
		try{
	        $payer = Paypalpayment::payer();
	        $payer->setPaymentMethod($this->getPaymentMethod());

	        //if card_details provided
	        if(!empty($this->getCard())){
		        $fi = Paypalpayment::fundingInstrument();
		        $fi->setCreditCard($this->getCard()); 		//setting card details
		    }

		    //if card_details provided
	        if(!empty($this->getCard())){
	        	$payer->setFundingInstruments([$fi]);
	        }

	        /**
	         * Collecting item's details
	         * @var object
	         */
	        $item = Paypalpayment::item();
	        $item->setName($this->getItemName())
            ->setDescription($this->getDescription())
            ->setCurrency($this->getCurrency())
            ->setQuantity($this->getQuantity())
            ->setTax($this->getTax())
            ->setPrice($this->getPrice());

	        $itemList = Paypalpayment::itemList();
	        $itemList->setItems([ $item ]);
	        
	        //if shipping details are provided
	        if(!empty($this->getShippingDetails())){
	        	$itemList->setShippingAddress($this->getShippingDetails());
	        }

			//Payment Amount
	        $amount = Paypalpayment::amount();
	        $amount->setCurrency($this->getCurrency())
            ->setTotal($this->getTotal());
            
            //if details are provided
            if(!empty($this->getDetails())){
            	$amount->setDetails($this->getDetails());
            }

            /**
             * Transaction details
             * @var object
             */
	        $transaction = Paypalpayment::transaction();
	        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($this->getDescription())
            ->setInvoiceNumber($this->getInvoiceNumber());

	        $payment = Paypalpayment::payment();

	        /**
	         * If user have Non-US based account, then the redirect URLs must be given
	         * 
	         */
	        if($this->getPaymentMethod() === 'paypal'){ //in case of paypal-express checkout
	        	$redirect_urls = new RedirectUrls();

		        $redirect_urls->setReturnUrl($this->getReturnUrl()) //Specify return URL
		        ->setCancelUrl($this->getCancelUrl());				//Specify cancel URL
	        }

	        $payment->setPayer($payer)
	        ->setIntent($this->getIntent())            
            ->setTransactions([$transaction]);

            /**
             * In case of paypal-checkout
             * setting the redirect URLs
             */
            if($this->getPaymentMethod() === 'paypal'){
            	$payment->setRedirectUrls($redirect_urls);
            }

	        try {
	        	/**
	        	 * making final payment
	        	 */
	            $payment->create($this->_api_context);
	            
	            /**
	             * if user want only URL of redirectiction
	             * In case of implementation of API
	             * provide $this->extra_param[
	             * 		'url' => true
	             * ]
	             */
	            if( ($this->getPaymentMethod() === 'paypal') && ($this->extra_param['url']) ){
	            	/**
	            	 * returns the approval link of payment
	            	 */
	            	return $payment->getApprovalLink();
	            }

	            /**
	             *	If card payment selected
	             * 	@return object
	             */
	            if($this->getPaymentMethod() === 'credit_card'){
	            	return $payment;	
	            }	
	        } 
	        catch (\PPConnectionException $ex) {
	            return response()->json(["error" => $ex->getMessage()], 400);
	        }

	        /**
	         * redirecting the user to PayPal checkout
	         * @var string
	         */
	        $approval_url = $payment->getApprovalLink();
	        header("Location: {$approval_url}");
		}
		catch(\Exception $e){
			dd($e);
		}
	}
}