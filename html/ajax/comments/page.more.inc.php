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

        $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';
        $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $commentId = isset($_POST['commentId']) ? $_POST['commentId'] : 0;
        $itemType = isset($_POST['itemType']) ? $_POST['itemType'] : 2; // ITEM_TYPE_POST = 2

        $itemId = helper::clearInt($itemId);
        $commentId = helper::clearInt($commentId);
        $itemType = helper::clearInt($itemType);

        $result = array(
            "error" => true,
            "error_code" => ERROR_UNKNOWN
        );

        $auth = new auth($dbo);

//        if (!$auth->authorize($accountId, $accessToken)) {
//
//            api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
//        }

        $comments = new comments($dbo, $itemType);
        $comments->setLanguage($LANG['lang-code']);
        $comments->setRequestFrom($accountId);

        $result = $comments->get($itemId, $commentId);

        $result['comments'] = array_reverse($result['comments'], false);

        ob_start();

        foreach ($result['comments'] as $key => $value) {

            draw::comment($value, $LANG);
        }

        $result['html'] = ob_get_clean();

        echo json_encode($result);
        exit;
    }