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

    $client_id = isset($_POST['client_id']) ? $_POST['client_id'] : 0;

    $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : 0;
    $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

    $app_type = isset($_POST['app_type']) ? $_POST['app_type'] : 0; // 0 = APP_TYPE_UNKNOWN
    $fcm_regId = isset($_POST['fcm_regId']) ? $_POST['fcm_regId'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $lang = isset($_POST['lang']) ? $_POST['lang'] : '';

    $uid = isset($_POST['uid']) ? $_POST['uid'] : '';

    $client_id = helper::clearInt($client_id);

    $app_type = helper::clearInt($app_type);

    $action = helper::clearText($action);
    $action = helper::escapeText($action);

    $lang = helper::clearText($lang);
    $lang = helper::escapeText($lang);

    $fcm_regId = helper::clearText($fcm_regId);
    $fcm_regId = helper::escapeText($fcm_regId);

    $uid = helper::clearText($uid);
    $uid = helper::escapeText($uid);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $helper = new helper($dbo);
    $auth = new auth($dbo);

    switch ($action) {

        case 'connect': {

            //

            if (!$auth->authorize($account_id, $access_token)) {

                api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
            }

            if ($helper->getUserIdByGoogle($uid) != 0) {

                $result = array(
                    "error" => true,
                    "error_code" => ERROR_FACEBOOK_ID_TAKEN
                );

            } else {

                $account = new account($dbo, $account_id);
                $account->setGoogleFirebaseId($uid);
                unset($account);

                $result = array(
                    "error" => false,
                    "error_code" => ERROR_SUCCESS
                );
            }

            break;
        }

        case 'disconnect': {

            //

            if (!$auth->authorize($account_id, $access_token)) {

                api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
            }

            $account = new account($dbo, $account_id);
            $account->setGoogleFirebaseId("");
            unset($account);

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );

            break;
        }

        default: {

            $account_id = $helper->getUserIdByGoogle($uid);

            if ($account_id != 0) {

                $account = new account($dbo, $account_id);
                $account_info = $account->get();

                if ($account_info['state'] == ACCOUNT_STATE_ENABLED) {

                    $auth = new auth($dbo);
                    $result = $auth->create($account_id, $client_id, $app_type, $fcm_regId, $lang);

                    if (!$result['error']) {

                        $account->setLastActive();
                        $result['account'] = array();

                        array_push($result['account'], $account_info);

                        if ($app_type == APP_TYPE_WEB) {

                            auth::setSession($result['accountId'], $account_info['username'], $account_info['fullname'], $account_info['lowPhotoUrl'], $account_info['verified'], $account_info['access_level'], $result['accessToken']);
                            auth::setCurrentUserAdmobFeature($account_info['admob']);
                            auth::setCurrentUserGhostFeature($account_info['ghost']);
                            auth::setCurrentUserOtpVerified($account_info['otpVerified']);
                            auth::updateCookie($account_info['username'], $result['accessToken']);
                        }
                    }
                }

            } else {

                if ($app_type == APP_TYPE_WEB) {

                    $_SESSION['oauth'] = 'google';
                    $_SESSION['oauth_id'] = $uid;
                    $_SESSION['oauth_name'] = "";
                    $_SESSION['oauth_email'] = "";
                    $_SESSION['oauth_link'] = "";
                }
            }

            break;
        }
    }

    echo json_encode($result);
    exit;
}
