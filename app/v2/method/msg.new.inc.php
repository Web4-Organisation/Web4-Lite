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

    $profileId = isset($_POST['profileId']) ? $_POST['profileId'] : 0;

    $chatFromUserId = isset($_POST['chatFromUserId']) ? $_POST['chatFromUserId'] : 0;
    $chatToUserId = isset($_POST['chatToUserId']) ? $_POST['chatToUserId'] : 0;

    $chatId = isset($_POST['chatId']) ? $_POST['chatId'] : 0;
    $messageText = isset($_POST['messageText']) ? $_POST['messageText'] : "";
    $messageImg = isset($_POST['messageImg']) ? $_POST['messageImg'] : "";

    $listId = isset($_POST['listId']) ? $_POST['listId'] : 0;

    $stickerId = isset($_POST['stickerId']) ? $_POST['stickerId'] : 0;
    $stickerImgUrl = isset($_POST['stickerImgUrl']) ? $_POST['stickerImgUrl'] : "";

    $stickerId = helper::clearInt($stickerId);

    $stickerImgUrl = helper::clearText($stickerImgUrl);
    $stickerImgUrl = helper::escapeText($stickerImgUrl);

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $profileId = helper::clearInt($profileId);

    $chatFromUserId = helper::clearInt($chatFromUserId);
    $chatToUserId = helper::clearInt($chatToUserId);

    $chatId = helper::clearInt($chatId);

    $listId = helper::clearInt($listId);

    $messageText = helper::clearText($messageText);

    $messageText = preg_replace( "/[\r\n]+/", "<br>", $messageText); //replace all new lines to one new line
    $messageText  = preg_replace('/\s+/', ' ', $messageText);        //replace all white spaces to one space

    $messageText = helper::escapeText($messageText);

    $messageImg = helper::clearText($messageImg);
    $messageImg = helper::escapeText($messageImg);

    if (strpos($messageImg, APP_HOST) === false) {

        $messageImg = "";
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if ($profileId == $accountId) {

        echo json_encode($result);
        exit;
    }

    $profile = new profile($dbo, $profileId);
    $profile->setRequestFrom($accountId);

    $profileInfo = $profile->getShort();

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        echo json_encode($result);
        exit;
    }

    if ($profileInfo['allowMessages'] == 0) {

        if (!$profile->is_friend_exists($accountId)) {

            echo json_encode($result);
            exit;
        }
    }

    if (!$profileInfo['inBlackList']) {

        $messages = new msg($dbo);
        $messages->setRequestFrom($accountId);

        $result = $messages->create($profileId, $chatId, $messageText, $messageImg, $chatFromUserId, $chatToUserId, $listId, $stickerId, $stickerImgUrl);
    }

    echo json_encode($result);
    exit;
}
