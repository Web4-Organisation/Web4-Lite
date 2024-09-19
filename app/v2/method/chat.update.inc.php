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

    $chatFromUserId = isset($_POST['chatFromUserId']) ? $_POST['chatFromUserId'] : 0;
    $chatToUserId = isset($_POST['chatToUserId']) ? $_POST['chatToUserId'] : 0;

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $chatFromUserId = helper::clearInt($chatFromUserId);
    $chatToUserId = helper::clearInt($chatToUserId);

    $chatId = helper::clearInt($chatId);

    $result = array("error" => false,
                    "error_code" => ERROR_UNKNOWN);

//    $auth = new auth($dbo);
//
//    if (!$auth->authorize($accountId, $accessToken)) {
//
//        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
//    }

    $msg = new msg($dbo);
    $msg->setRequestFrom($accountId);

    $profileId = $chatFromUserId;

    if ($profileId == $accountId) {

        $msg->setChatLastView_FromId($chatId);

        $msg->setSeen($chatId, $chatToUserId);

        // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
        // GCM_NOTIFY_SEEN= 15
        // GCM_NOTIFY_TYPING= 16
        // GCM_NOTIFY_TYPING_START = 27
        // GCM_NOTIFY_TYPING_END = 28

        $fcm = new fcm($dbo);
        $fcm->setRequestFrom($chatFromUserId);
        $fcm->setRequestTo($chatToUserId);
        $fcm->setType(15);
        $fcm->setTitle("Seen");
        $fcm->setItemId($chatId);
        $fcm->prepare();
        $fcm->send();
        unset($fcm);

    } else {

        $msg->setChatLastView_ToId($chatId);

        $msg->setSeen($chatId, $chatFromUserId);

        // GCM_MESSAGE_ONLY_FOR_PERSONAL_USER = 2
        // GCM_NOTIFY_SEEN= 15
        // GCM_NOTIFY_TYPING= 16
        // GCM_NOTIFY_TYPING_START = 27
        // GCM_NOTIFY_TYPING_END = 28

        $fcm = new fcm($dbo);
        $fcm->setRequestFrom($chatToUserId);
        $fcm->setRequestTo($chatFromUserId);
        $fcm->setType(15);
        $fcm->setTitle("Seen");
        $fcm->setItemId($chatId);
        $fcm->prepare();
        $fcm->send();
        unset($fcm);
    }

    echo json_encode($result);
    exit;
}
