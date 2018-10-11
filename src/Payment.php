<?php

namespace Shahrukh\Payments;

use Illuminate\Http\Request;

interface Payment{
	public function pay(Request $request);

	public function invoice(Request $request);
}