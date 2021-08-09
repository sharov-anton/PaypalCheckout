<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
require __DIR__  . '/vendor/autoload.php';
// Use below for direct download installation
// require __DIR__  . '/PayPal-PHP-SDK/autoload.php';

// After Step 1
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'ARSnAU31dVSvk8rBxp3Y1M7aRRf8K3uqn7dW7IX5oZyrXhXiVjsC1UZRGHMHNe4Y6-CungDfFd-yf9Pe',     // ClientID
        'EOetd-hYFcSFYZip1C_ObDt_ZGkjh-AEnMNs9sm4Zy4auUybVDVzAudma051nT6qyxqT4Gm5p5xcOpK2'      // ClientSecret
    )
);

// After Step 2
$payer = new \PayPal\Api\Payer();
$payer->setPaymentMethod('paypal');

$amount = new \PayPal\Api\Amount();
$amount->setTotal('1.00');
$amount->setCurrency('USD');

$payee = new \PayPal\Api\Payee();
$payee->setEmail('ghost@gmail.com');

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
    echo $payment;

    echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
}
catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception.
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}