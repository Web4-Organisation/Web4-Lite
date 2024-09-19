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

    $css_files = array("main.css", "my.css");
    $page_title = $profileInfo['fullname']." | ".APP_HOST."/".$profileInfo['username'];

    include_once("../html/common/header.inc.php");

?>

<body class="user-profile">

<?php
include_once("../html/common/topbar.inc.php");
?>

<div class="wrap content-page">

    <div class="main-column row">

        <?php

            include_once("../html/common/sidenav.inc.php");
        ?>

        <div class="row col sn-content sn-content-wide-block" id="content">

            <div class="main-content">

                <div class="profile-content standard-page" style="background: white">

                    <div class="user-info">

                        <a href="/<?php echo $profileInfo['username']; ?>" class="profile_img_wrap">
                            <img alt="Photo" class="profile-user-photo user_image" width="90" height="90px" src="/img/profile_default_photo.png">
                        </a>

                        <div class="basic-info">
                            <h1><?php echo $profileInfo['fullname']; ?></h1>
                            <h4 style="margin: 0">@<?php echo $profileInfo['username']; ?></h4>
                        </div>

                    </div>


                    <div class="content-list-page section" style="margin: 0; padding: 0">

                        <header class="top-banner info-banner" style="border: 0">

                            <div class="info">
                                <h1>
                                    <?php

                                    switch ($profileInfo['state']) {

                                        case ACCOUNT_STATE_DISABLED: {

                                            // User disable account
                                            echo $LANG['label-account-disabled'];
                                            break;
                                        }

                                        case ACCOUNT_STATE_BLOCKED: {

                                            // Account blocked by moderator
                                            echo $LANG['label-account-blocked'];
                                            break;
                                        }

                                        default: {

                                            // Account created and not activated
                                            echo $LANG['label-account-deactivated'];
                                            break;
                                        }
                                    }
                                    ?>
                                </h1>
                            </div>

                        </header>

                    </div>


                </div>

            </div>

        </div>
    </div>

</div>

<?php

    include_once("../html/common/footer.inc.php");
?>

</body
</html>