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

    $appType = isset($_POST['appType']) ? $_POST['appType'] : 0; // 0 = APP_TYPE_UNKNOWN
    $fcm_regId = isset($_POST['fcm_regId']) ? $_POST['fcm_regId'] : '';
    $lang = isset($_POST['lang']) ? $_POST['lang'] : '';

    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $signin_method = isset($_POST['signin_method']) ? $_POST['signin_method'] : 0;

    $clientId = helper::clearInt($clientId);

    $appType = helper::clearInt($appType);

    $lang = helper::clearText($lang);
    $lang = helper::escapeText($lang);

    $fcm_regId = helper::clearText($fcm_regId);
    $fcm_regId = helper::escapeText($fcm_regId);

    $user_id = helper::clearText($user_id);
    $user_id = helper::escapeText($user_id);

    $signin_method = helper::clearInt($signin_method);

    $access_data = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $helper = new helper($dbo);

    $accountId = 0;

    switch ($signin_method) {

        case SIGNIN_FACEBOOK: {

            $accountId = $helper->getUserIdByFacebook($user_id);

            break;
        }

        case SIGNIN_GOOGLE: {

            $accountId = $helper->getUserIdByGoogle($user_id);

            break;
        }

        default: {

            break;
        }
    }

    if ($accountId != 0) {

        $account = new account($dbo, $accountId);
        $account_info = $account->get();

        if ($account_info['state'] == ACCOUNT_STATE_ENABLED) {

            $auth = new auth($dbo);
            $access_data = $auth->create($accountId, $clientId, $appType, $fcm_regId, $lang);

            if (!$access_data['error']) {

                $account->setLastActive();
                $access_data['account'] = array();

                array_push($access_data['account'], $account_info);
            }
        }
    }

    echo json_encode($access_data);
    exit;
}
