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

if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

    header('Location: /');
}

if (isset($_GET['access_token'])) {

    $accessToken = (isset($_GET['access_token'])) ? ($_GET['access_token']) : '';

    if (auth::getAccessToken() === $accessToken) {

        $account = new account($dbo, auth::getCurrentUserId());
        $account->setFacebookId(""); //remove connection. set facebook id to 0.

        header("Location: /account/settings/services/?oauth_provider=facebook&status=disconnected");
        exit;
    }
}

header("Location: /account/settings/services");
