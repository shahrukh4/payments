<?php

namespace Shahrukh\Payments;

use Illuminate\Http\Request;

interface Payment{
	public function pay();

	public function invoice(Request $request);
}