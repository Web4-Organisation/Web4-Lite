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

    $comment = isset($_POST['comment']) ? $_POST['comment'] : "";
    $originImgUrl = isset($_POST['originImgUrl']) ? $_POST['originImgUrl'] : "";
    $previewImgUrl = isset($_POST['previewImgUrl']) ? $_POST['previewImgUrl'] : "";
    $imgUrl = isset($_POST['imgUrl']) ? $_POST['imgUrl'] : "";

    $previewVideoImgUrl = isset($_POST['previewVideoImgUrl']) ? $_POST['previewVideoImgUrl'] : "";
    $videoUrl = isset($_POST['videoUrl']) ? $_POST['videoUrl'] : "";

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $comment = helper::clearText($comment);

    $comment = preg_replace( "/[\r\n]+/", "<br>", $comment); //replace all new lines to one new line
    $comment  = preg_replace('/\s+/', ' ', $comment);        //replace all white spaces to one space

    $comment = helper::escapeText($comment);

    $originImgUrl = helper::clearText($originImgUrl);
    $originImgUrl = helper::escapeText($originImgUrl);

    $previewImgUrl = helper::clearText($previewImgUrl);
    $previewImgUrl = helper::escapeText($previewImgUrl);

    $imgUrl = helper::clearText($imgUrl);
    $imgUrl = helper::escapeText($imgUrl);

    $previewVideoImgUrl = helper::clearText($previewVideoImgUrl);
    $previewVideoImgUrl = helper::escapeText($previewVideoImgUrl);

    $videoUrl = helper::clearText($videoUrl);
    $videoUrl = helper::escapeText($videoUrl);

//    if (strpos($originImgUrl, APP_HOST) === false) {
//
//        $originImgUrl = "";
//    }
//
//    if (strpos($previewImgUrl, APP_HOST) === false) {
//
//        $previewImgUrl = "";
//    }
//
//    if (strpos($imgUrl, APP_HOST) === false) {
//
//        $imgUrl = "";
//    }
//
//    if (strpos($previewVideoImgUrl, APP_HOST) === false) {
//
//        $previewVideoImgUrl = "";
//    }
//
//    if (strpos($videoUrl, APP_HOST) === false) {
//
//        $videoUrl = "";
//    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom($accountId);

    $result = $gallery->add($comment, $originImgUrl, $previewImgUrl, $imgUrl, $previewVideoImgUrl, $videoUrl);

    echo json_encode($result);
    exit;
}
