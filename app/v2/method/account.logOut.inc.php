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

    $clientId = helper::clearInt($clientId);

    $accountId = helper::clearInt($accountId);

    $accessToken = helper::clearText($accessToken);
    $accessToken = helper::escapeText($accessToken);

    $result = array("error" => true);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $account = new account($dbo, $accountId);
    $account->setLastActive();
    $account->logout($accountId, $accessToken);

    $result = array("error" => false,
                    "error_code" => ERROR_SUCCESS);


    echo json_encode($result);
    exit;
}