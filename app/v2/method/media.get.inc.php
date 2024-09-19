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

    $accountId = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
    $accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';

    $itemId = isset($_POST['item_id']) ? $_POST['item_id'] : 0;
    $sectionId = isset($_POST['section_id']) ? $_POST['section_id'] : 0;

    $lang = isset($_POST['language']) ? $_POST['language'] : '';

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $itemId = helper::clearInt($itemId);
    $sectionId = helper::clearInt($sectionId);

    $lang = helper::clearText($lang);
    $lang = helper::escapeText($lang);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $media = new media($dbo);
    $media->setLanguage($lang);
    $media->setRequestFrom($accountId);

    if ($sectionId == 0) {

        $result = $media->get($itemId);

    } else {

        $result = $media->getImages($itemId);
    }

    echo json_encode($result);
    exit;
}
