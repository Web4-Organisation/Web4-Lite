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
 */;

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

require_once '../sys/addons/vendor/autoload.php';

use Kreait\Firebase\Factory;

use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\ServiceAccount;

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $phoneNumber = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : '';

    $idToken = isset($_POST['token']) ? $_POST['token'] : '';

    $phoneNumber = helper::clearText($phoneNumber);
    $phoneNumber = helper::escapeText($phoneNumber);

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "desc" => "",
        "token" => "",
        "verified" => false
    );

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $jsonFileName = "";

    if ($files = glob("js/firebase/*.json")) {

        $jsonFileName = $files[0];
    }

    $serviceAccount = ServiceAccount::fromValue($jsonFileName);

    $firebase = (new Factory)->withServiceAccount($serviceAccount);

    $firebaseAuth = $firebase->createAuth();

    try {

        $token = $firebaseAuth->verifyIdToken($idToken, true);

        $uid = $token->claims()->get('sub');

        $user = $firebaseAuth->getUser($uid);

        if ($user->phoneNumber != null) {

            if (!$helper->isPhoneNumberExists($user->phoneNumber)) {

                $account = new account($dbo, $accountId);
                $account->updateOtpVerification($user->phoneNumber, 1);

                if (BONUS_OTP_VERIFICATION != 0) {

                    $account->setBalance($account->getBalance() + BONUS_OTP_VERIFICATION);

                    $payments = new payments($dbo);
                    $payments->setRequestFrom($accountId);
                    $payments->create(PA_BUY_OTP_VERIFICATION, PT_BONUS, BONUS_OTP_VERIFICATION);
                    unset($payments);
                }

                if (auth::getCurrentUserId() != 0) {

                    auth::setCurrentUserOtpVerified(1);
                }

                $result = array(
                    "error" => false,
                    "error_code" => ERROR_SUCCESS,
                    "verified" => true
                );

            }  else {

                $result = array(
                    "error" => true,
                    "error_code" => ERROR_OTP_PHONE_NUMBER_TAKEN,
                    "verified" => false
                );
            }
        }

        $firebaseAuth->revokeRefreshTokens($uid);

    } catch (InvalidToken $e) {

        $result['desc'] = "InvalidToken";
        $result['token'] = $e->getMessage();

    } catch (\Kreait\Firebase\Exception\AuthException $e) {

        $result['desc'] = "AuthException";
        $result['token'] = $e->getMessage();

    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {

        $result['desc'] = "FirebaseException";
        $result['token'] = $e->getMessage();
    }

    echo json_encode($result);
    exit;
}
