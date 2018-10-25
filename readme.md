# Payments

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is simple and easy to use Laravel package for using multiple payment gateways in a single place. Now you don't need install multiple packages for multiple payment gateways.
Here we provide 2 of most widely used Payment Gateways (PayPal, Stripe) having easy to use methods such as `pay()`, `invoice()`, `refund()` etc.


## Installation

Via Composer

``` bash
$ composer require shahrukh/payments ">=1.2"
```

or you can just add it in your composer.json

```
"require": {
    "shahrukh/payments": ">=1.2"
}
```

Next, run `composer update`.


## Usage

Add the ServiceProvider to your `config/app.php`

a). Add in `providers` array,

'providers' => array(
    // ...

    Shahrukh\Payments\PaymentsServiceProvider::class,
)

b). Add alias in `alias` array,

'aliases' => array(
    // ...

    'Payment'   => Shahrukh\Payments\Facades\Payment::class,
)

c). Finally publish the package configurations by running the following command in `Terminal`

`php artisan vendor:publish --provider="Shahrukh\Payments\PaymentsServiceProvider"`



## Important instructions

a). search for `vendor/paypal/rest-api-sdk-php/lib/PayPal/Common/PayPalModel.php` at line 173 

change `else if(sizeof($v) <= 0 && is_array($v))` to `else if(is_array($v) && sizeof($v) <= 0)`

b). Add credentials to your .env

```
STRIPE_KEY='YOUR_CREDENTIALS'
STRIPE_SECRET='YOUR_CREDENTIALS'

PAYPAL_MODE='YOUR_CREDENTIALS'
PAYMENT_TYPE='YOUR_TYPE' 				//paypal, stripe (default paypal)
PAYPAL_CLIENT_ID='YOUR_CREDENTIALS'
PAYPAL_CLIENT_SECRET='YOUR_CREDENTIALS'
```

## Examples

1). For PayPal (if `PAYMENT_TYPE` == paypal in .env)
	a). For payment through PayPal

	```
	//for shipping details 						(Optional)
	$shipping = \Payment::shipping();
	$shipping->setLine1("3909 Witmer Road")
	->setLine2("Niagara Falls")
	->setCity("Niagara Falls")
	->setState("NY")
	->setPostalCode("14305")
	->setCountryCode("US")
	->setPhone("716-298-1822")
	->setRecipientName("Jhone");
		
	//if making payment via credit_card        	(Required if payment method is set as `credit_card` otherwise not required)
	$card = \Payment::card();
	$card->setType('visa')						
	->setNumber(4032038634486363)			//required		
	->setExpireMonth(11)					//required	
	->setExpireYear(2023)					//required		
	->setCvv(123)							//required		
	->setFirstName('Abhishek')					
	->setLastName('Rana');						
		
	//if user wants to add some extra details  	(Optional)
	$details = \Payment::details();
	$details//>setShippingCharge(1.0)				
	->setShippingTax(1.0)						(sub total must be a sumation of all taxes and price)	
	->setSubtotal(2.0);

	//making payment
	$pay = \Payment::setPaymentMethod('paypal')  (Required)
	->setTax(0.2)									
	->setPrice(1) 								 	//required                          		
	->setQuantity(1)							 	//required							
	->setSubTotal(1)															
	->setTotal(1.0) 							 	//required                          	
	->setCurrency('USD')						 	(Default USD, otherwise can be provided e.g. USD, AUD etc.)						
	->setDescription('New description')			
	->setItemName('New Items for shopping') 	 	//required		
	->setShippingDetails($shipping)				 	//required only if shipping details are set as above, otherwise optional		
	->setCard($card)							 	//required only if payment method is set as `credit_card` otherwise not required
	->setReturnUrl(url('/payment?success=true')) 	//required if payment method is set as `paypal` (must be a full fletched URl)
	->setCancelUrl(url('/payment?success=false'))	//required if payment method is set as `paypal` (must be a full fletched URl)
	->setExtraParam([		
		'url' => true 								//required if you want only redirect URL for PayaPal (useful in cases when you are making API or something). if it is not set then for payment it will redirect to paypal payment screen
	])
	->setIntent('sale')								(Optional)(Default : sale)(e.g. sale, order, authorize)					
	->setDetails($details)						
	->pay();										//required

	```

	b). For the invoice or payment details of any Payment through PayPal

	```
	$payment_id = 'PAY-1JK339012V705711ALPC3QSI';  	: Required(fetch this id, when you are making transaction and store it safe)
	$payment_details = \Payment::invoice($payment_id);
	```

	c). For refund of any transaction through PayPal

	```
	$transaction_id = '3S7574554G079694D'; : Required(fetch this id, when you are making transaction and store)
	$refund = \Payment::setAmount(0.11)
	->setCurrency('USD')
	->setReason('to refund the amount reason')
	->refund($transaction_id);  
	```


2). For Stripe (if `PAYMENT_TYPE` == stripe in .env)
	a). For payment through Stripe

	```
	//initialising the card object
	$card = Payment::card();
	$card->setNumber(4242424242424242) 	: integer (Required)
	->setExpireYear(2020) 				: integer (Required)
	->setExpireMonth(06) 				: integer (Required)
	->setCvv(314);						: integer (Required)
		
	//making payments
	$pay = \Payment::setAmount(1) 		: integer (Required)
	->setDescription('for whatever') 	: string  (Optional)
	->setCard($card)					: object  (Required)
	->pay();							: required
	```

	b). To get invoice or payment details

	```
	$payment_id = 'ch_1DK0jWB108n83JtHxlqSCqrK';  : Required(fetch this id, when you are making transaction and store it safe)
	
	$payment_details = \Payment::invoice($payment_id);
	```

	c). To get refund of any transaction

	```
	$transaction_id = 'ch_1DK0j1B108n83JtH236d8aH5'; : Required(fetch this id, when you are making transaction and store)
	$refund = \Payment::setAmount(1)
	->setReason('duplicate')
	->refund($transaction_id); 
	```


## Testing

``` bash
$ composer test
```

## Environment

a). Just change the environment from `env` for PayPal *sanbox* for testing and *live* for production, in your .env 

```
PAYPAL_MODE = sandbox/live
```

b). For Stripe just change the credentials of live and everything will be fine.


## Security

If you discover any security related issues, please email shahrukhanwar2013@gmail.com instead of using the issue tracker.

## Credits

- [Shahrukh Anwar][https://stackoverflow.com/users/8473036/shahrukh-anwar]	

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/shahrukh/payments.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/shahrukh/payments.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/shahrukh/payments/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/shahrukh/payments
[link-downloads]: https://packagist.org/packages/shahrukh/payments
[link-travis]: https://travis-ci.org/shahrukh/payments
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/shahrukh
[link-contributors]: ../../contributors]