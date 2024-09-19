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

    $error = false;
    $error_msg = "";

    if (isset($_GET['to'])) {

        $url = (isset($_GET['to'])) ? $_GET['to'] : '';

        $url = helper::clearText($url);
        $url = helper::escapeText($url);

        if (!filter_var($url, FILTER_VALIDATE_URL)) {

            header("Location: /");
            exit;

        } else {

            // add url to db

//            $stats = new stats($dbo);
//            $stats->setRequestFrom(auth::getCurrentUserId());
//            $stats->add($url);
//
//            unset($stats);

            header("Location: ".$url);
        }

    } else {

        header("Location: /");
        exit;
    }
