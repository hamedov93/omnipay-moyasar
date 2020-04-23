## Moyasar gateway extension for Omnipay package
This package makes it easy to process payments using moyasar gateway for credit card, sadad, apple pay and mada payments.
For more information about different payment methods refer to Moyasar documentation.
[https://moyasar.com/docs/api](https://moyasar.com/docs/api)
## Installation
```composer require hamedov/omnipay-moyasar```

## Usage
### Initialize the gateway with api key
You can use test or live api key here.
```
use Omnipay\Omnipay;

$moyasar = Omnipay::create('Moyasar');

$moyasar->setApiKey('You test or live api key');
```
### Initiate new payment
```
$request = $moyasar->purchase([
  'amount' => 50,
  'currency' => 'SAR',
  'description' => 'Payment description',
  'callbackUrl' => 'http://example.com/payments',
  'source' => [
    'type' => 'creditcard',
    'name' => 'Mohamed Hamed',
    'number' => '4111111111111111',
    'cvc' => 785,
    'month' => 11,
    'year' => 2021,
    '3ds' => true,
  ],
]);

$payment = $request->send();
```

### Initiate new manual payment
To only authorize the payment and let it be captured manually by the merchant, use the authorize method.
If the payment is not captured within 7 days, the authorization is canceled and funds released.
```
$request = $moyasar->authorize([
  'amount' => 50,
  'currency' => 'SAR',
  'description' => 'Payment description',
  'callbackUrl' => 'http://example.com/payments',
  'source' => [
    'type' => 'creditcard',
    'name' => 'Mohamed Hamed',
    'number' => '4111111111111111',
    'cvc' => 785,
    'month' => 11,
    'year' => 2021,
    '3ds' => true,
  ],
]);

$payment = $request->send();
```

### Handle payment response
You will need to check the payment status to decide whether there will be a redirect to complete payment or not.
You will also need to save reference to the payment for future reference such as refunding and voiding payments.
```
if ($payment->isSuccessful())
{
  // Payment is successful no redirect requird
  $transactionId = $payment->getTransactionReference();
}
elseif ($payment->isAuthorized())
{
  // Payment is authorized and waiting to be capture by the merchant
  $transactionId = $payment->getTransactionReference();
}
elseif ($payment->isFailed())
{
  // Payment failed
  $error = $payment->getMessage();
  
}
```
For Redirect response You can call the `redirect()` method to redirect the user automatically to complete payment, or you can get the
transaction url using `$payment->getRedirectUrl()` and handle the redirect yourself or return it in a json api response.
```
if ($payment->isRedirect())
{
  $payment->redirect();
}
```
After the user is redirected back to your website you can fetch the payment using the id provided in the url to check payment status
and process it as required. You will get the same response format as returned in the payment request.
```
$moyasar = Omnipay::create('Moyasar');
$moyasar->setApiKey('You test or live api key');

$payment = $moyasar->fetch($_GET['id'])->send();
if ($payment->isSuccessful())
{
  // Payment successful
  $transactionId = $payment->getTransactionReference();
}
elseif ($payment->isFailed())
{
  // Payment failed
  $error = $payment->getMessage();  
}
```
### Capture a previously authorized payment
You can provide the amount to be captured, it can be less than or equal to original amount provided during payment creation.
The remaining amount will be automatically released. The request will fail if the payment has any status other than `authorized`
```
$moyasar = Omnipay::create('Moyasar');
$moyasar->setApiKey('You test or live api key');

$payment = $moyasar->capture([
  'id' => $transactionId,
  'amount' => $amount, // Optional, defaults to total amount
])->send();

if ($payment->isCaptured())
{
  // Success
  
}
elseif ($payment->hasError())
{
  $error = $payment->getMessage();
}
```
### Void a payment
- You can cancel/void an authorized payment which has not been captured yet.
- You can cancel/void captured or successful payments which have not yet settled in the customers bank account.
  Or you will need to send a refund request.
```
$moyasar = Omnipay::create('Moyasar');
$moyasar->setApiKey('You test or live api key');

$payment = $moyasar->void([
  'id' => $transactionId,
])->send();

if ($payment->isVoided())
{
  // Success
  
}
elseif ($payment->hasError())
{
  $error = $payment->getMessage();
  // You can fallback to the refund request here
}
```
### Refund a payment
```
$moyasar = Omnipay::create('Moyasar');
$moyasar->setApiKey('You test or live api key');

$payment = $moyasar->refund([
  'id' => $transactionId,
  'amount' => $amount, // Optional
])->send();

if ($payment->isRefunded())
{
  // Success
  
}
elseif ($payment->hasError())
{
  // Payment cannot be refunded
  $error = $payment->getMessage();
}
```
## TODO
- Add update payment request.
- Add list payments request.

## License
Released under the Mit license, see [LICENSE](https://github.com/hamedov93/omnipay-moyasar/blob/master/LICENSE)
