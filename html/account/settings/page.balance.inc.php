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
 */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    require_once '../sys/addons/vendor/autoload.php';

    \Stripe\Stripe::setVerifySslCerts(false);
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

    if (!empty($_POST)) {

        $packageId = isset($_POST['packageId']) ? $_POST['packageId'] : 0;

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        if ($packageId < count($payments_packages)) {

            $product = \Stripe\Product::create([
                'name' => $payments_packages[$packageId]['name'],
                'description' => $payments_packages[$packageId]['name']
            ]);

            $price = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => $payments_packages[$packageId]['amount'],
                'currency' => 'usd',
            ]);

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1
                ]],
                'mode' => 'payment',
                'success_url' => APP_URL.'/account/settings/balance?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => APP_URL.'/account/settings/balance',
            ]);

            $stripeSession = array($session);
            $sessId = ($stripeSession[0]['id']);

            $_SESSION['sessId'] = $sessId;
            $_SESSION['packageId'] = $packageId;

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "sessId" => $sessId
            );
        }

        echo json_encode($result);
        exit;
    }

    $payment = false;

    if (isset($_GET['session_id'])) {

        try {

            $response = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

            if (isset($_SESSION['sessId'])) {

                $paymentIntent = \Stripe\PaymentIntent::retrieve($response['payment_intent']);

                if ($_GET['session_id'] == $_SESSION['sessId']) {

                    $account = new account($dbo, auth::getCurrentUserId());
                    $account->setBalance($account->getBalance() + $payments_packages[$_SESSION['packageId']]['credits']);

                    $payments = new payments($dbo);
                    $payments->setRequestFrom(auth::getCurrentUserId());
                    $payments->create(PA_BUY_CREDITS, PT_CARD, $payments_packages[$_SESSION['packageId']]['credits'], $payments_packages[$_SESSION['packageId']]['amount']);
                    unset($payments);

                    unset($_SESSION['sessId']);
                    unset($_SESSION['packageId']);

                    $payment = true;
                }
            }

        } catch (\Stripe\Exception\ApiErrorException $e) {

            $payment = false;
        }
    }

    $account = new account($dbo, auth::getCurrentUserId());

    $page_id = "settings_balance";

    $css_files = array();
    $page_title = $LANG['page-balance']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="settings-page">

<?php

    include_once("../html/common/topbar.inc.php");
?>

