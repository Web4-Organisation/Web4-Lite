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

    $accountId = auth::getCurrentUserId();
    $postId = helper::clearInt($request[2]);

    if (!empty($_POST)) {

        $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';
        $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';

        $commentText = isset($_POST['commentText']) ? $_POST['commentText'] : '';

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 2; // ITEM_TYPE_POST = 2
        $replyToUserId = isset($_POST['replyToUserId']) ? $_POST['replyToUserId'] : 0;

        $itemId = helper::clearInt($itemId);
        $itemType = helper::clearInt($itemType);
        $replyToUserId = helper::clearInt($replyToUserId);

        $commentText = helper::clearText($commentText);
        $commentText = helper::escapeText($commentText);

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

            ob_start();

            draw::comment($result['comment'], $LANG);

            $result['html'] = ob_get_clean();
        }

        echo json_encode($result);
        exit;
    }
