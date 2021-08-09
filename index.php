<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation

$send_amount = isset( $_POST['amount'] ) ? $_POST['amount'] : '1.0';
$send_target = isset( $_POST['target'] ) ? $_POST['target'] : 'sharov.frl@gmail.com';

require __DIR__  . '/vendor/autoload.php';
// Use below for direct download installation
// require __DIR__  . '/PayPal-PHP-SDK/autoload.php';

// After Step 1
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'Ab1f-GPgmL_4xNqaMfyScfTqysXQr40bsc0XAV0jIej_UOYr7FhEDsFbjB9Oo2AoTk2hmx2dGeqb_ipk',     // ClientID
        'EDlJeF6uTh7cD95w4nVDr3Jyrfz8rZO0urQdxVpD84ivjnp0xdnTFHMcjRrsnSRbDB4jTgarckqBnYa8'      // ClientSecret
    )
);

// Step 2.1 : Between Step 2 and Step 3
$apiContext->setConfig(
    array(
      'mode' => 'live',
      'log.LogEnabled' => true,
      'log.FileName' => 'PayPal.log',
      'log.LogLevel' => 'DEBUG'
    )
);

// After Step 2
$payer = new \PayPal\Api\Payer();
$payer->setPaymentMethod('paypal');

$amount = new \PayPal\Api\Amount();
$amount->setTotal($send_amount);
$amount->setCurrency('USD');

$payee = new \PayPal\Api\Payee();
$payee->setEmail($send_target);

$transaction = new \PayPal\Api\Transaction();
$transaction->setAmount($amount);
$transaction->setpayee($payee);

$redirectUrls = new \PayPal\Api\RedirectUrls();
$redirectUrls->setReturnUrl("https://example.com/your_redirect_url.html")
    ->setCancelUrl("https://example.com/your_cancel_url.html");

$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions(array($transaction))
    ->setRedirectUrls($redirectUrls);

// After Step 3
try {
    $payment->create($apiContext);
    
    $resopnse = array( "url" => $payment->getApprovalLink() );
    echo json_encode($resopnse);
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception.
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}