<div class="wrap content-page">
    <div class="main-column row">

        <?php

            include_once("../html/common/sidenav.inc.php");
        ?>

        <?php

            include_once("../html/account/settings/settings_nav.inc.php");
        ?>

        <div class="row col sn-content" id="content">

            <div class="main-content">

                <div class="standard-page profile-content">

                    <h1 class="title"><?php echo $LANG['page-balance']; ?></h1>
                    <p><?php echo $LANG['page-balance-desc'] ?></p>
                    <p><?php echo $LANG['label-balance']; ?> <b><?php echo $account->getBalance(); ?> <?php echo $LANG['label-credits']; ?></b></p>

                    <?php

                        if (APP_DEMO) {

                            ?>

                            <div class="alert alert-warning">
                                <p class="m-0">
                                    <span><b>For testing:</b></span>
                                    <span class="d-block">Use card number for test: 4242424242424242</span>
                                    <span class="d-block">CVC and other data: any</span>
                                </p>
                            </div>

                            <?php
                        }

                        if ($payment) {

                            ?>

                            <div class="alert alert-success">
                                <b><?php echo $LANG['label-thanks']; ?></b>
                                <br>
                                <?php echo $LANG['label-payments-success_added']; ?>
                            </div>

                            <?php
                        }
                    ?>

                    <header class="top-banner p-0">
                        <div class="info">
                            <h1><?php echo $LANG['action-buy-credits']; ?></h1>
                        </div>
                    </header>

                    <div class="row col-12 px-0">

                    <?php

                        foreach ($payments_packages as $package) {

                            ?>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2 p-0">

                                <a class="stripe-buy-button button blue d-block mx-1" onclick="Payments.new(<?php echo $package['id']; ?>); return false;" href="javascript: void(0)"><i class="icofont icofont-stripe-alt"></i> <?php echo $package['name']." ".$LANG['label-payments-for']." $".$package['amount'] / 100; ?></a>
                            </div>

                            <?php
                        }
                    ?>
                    </div>

                    <header class="top-banner px-0 pb-0">
                        <div class="info">
                            <h1><?php echo $LANG['label-payments-history']; ?></h1>
                        </div>
                    </header>

                    <div class="listview">
                        <table class="bordered data-tables responsive-table">
                            <tbody>
                            <tr class="listview-header">
                                <th class="text-center"><?php echo $LANG['label-payments-credits']; ?></th>
                                <th class="text-center"><?php echo $LANG['label-payments-amount']; ?></th>
                                <th class="text-right"><?php echo $LANG['label-payments-description']; ?></th>
                                <th class="text-right"><?php echo $LANG['label-payments-date']; ?></th>
                            </tr>

                            <?php

                            $payments = new payments($dbo);
                            $payments->setRequestFrom(auth::getCurrentUserId());

                            $result = $payments->get(0, 30);

                            if (count($result['items']) == 0) {

                                ?>

                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="card information-banner border-0 shadow-none m-0">
                                            <div class="card-header border-0">
                                                <div class="card-body">
                                                    <h5 class="m-0"><?php echo $LANG['label-empty-list']; ?></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php

                            } else {

                                foreach ($result['items'] as $key => $value) {

                                    ?>

                                    <tr>
                                        <td class="text-center">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                case PA_BUY_REGISTRATION_BONUS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                case PA_BUY_OTP_VERIFICATION: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                case PA_BUY_REFERRAL_BONUS: {

                                                    echo "<span class=\"green\">+".$value['credits']."</span>";
                                                    break;
                                                }

                                                default: {

                                                    echo "<span class=\"red\">-".$value['credits']."</span>";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    if ($value['amount'] > 0) {

                                                        echo "$".$value['amount'] / 100;
                                                        break;
                                                    }
                                                }

                                                default: {

                                                    echo "";
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right" style="word-break: break-word">
                                            <?php
                                            switch ($value['paymentAction']) {

                                                case PA_BUY_CREDITS: {

                                                    switch ($value['paymentType']) {

                                                        case PT_CARD: {

                                                            echo $LANG['label-payments-credits-stripe'];
                                                            break;
                                                        }

                                                        case PT_GOOGLE_PURCHASE: {

                                                            echo $LANG['label-payments-credits-android'];
                                                            break;
                                                        }

                                                        case PT_APPLE_PURCHASE: {

                                                            echo $LANG['label-payments-credits-ios'];
                                                            break;
                                                        }

                                                        case PT_ADMOB_REWARDED_ADS: {

                                                            echo $LANG['label-payments-credits-admob'];
                                                            break;
                                                        }
                                                    }

                                                    break;
                                                }

                                                case PA_BUY_GIFT: {

                                                    echo $LANG['label-payments-send-gift'];
                                                    break;
                                                }

                                                case PA_BUY_VERIFIED_BADGE: {

                                                    echo $LANG['label-payments-verified-badge'];
                                                    break;
                                                }

                                                case PA_BUY_GHOST_MODE: {

                                                    echo $LANG['label-payments-ghost-mode'];
                                                    break;
                                                }

                                                case PA_BUY_DISABLE_ADS: {

                                                    echo $LANG['label-payments-off-admob'];
                                                    break;
                                                }

                                                case PA_BUY_REGISTRATION_BONUS: {

                                                    echo $LANG['label-payments-registration-bonus'];
                                                    break;
                                                }

                                                case PA_BUY_OTP_VERIFICATION: {

                                                    echo $LANG['label-payments-otp-verification-bonus'];
                                                    break;
                                                }

                                                case PA_BUY_REFERRAL_BONUS: {

                                                    echo $LANG['label-payments-referral-bonus'];
                                                    break;
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td class="text-right"><?php echo $value['date']; ?></td>
                                    </tr>

                                    <?php
                                }
                            }

                            ?>

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>


    <script src="https://js.stripe.com/v3/"></script>

<?php

include_once("../html/common/footer.inc.php");
?>

<script>

    //set your publishable key
    var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

    window.Payments || ( window.Payments = {} );

    Payments.new = function (package_id) {

        $.ajax({
            type: 'POST',
            url: '/account/settings/balance',
            data: "packageId=" + package_id,
            dataType: 'json',
            timeout: 30000,
            success: function(response){

                if (response.hasOwnProperty('error')) {

                    if (response.error === false) {

                        if (response.hasOwnProperty('sessId')) {

                            stripe.redirectToCheckout({
                                // Make the id field from the Checkout Session creation API response
                                // available to this file, so you can provide it as parameter here
                                // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
                                sessionId: response.sessId

                            }).then(function (result) {
                                // If `redirectToCheckout` fails due to a browser or network
                                // error, display the localized error message to your customer
                                // using `result.error.message`.
                            });
                        }
                    }
                }
            },
            error: function(xhr, type){


            }
        });
    };

    $('.btn-stripe').click(function() {

        Payments.new(1);
    });

</script>

</body>
</html>