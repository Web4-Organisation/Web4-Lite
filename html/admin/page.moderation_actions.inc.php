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

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $stats = new stats($dbo);
    $admin = new admin($dbo);

    if (isset($_GET['account_id'])) {

        $action = isset($_GET['act']) ? $_GET['act'] : '';

        $accountId = isset($_GET['account_id']) ? $_GET['account_id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : '';

        $accountId = helper::clearInt($accountId);

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            $moderator = new moderator($dbo);

            switch ($action) {

                case "photo_approve": {

                    $moderator->approvePhoto($accountId);

                    break;
                }

                case "photo_reject": {

                    $moderator->rejectPhoto($accountId);

                    break;
                }

                case "cover_approve": {

                    $moderator->approveCover($accountId);

                    break;
                }

                case "cover_reject": {

                    $moderator->rejectCover($accountId);

                    break;
                }

                default: {

                    break;
                }
            }

            unset($moderator);
        }

    } else {

        header("Location: /admin/main");
        exit;
    }
