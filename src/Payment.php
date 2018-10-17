<?php

namespace Shahrukh\Payments;

use Illuminate\Http\Request;

/**
 |-------------------------------------
 |Contract for all the implemented payment gateways
 | Written by : Shahrukh Anwar (17/10/18)
 |-------------------------------------
 */
interface Payment{
	/**
	 * payment via gateways
	 */
	public function pay();

	/**
	 * invoice of payement made
	 */
	public function invoice();

	/**
	 * refund of transaction made
	 */
	public function refund();
}