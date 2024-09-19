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

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $credits = isset($_POST['credits']) ? $_POST['credits'] : 0;
    $upgradeType = isset($_POST['upgradeType']) ? $_POST['upgradeType'] : 0;

    $credits = helper::clearInt($credits);
    $upgradeType = helper::clearInt($upgradeType);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $account = new account($dbo, $accountId);

    $balance = $account->getBalance();

    if ($balance >= $credits) {

        switch ($upgradeType) {

            case PA_BUY_VERIFIED_BADGE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setVerify(1);

                break;
            }

            case PA_BUY_GHOST_MODE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setGhost(1);

                break;
            }

            case PA_BUY_DISABLE_ADS: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setAdmob(1);

                break;
            }

            default: {

                break;
            }
        }

        if (!$result['error']) {

            $payments = new payments($dbo);
            $payments->setRequestFrom($accountId);
            $payments->create($upgradeType, PT_CREDITS, $credits);
            unset($payments);
        }
    }

    echo json_encode($result);
    exit;
}
