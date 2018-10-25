<?php

namespace Shahrukh\Test;

use Tests\TestCase;

/**
 * A Test class related to payment via PayPal
 */
class PayPalPaymentTest extends TestCase{
	/**
	 * A test case for making payment via PayPal
	 * @return boolean
	 */
	public function testPayment(){
		$pay = \Payment::setPaymentMethod('paypal') 
        ->setPrice(1)                         
        ->setQuantity(1)
        ->setTotal(1.0)                         
        ->setCurrency('USD')
        ->setItemName('New Items for creadit card')
        ->setReturnUrl(url('/shahrukh'))
        ->setCancelUrl(url('/shahrukh'))
        ->setIntent('sale')
        ->setExtraParam([
        	'url'	=> true
        ])
        ->pay();

        if(!empty($pay)){
			return $this->assertTrue(true);
        }

        return $this->assertFalse(false);
	}

	/**
	 * A test case for getting payment details of any payment
	 * @return boolean
	 */
	public function testInvoice(){
		$payment_id = "PAY-1JK339012V705711ALPC3QSI";

		$invoice = \Payment::invoice($payment_id);

		if(!empty($invoice)){
			return $this->assertTrue(true);
        }

        return $this->assertFalse(false);
	}

	/**
	 * A test case for making a refund of any transaction
	 * @return boolean
	 */
	public function testRefund(){
		$transaction_id = "3S7574554G079694D";

		$refund = \Payment::setAmount(0.11)
        ->setCurrency('USD')
        ->setReason('to refund the amount reason')
        ->refund($transaction_id);

		if(!empty($refund)){
			return $this->assertTrue(true);
        }

        return $this->assertFalse(false);
	}
}