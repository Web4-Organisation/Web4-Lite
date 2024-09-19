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

    $group_name = isset($_POST['group_name']) ? $_POST['group_name'] : '';
    $group_fullname = isset($_POST['group_fullname']) ? $_POST['group_fullname'] : '';
    $group_category = isset($_POST['group_category']) ? $_POST['group_category'] : 0;
    $group_desc = isset($_POST['group_desc']) ? $_POST['group_desc'] : '';
    $group_site = isset($_POST['group_site']) ? $_POST['group_site'] : '';
    $group_location = isset($_POST['group_location']) ? $_POST['group_location'] : '';

    $year = isset($_POST['year']) ? $_POST['year'] : 0;
    $month = isset($_POST['month']) ? $_POST['month'] : 0;
    $day = isset($_POST['day']) ? $_POST['day'] : 0;

    $group_allow_posts = isset($_POST['group_allow_posts']) ? $_POST['group_allow_posts'] : 0;
    $group_allow_comments = isset($_POST['group_allow_comments']) ? $_POST['group_allow_comments'] : 0;

    $clientId = helper::clearInt($clientId);
    $accountId = helper::clearInt($accountId);

    $group_category = helper::clearInt($group_category);

    $year = helper::clearInt($year);
    $month = helper::clearInt($month);
    $day = helper::clearInt($day);

    $group_allow_posts = helper::clearInt($group_allow_posts);
    $group_allow_comments = helper::clearInt($group_allow_comments);

    $group_name = helper::clearText($group_name);
    $group_name = helper::escapeText($group_name);

    $group_fullname = helper::clearText($group_fullname);
    $group_fullname = helper::escapeText($group_fullname);

    $group_category = helper::clearText($group_category);
    $group_category = helper::escapeText($group_category);

    $group_desc = helper::clearText($group_desc);
    $group_desc = helper::escapeText($group_desc);

    $group_site = helper::clearText($group_site);
    $group_site = helper::escapeText($group_site);

    $group_location = helper::clearText($group_location);
    $group_location = helper::escapeText($group_location);

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $group = new group($dbo);
    $group->setRequestFrom($accountId);

    $result = $group->create($group_name, $group_fullname, $group_category, $group_desc, $group_site, $group_location, $year, $month, $day, $group_allow_posts, $group_allow_comments);

    echo json_encode($result);
    exit;
}
