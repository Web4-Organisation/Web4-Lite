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

include '../sys/addons/agora/RtcTokenBuilder.php';

if (!empty($_POST)) {

	$accountId = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
	$accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';

	$call_id = isset($_POST['call_id']) ? $_POST['call_id'] : 0;
	$from_user_id = isset($_POST['from_user_id']) ? $_POST['from_user_id'] : 0;
	$to_user_id = isset($_POST['to_user_id']) ? $_POST['to_user_id'] : 0;

	$channel = isset($_POST['channel']) ? $_POST['channel'] : 0;

	$call_id = helper::clearInt($call_id);
	$from_user_id = helper::clearInt($from_user_id);
	$to_user_id = helper::clearInt($to_user_id);

	$channel = helper::clearText($channel);

	$auth = new auth($dbo);

	if (!$auth->authorize($accountId, $accessToken)) {

		api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
	}

	$result = array(
		"error" => true,
		"error_code" => ERROR_UNKNOWN
	);

	$settings = new settings($dbo);
	$config = $settings->get();

	$arr = array();

	$arr = $config['agora_app_id'];
	$appID = $arr['textValue'];

	$arr = $config['agora_app_certificate'];
	$appCertificate = $arr['textValue'];

	$uid = 0;
	$uidStr = "{$uid}";

	$role = RtcTokenBuilder::RolePublisher;
	$expireTimeInSeconds = 3600;
	$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
	$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

	$agora = new agora($dbo);
	$agora->setRequestFrom($accountId);

	if ($call_id != 0) {

		$result = $agora->info($call_id);
		$token = "";

		if ($result['createAt'] + 4000 < time()) {

            $agora->statusVideoCall($call_id, VIDEO_CALL_CANCELED);
            $result['callStatus'] = VIDEO_CALL_CANCELED;

        } else {

            $token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $result['channel'], $uid, $role, $privilegeExpiredTs);
        }

		$result['token'] = $token;
		$result['call_id'] = $call_id;
		$result['call_status'] = $result['callStatus'];
		$result['from_user_id'] = $result['fromUserId'];
		$result['to_user_id'] = $result['toUserId'];
        $result['from_user_photo_url'] = $result['fromUserPhotoUrl'];
        $result['from_user_username'] = $result['fromUserUsername'];
        $result['from_user_fullname'] = $result['fromUserFullname'];
        $result['to_user_photo_url'] = $result['toUserPhotoUrl'];
        $result['to_user_username'] = $result['toUserUsername'];
        $result['to_user_fullname'] = $result['toUserFullname'];

	} else {

		// new call

		$channelName = helper::generateHash(8);
		$token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

		$res = $agora->newVideoCall($from_user_id, $to_user_id, $channelName);

		if (!$res['error']) {

            $result = $agora->info($res['itemId']);

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS,
                "token" => $token,
                "channel" => $channelName,
                "call_id" => $res['itemId'],
                "call_status" => $result['callStatus'],
                "from_user_id" => $result['fromUserId'],
                "to_user_id" => $result['toUserId'],
                "from_user_photo_url" => $result['fromUserPhotoUrl'],
                "from_user_username" => $result['fromUserUsername'],
                "from_user_fullname" => $result['fromUserFullname'],
                "to_user_photo_url" => $result['toUserPhotoUrl'],
                "to_user_username" => $result['toUserUsername'],
                "to_user_fullname" => $result['toUserFullname']
            );
        }
	}

	echo json_encode($result);
	exit;
}

