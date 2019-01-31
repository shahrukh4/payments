<?php

namespace Shahrukh\Test;

use Tests\TestCase;

/**
 * A Test class related to payment via Stripe
 */
class StripePaymentTest extends TestCase{
	/**
	 * A test case for making payment via Stripe
	 * @return boolean
	 */
	public function testPayment(){
		$card = \Payment::card();
        $card->setNumber(4242424242424242)
        ->setExpireYear(2020)
        ->setExpireMonth(06)
        ->setCvv(314);

        $pay = \Payment::setAmount(1.20)
        ->setDescription('duplicate')
        ->setCard($card)
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
		$payment_id = "ch_1DK0jWB108n83JtHxlqSCqrK";
   		
   		$invoice = \Payment::invoice($payment_id);
		
		if(!empty($invoice)){
			return $this->assertTrue(true);
        }

        return $this->assertFalse(false);
	}

	/**
	 * A test case for making a refund of any tarnasaction
	 * @return boolean
	 */
	public function testRefund(){
		$transaction_id = 'ch_1DK0j1B108n83JtH236d8aH5';

		$refund = \Payment::setAmount(1)
		->setReason('duplicate')
		->refund($transaction_id); 

		if(!empty($refund)){
			return $this->assertTrue(true);
        }

        return $this->assertFalse(false);
	}
}