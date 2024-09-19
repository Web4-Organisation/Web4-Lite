<?php

/*!
 * Linkspreed UG
 * Web4 Lite published under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License. (BY-NC-SA 4.0)
 *
 * https://linkspreed.com
 * https://web4.one
 *
 * Copyright (c) 2024 Linkspreed UG (hello@linkspreed.com)
 * Copyright (c) 2024 Marc Herdina (marc.herdina@linkspreed.com)
 * 
 * Web4 Lite (c) 2024 by Linkspreed UG & Marc Herdina is licensed under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/.
 */;

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

require_once '../sys/addons/vendor/autoload.php';

$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);

// Use an existing Customer ID if this is a returning customer.
$customer = $stripe->customers->create();
$ephemeralKey = $stripe->ephemeralKeys->create([
    'customer' => $customer->id,
], [
    'stripe_version' => '2022-08-01',
]);

$paymentIntent = $stripe->paymentIntents->create([

    'amount' => $payments_packages[2]['amount'],
    'currency' => 'usd',
    'customer' => $customer->id,
    // In the latest version of the API, specifying the `automatic_payment_methods` parameter is optional because Stripe enables its functionality by default.
    'automatic_payment_methods' => [

        'enabled' => 'true',
    ],
]);

echo json_encode(
    [
        'paymentIntent' => $paymentIntent->client_secret,
        'ephemeralKey' => $ephemeralKey->secret,
        'customer' => $customer->id,
        'publishableKey' => STRIPE_PUBLISHABLE_KEY
    ]
);

http_response_code(200);
