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

    $lat = isset($_POST['lat']) ? $_POST['lat'] : '0.000000';
    $lng = isset($_POST['lng']) ? $_POST['lng'] : '0.000000';

    $lat = helper::clearText($lat);
    $lat = helper::escapeText($lat);

    $lng = helper::clearText($lng);
    $lng = helper::escapeText($lng);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $auth = new auth($dbo);

    if (!auth::isSession()) {

        if (!$auth->authorize($accountId, $accessToken)) {

            api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
        }
    }

    $result = array(
        "error" => false,
        "error_code" => ERROR_SUCCESS
    );

    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();
    unset($account);

    $messages_count = 0;
    $notifications_count = 0;
    $guests_count = 0;
    $friends_count = 0;

    // Get new messages count

    if (APP_MESSAGES_COUNTERS) {

        $msg = new msg($dbo);
        $msg->setRequestFrom($accountId);

        $messages_count = $msg->getNewMessagesCount();

        unset($msg);
    }

    // Get notifications count

    $notifications = new notify($dbo);
    $notifications->setRequestFrom($accountId);

    $notifications_count = $notifications->getNewCount($accountInfo['lastNotifyView']);

    unset($notifications);


    // Get new guests count

    $guests = new guests($dbo, $accountId);
    $guests->setRequestFrom($accountId);

    $guests_count = $guests->getNewCount($accountInfo['lastGuestsView']);

    unset($guests);

    // Get friends count

    $friends = new friends($dbo);
    $friends->setRequestFrom($accountId);

    $friends_count = $friends->getNewCount($accountInfo['lastFriendsView']);

    unset($friends);

    //

    $result['messagesCount'] = $messages_count;
    $result['notificationsCount'] = $notifications_count;
    $result['guestsCount'] = $guests_count;
    $result['friendsCount'] = $friends_count;

    // Get chat settings

    $settings = new settings($dbo);

    $config = $settings->get();

    $arr = array();

    $arr = $config['interstitialAdAfterNewItem'];
    $result['interstitialAdAfterNewItem'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewGalleryItem'];
    $result['interstitialAdAfterNewGalleryItem'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewMarketItem'];
    $result['interstitialAdAfterNewMarketItem'] = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewLike'];
    $result['interstitialAdAfterNewLike'] = $arr['intValue'];

    //

    $arr = $config['android_admob_app_id'];
    $result['android_admob_app_id'] = $arr['textValue'];

    $arr = $config['android_admob_banner_ad_unit_id'];
    $result['android_admob_banner_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_rewarded_ad_unit_id'];
    $result['android_admob_rewarded_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_interstitial_ad_unit_id'];
    $result['android_admob_interstitial_ad_unit_id'] = $arr['textValue'];

    $arr = $config['android_admob_banner_native_ad_unit_id'];
    $result['android_admob_banner_native_ad_unit_id'] = $arr['textValue'];

    //

    $arr = $config['ios_admob_app_id'];
    $result['ios_admob_app_id'] = $arr['textValue'];

    $arr = $config['ios_admob_banner_ad_unit_id'];
    $result['ios_admob_banner_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_rewarded_ad_unit_id'];
    $result['ios_admob_rewarded_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_interstitial_ad_unit_id'];
    $result['ios_admob_interstitial_ad_unit_id'] = $arr['textValue'];

    $arr = $config['ios_admob_banner_native_ad_unit_id'];
    $result['ios_admob_banner_native_ad_unit_id'] = $arr['textValue'];

    // Agora

    $arr = $config['agora_app_enabled'];
    $result['agora_app_enabled'] = $arr['intValue'];

    $arr = $config['agora_app_id'];
    $result['agora_app_id'] = $arr['textValue'];

    $arr = $config['agora_app_certificate'];
    $result['agora_app_certificate'] = $arr['textValue'];

    //

    echo json_encode($result);
    exit;
}
