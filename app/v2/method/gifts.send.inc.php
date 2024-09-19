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

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $giftId = isset($_POST['giftId']) ? $_POST['giftId'] : 0;
    $giftAnonymous = isset($_POST['giftAnonymous']) ? $_POST['giftAnonymous'] : 0;
    $giftTo = isset($_POST['giftTo']) ? $_POST['giftTo'] : 0;

    $message = isset($_POST['message']) ? $_POST['message'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $giftId = helper::clearInt($giftId);
    $giftAnonymous = helper::clearInt($giftAnonymous);
    $giftTo = helper::clearInt($giftTo);

    $message = helper::clearText($message);

    $message = preg_replace( "/[\r\n]+/", "<br>", $message); //replace all new lines to one new line
    $message = preg_replace('/\s+/', ' ', $message);        //replace all white spaces to one space

    $message = helper::escapeText($message);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gift = new gift($dbo);
    $gift->setRequestFrom($accountId);

    $giftInfo = $gift->db_info($giftId);

    if ($giftInfo['error'] === false && $giftInfo['removeAt'] == 0) {

        $account = new account($dbo, $accountId);

        $balance = $account->getBalance();

        if ($balance == $giftInfo['cost'] || $balance > $giftInfo['cost']) {

            $result = $gift->send($giftId, $giftTo, $message, $giftAnonymous);

            if ($result['error'] === false) {

                $account->setBalance($balance - $giftInfo['cost']);

                $result['balance'] = $balance - $giftInfo['cost'];

                $payments = new payments($dbo);
                $payments->setRequestFrom($accountId);
                $payments->create(PA_BUY_GIFT, PT_CREDITS, $giftInfo['cost']);
                unset($payments);
            }
        }
    }

    echo json_encode($result);
    exit;
}
