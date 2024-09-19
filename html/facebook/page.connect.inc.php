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

    if (!auth::isSession()) {

        header('Location: /');
        exit;
    }

    if (isset($_GET['error'])) {

        header("Location: /account/settings/services");
        exit;
    }

    require_once '../sys/addons/vendor/autoload.php';

    $fb = new \Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);


    // login helper with redirect_uri
    $fb_helper = $fb->getRedirectLoginHelper();

    if (isset($_GET['state'])) {

        $fb_helper->getPersistentDataHandler()->set('state', $_GET['state']);
    }

    if (isset($_GET['code'])) {

        try {

            $accessToken = $fb_helper->getAccessToken();

            $response = $fb->get('/me', $accessToken);

            $me = $response->getGraphUser();

            $accountId = $helper->getUserIdByFacebook($me->getId());

            if ($accountId != 0) {

                //user with fb id exists in db
                header("Location: /account/settings/services?oauth_provider=facebook&status=error");
                exit;

            } else {

                //new user

                $account = new account($dbo, auth::getCurrentUserId());
                $account->setFacebookId($me->getId());

                header("Location: /account/settings/services?oauth_provider=facebook&status=connected");
                exit;
            }

        } catch (Exception $e) {

            header("Location: /account/settings/services");
            exit;
        }


    } else {

        $loginUrl = $fb_helper->getLoginUrl(APP_URL."/facebook/connect");
        header("Location: ".$loginUrl);
        exit;
    }