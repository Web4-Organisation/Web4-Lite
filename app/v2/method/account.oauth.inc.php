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

    $oauth_type = isset($_POST['oauth_type']) ? $_POST['oauth_type'] : 0;
    $oauth_name = isset($_POST['oauth_name']) ? $_POST['oauth_name'] : '';
    $oauth_email = isset($_POST['oauth_email']) ? $_POST['oauth_email'] : '';
    $oauth_photo = isset($_POST['oauth_photo']) ? $_POST['oauth_photo'] : '';

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

    $oauth_type = helper::clearInt($oauth_type);

    $oauth_name = helper::clearText($oauth_name);
    $oauth_name = helper::escapeText($oauth_name);

    $oauth_email = helper::clearText($oauth_email);
    $oauth_email = helper::escapeText($oauth_email);

    $oauth_photo = helper::clearText($oauth_photo);
    $oauth_photo = helper::escapeText($oauth_photo);

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

            $account = new account($dbo, $account_id);

            $result = array(
                "error" => true,
                "error_code" => ERROR_OAUTH_ID_TAKEN
            );

            switch ($oauth_type) {

                case OAUTH_TYPE_GOOGLE: {

                    if ($helper->getUserIdByGoogle($uid) == 0) {

                        $account->setGoogleFirebaseId($uid);

                        $result = array(
                            "error" => false,
                            "error_code" => ERROR_SUCCESS
                        );
                    }

                    break;
                }

                case OAUTH_TYPE_APPLE: {

                    if ($helper->getUserIdByApple($uid) == 0) {

                        $account->setAppleId($uid);

                        $result = array(
                            "error" => false,
                            "error_code" => ERROR_SUCCESS
                        );
                    }

                    break;
                }

                default: {

                    // Facebook

                    if ($helper->getUserIdByFacebook($uid) == 0) {

                        $account->setFacebookId($uid);

                        $result = array(
                            "error" => false,
                            "error_code" => ERROR_SUCCESS
                        );
                    }

                    break;
                }
            }

            unset($account);

            break;
        }

        case 'disconnect': {

            if (!$auth->authorize($account_id, $access_token)) {

                api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
            }

            $account = new account($dbo, $account_id);

            switch ($oauth_type) {

                case OAUTH_TYPE_GOOGLE: {

                    $account->setGoogleFirebaseId("");

                    break;
                }

                case OAUTH_TYPE_APPLE: {

                    $account->setAppleId("");

                    break;
                }

                default: {

                    // Facebook

                    $account->setFacebookId("");

                    break;
                }
            }

            unset($account);

            $result = array(
                "error" => false,
                "error_code" => ERROR_SUCCESS
            );

            break;
        }

        default: {

            switch ($oauth_type) {

                case OAUTH_TYPE_GOOGLE: {

                    $account_id = $helper->getUserIdByGoogle($uid);

                    break;
                }

                case OAUTH_TYPE_APPLE: {

                    $account_id = $helper->getUserIdByApple($uid);

                    break;
                }

                default: {

                    // Facebook

                    $account_id = $helper->getUserIdByFacebook($uid);

                    break;
                }
            }

            if ($account_id == 0) {

                // Auto signup

                $account = new account($dbo);
                $account_info = $account->signupOauth($oauth_type, $uid, $oauth_name, $oauth_email);
                unset($account);

                if (!$account_info['error']) {

                    $account_id = $account_info['accountId'];

                    if (strlen($oauth_photo) != 0 && $oauth_type == OAUTH_TYPE_GOOGLE) {

                        $imgFilename = "tmp/tmp.jpg";

                        @file_put_contents($imgFilename, file_get_contents($oauth_photo));

                        $cdn = new cdn($dbo);
                        $response = $cdn->uploadPhoto($imgFilename);

                        if (!$response['error']) {

                            $pic_result['normalPhotoUrl'] = $response['fileUrl'];
                            $pic_result['originPhotoUrl'] = $response['fileUrl'];
                            $pic_result['bigPhotoUrl'] = $response['fileUrl'];
                            $pic_result['lowPhotoUrl'] = $response['fileUrl'];

                            $acc = new account($dbo, $account_id);
                            $acc->setPhoto($pic_result);
                            unset($acc);
                        }

                        unset($cdn);
                    }
                }
            }

            if ($account_id != 0) {

                // Authorize

                $account = new account($dbo, $account_id);
                $account_info = $account->get();

                if ($account_info['state'] == ACCOUNT_STATE_ENABLED) {

                    $auth = new auth($dbo);
                    $result = $auth->create($account_id, $client_id, $app_type, $fcm_regId, $lang);

                    if (!$result['error']) {

                        $account->setLastActive();
                        $result['account'] = array();

                        array_push($result['account'], $account_info);
                    }
                }
            }

            break;
        }
    }

    echo json_encode($result);
    exit;
}
