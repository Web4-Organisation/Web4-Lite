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

    $postId = isset($_POST['postId']) ? $_POST['postId'] : 0;
    $postMode = isset($_POST['postMode']) ? $_POST['postMode'] : 0;
    $rePostId = isset($_POST['rePostId']) ? $_POST['rePostId'] : 0;

    $postText = isset($_POST['postText']) ? $_POST['postText'] : '';
    $postImg = isset($_POST['postImg']) ? $_POST['postImg'] : '';

    $postArea = isset($_POST['postArea']) ? $_POST['postArea'] : '';
    $postCountry = isset($_POST['postCountry']) ? $_POST['postCountry'] : '';
    $postCity = isset($_POST['postCity']) ? $_POST['postCity'] : '';
    $postLat = isset($_POST['postLat']) ? $_POST['postLat'] : '0.000000';
    $postLng = isset($_POST['postLng']) ? $_POST['postLng'] : '0.000000';

    $videoImgUrl = isset($_POST['videoImgUrl']) ? $_POST['videoImgUrl'] : '';
    $videoUrl = isset($_POST['videoUrl']) ? $_POST['videoUrl'] : '';

    $feeling = isset($_POST['feeling']) ? $_POST['feeling'] : 0;

    $imagesArray = isset($_POST['images']) ? $_POST['images'] : array();

    $feeling = helper::clearInt($feeling);

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $postId = helper::clearInt($postId);
    $postMode = helper::clearInt($postMode);
    $rePostId = helper::clearInt($rePostId);

    $postText = preg_replace( "/[\r\n]+/", "<br>", $postText); //replace all new lines to one new line
    $postText  = preg_replace('/\s+/', ' ', $postText);        //replace all white spaces to one space

    $postText = helper::escapeText($postText);

    $postImg = helper::clearText($postImg);
    $postImg = helper::escapeText($postImg);

    $postArea = helper::clearText($postArea);
    $postArea = helper::escapeText($postArea);

    $postCountry = helper::clearText($postCountry);
    $postCountry = helper::escapeText($postCountry);

    $postCity = helper::clearText($postCity);
    $postCity = helper::escapeText($postCity);

    $postLat = helper::clearText($postLat);
    $postLat = helper::escapeText($postLat);

    $postLng = helper::clearText($postLng);
    $postLng = helper::escapeText($postLng);

    $videoImgUrl = helper::clearText($videoImgUrl);
    $videoImgUrl = helper::escapeText($videoImgUrl);

    $videoUrl = helper::clearText($videoUrl);
    $videoUrl = helper::escapeText($videoUrl);

    if (strpos($postImg, APP_HOST) === false) {

        $postImg = "";
    }

    if (strpos($videoImgUrl, APP_HOST) === false) {

        $videoImgUrl = "";
    }

    if (strpos($videoUrl, APP_HOST) === false) {

        $videoUrl = "";
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $posts = new post($dbo);
    $posts->setRequestFrom($accountId);

    $postInfo = $posts->info($postId);

    if ($postInfo['error'] === true) {

        return $result;
    }

    if ($postInfo['fromUserId'] != $accountId) {

        return $result;
    }

    $result = $posts->edit($postId, $postMode, $rePostId, $postText, $postImg, $postArea, $postCountry, $postCity, $postLat, $postLng, $videoImgUrl, $videoUrl, $feeling);

    if (!$result['error']) {

        $postimg = new postimg($dbo);
        $postimg->setRequestFrom($accountId);

        $postimg->removeAll($postId);

        if (count($imagesArray) <= POST_MAX_IMAGES_COUNT) {

            if (count($imagesArray) > 0) {

                for ($i = 0; $i <= count($imagesArray); $i++) {

                    if (strpos($imagesArray[$i], APP_URL."/".POST_PHOTO_PATH) !== false) {

                        $postimg->add($postId, $imagesArray[$i], $imagesArray[$i], $imagesArray[$i]);
                    }
                }

                $posts->setImagesCount($postId, count($imagesArray));

            } else {

                $posts->setImagesCount($postId, 0);
            }
        }
    }

    echo json_encode($result);
    exit;
}
