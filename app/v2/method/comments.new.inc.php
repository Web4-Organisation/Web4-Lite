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

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
    $commentText = isset($_POST['commentText']) ? $_POST['commentText'] : '';

    $replyToUserId = isset($_POST['replyToUserId']) ? $_POST['replyToUserId'] : 0;

    $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);

    $commentText = helper::clearText($commentText);

    $commentText = preg_replace( "/[\r\n]+/", " ", $commentText); //replace all new lines to one new line
    $commentText  = preg_replace('/\s+/', ' ', $commentText);        //replace all white spaces to one space

    $commentText = helper::escapeText($commentText);

    $replyToUserId = helper::clearInt($replyToUserId);
    $itemType = helper::clearInt($itemType);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if (strlen($commentText) != 0) {

        if ($itemType == ITEM_TYPE_POST) {

            $item = new post($dbo);
            $item->setRequestFrom($accountId);

            $itemInfo = $item->info($itemId);

        } else {

            $item = new gallery($dbo);
            $item->setRequestFrom($accountId);

            $itemInfo = $item->info($itemId);
        }

        $blacklist = new blacklist($dbo);
        $blacklist->setRequestFrom($itemInfo['fromUserId']);

        if ($blacklist->isExists($accountId)) {

            echo json_encode($result);
            exit;
        }

        if ($itemType == ITEM_TYPE_POST && $itemInfo['owner']['allowComments'] == 0) {

            echo json_encode($result);
            exit;
        }

        if ($itemType == ITEM_TYPE_GALLERY && $itemInfo['owner']['allowGalleryComments'] == 0) {

            echo json_encode($result);
            exit;
        }

        $comments = new comments($dbo, $itemType);
        $comments->setRequestFrom($accountId);

        $notifyId = 0;

        $result = $comments->create($itemId, $commentText, $notifyId, $replyToUserId);
    }

    echo json_encode($result);
    exit;
}
