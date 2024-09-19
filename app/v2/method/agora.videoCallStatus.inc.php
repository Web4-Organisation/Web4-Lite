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

	$accountId = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
	$accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';

	$call_id = isset($_POST['call_id']) ? $_POST['call_id'] : 0;
    $time = isset($_POST['time']) ? $_POST['time'] : 0;
	$status = isset($_POST['status']) ? $_POST['status'] : 0;

	$call_id = helper::clearInt($call_id);
	$status = helper::clearInt($status);

	$auth = new auth($dbo);

	if (!$auth->authorize($accountId, $accessToken)) {

		api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
	}

	$result = array(
		"error" => true,
		"error_code" => ERROR_UNKNOWN
	);

	$agora = new agora($dbo);
	$agora->setRequestFrom($accountId);

    $result = $agora->statusVideoCall($call_id, $status, $time);

    echo json_encode($result);
    exit;
}

