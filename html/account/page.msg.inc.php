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

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $chat_id = 0;
    $user_id = 0;

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $chat_id = isset($_POST['chat_id']) ? $_POST['chat_id'] : 0;
        $message_id = isset($_POST['message_id']) ? $_POST['message_id'] : 0;

        $message_text = isset($_POST['message_text']) ? $_POST['message_text'] : "";
        $message_img = isset($_POST['message_img']) ? $_POST['message_img'] : "";

        $stickerId = isset($_POST['stickerId']) ? $_POST['stickerId'] : 0;
        $stickerImgUrl = isset($_POST['stickerImgUrl']) ? $_POST['stickerImgUrl'] : "";

        $user_id = helper::clearInt($user_id);
        $chat_id = helper::clearInt($chat_id);
        $message_id = helper::clearInt($message_id);

        $message_text = helper::clearText($message_text);

        $message_text = preg_replace( "/[\r\n]+/", "<br>", $message_text); //replace all new lines to one new line
        $message_text  = preg_replace('/\s+/', ' ', $message_text);        //replace all white spaces to one space

        $message_text = helper::escapeText($message_text);

        $message_img = helper::clearText($message_img);
        $message_img = helper::escapeText($message_img);

        $stickerId = helper::clearInt($stickerId);

        $stickerImgUrl = helper::clearText($stickerImgUrl);
        $stickerImgUrl = helper::escapeText($stickerImgUrl);

//        if (strpos($message_img, APP_HOST) === false) {
//
//            $message_img = "";
//        }

        $result = array("error" => true,
                        "error_code" => ERROR_UNKNOWN);

        if ($user_id == auth::getCurrentUserId()) {

            echo json_encode($result);
            exit;
        }

        $profile = new profile($dbo, $user_id);
        $profile->setRequestFrom(auth::getCurrentUserId());

        $profileInfo = $profile->get();

        if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

            echo json_encode($result);
            exit;
        }

        if ($profileInfo['allowMessages'] == 0 && $profileInfo['friend'] === false) {

            echo json_encode($result);
            exit;
        }

        $blacklist = new blacklist($dbo);
        $blacklist->setRequestFrom($user_id);

        if (!$blacklist->isExists(auth::getCurrentUserId())) {

            $messages = new msg($dbo);
            $messages->setRequestFrom(auth::getCurrentUserId());

            $result = $messages->create($user_id, $chat_id, $message_text, $message_img, 0, 0, 0, $stickerId, $stickerImgUrl);

            if (!$result['error']) {

                $messages = new messages($dbo);
                $messages->setRequestFrom(auth::getCurrentUserId());

                $result = $messages->getNextMessages($result['chatId'], $message_id);

                ob_start();

                foreach ($result['messages'] as $key => $value) {

                    draw::messageItem($value, $LANG, $helper);
                }

                $result['html'] = ob_get_clean();
                $result['items_all'] = $messages->messagesCountByChat($chat_id);
            }
        }

        echo json_encode($result);
        exit;
    }
