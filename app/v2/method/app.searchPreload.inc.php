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

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;

    $gender = isset($_POST['gender']) ? $_POST['gender'] : -1;
    $online = isset($_POST['online']) ? $_POST['online'] : -1;

    $photo = isset($_POST['photo']) ? $_POST['photo'] : -1;

    if ($gender != -1) $gender = helper::clearInt($gender);
    if ($online != -1) $online = helper::clearInt($online);
    if ($photo != -1) $photo = helper::clearInt($photo);

    $itemId = helper::clearInt($itemId);

    if ($gender != -1) $gender = helper::clearInt($gender);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $search = new search($dbo);
    $search->setRequestFrom($accountId);

    $result = $search->preload($itemId, $gender, $online, $photo);

    echo json_encode($result);
    exit;
}
