<?php

    //Include Google Client Library for PHP autoload file
    //require_once 'vendor/autoload.php';

    require_once('../sys/addons/vendor/autoload.php');

    //Make object of Google API Client for call Google API
    $google_client = new Google_Client();

    //Set the OAuth 2.0 Client ID
    $google_client->setClientId(GOOGLE_CLIENT_ID);

    //Set the OAuth 2.0 Client Secret key
    $google_client->setClientSecret(GOOGLE_CLIENT_SECRET);

    //Set the OAuth 2.0 Redirect URI
    $google_client->setRedirectUri(APP_URL."/google");

    //
    $google_client->addScope('email');

    $google_client->addScope('profile');

    //start session on web page
    //session_start();