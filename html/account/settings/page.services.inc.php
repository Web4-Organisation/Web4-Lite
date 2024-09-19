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

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    include_once('../sys/config/gconfig.inc.php');

	$error = false;
    $error_message = '';

    $account = new account($dbo, auth::getCurrentUserId());

    $account_info = $account->get();

    $account_free = $account_info['account_free'];

    $fb_id = $account_info['fb_id'];
    $gl_id = $account_info['gl_id'];
    $ap_id = $account_info['ap_id'];

    if (!empty($_POST)) {

    }

	$page_id = "settings_services";

	$css_files = array();
    $page_title = $LANG['page-services']." | ".APP_TITLE;

	include_once("../html/common/header.inc.php");
?>

<body class="settings-page">

    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/account/settings/settings_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="profile-content standard-page">

                        <h1 class="title"><?php echo $LANG['page-services']; ?></h1>

                        <?php

                        $msg = $LANG['page-services-sub-title'];

                        if (isset($_GET['status'])) {

                            switch($_GET['status']) {

                                case "connected": {

                                    $msg = $LANG['label-services-facebook-connected'];
                                    break;
                                }

                                case "g_connected": {

                                    $msg = $LANG['label-services-google-connected'];
                                    break;
                                }

                                case "error": {

                                    $msg = $LANG['label-services-facebook-error'];
                                    break;
                                }

                                case "g_error": {

                                    $msg = $LANG['label-services-google-error'];
                                    break;
                                }

                                case "disconnected": {

                                    $msg = $LANG['label-services-facebook-disconnected'];
                                    break;
                                }

                                case "g_disconnected": {

                                    $msg = $LANG['label-services-google-disconnected'];
                                    break;
                                }

                                default: {

                                    $msg = $LANG['page-services-sub-title'];
                                    break;
                                }
                            }
                        }
                        ?>

                        <?php

                            if ($account_info['account_free'] != 0) {

                                ?>
                                    <div class="alert alert-warning">
                                        <ul>
                                            <?php echo $LANG['label-login-create-promo-2']; ?>
                                            <br>
                                            <a href="/account/settings/password" class="button primary mt-2 d-block"><?php echo $LANG['label-login-create']; ?></a>
                                        </ul>
                                    </div>
                                <?php

                            } else {

                                ?>
                                    <div class="alert alert-warning">
                                        <ul>
                                            <?php echo $msg; ?>
                                        </ul>
                                    </div>

                                    <header class="top-banner <?php if (!FACEBOOK_AUTHORIZATION) echo "gone" ?>" style="padding: 0">

                                        <div class="info">
                                            <h1>Facebook</h1>

                                            <?php

                                            if (strlen($fb_id) != 0) {

                                                ?>
                                                <p><?php echo $LANG['label-connected-with-facebook']; ?></p>
                                                <?php
                                            }
                                            ?>

                                        </div>

                                        <div class="prompt">

                                            <?php

                                            if (strlen($fb_id) < 5) {

                                                ?>
                                                <a class="button green" href="/facebook/connect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-connect-facebook']; ?></a>
                                                <?php

                                            } else {

                                                ?>
                                                <a class="button red" href="/facebook/disconnect/?access_token=<?php echo auth::getAccessToken(); ?>"><?php echo $LANG['action-disconnect']; ?></a>
                                                <?php
                                            }
                                            ?>

                                        </div>

                                    </header>

                                    <header class="top-banner mt-2 <?php if (!GOOGLE_AUTHORIZATION) echo "gone" ?>" style="padding: 0">

                                        <div class="info">
                                            <h1>Google</h1>

                                            <?php

                                            if (strlen($gl_id) != 0) {

                                                ?>
                                                <p><?php echo $LANG['label-connected-with-google']; ?></p>
                                                <?php
                                            }
                                            ?>

                                        </div>

                                        <div class="prompt">

                                            <?php

                                            if (strlen($gl_id) == 0) {

                                                ?>
                                                <a class="button green" href="<?php echo $google_client->createAuthUrl(); ?>"><?php echo $LANG['action-connect-google']; ?></a>
                                                <?php

                                            } else {

                                                ?>
                                                <a class="button red" onclick="disconnect()"><?php echo $LANG['action-disconnect']; ?></a>
                                                <?php
                                            }
                                            ?>

                                        </div>

                                    </header>

                                <?php
                            }
                        ?>

                    </div>


                </div>
            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");

    ?>

    <script type="text/javascript" src="/js/firebase/config.js"></script>
<!--    <script type="text/javascript" src="/js/firebase/google.js"></script>-->

    <script>

        var OAUTH_TYPE_GOOGLE = 1;

        function disconnect() {

            $.ajax({
                type: 'POST',
                url: "/api/" + options.api_version + "/method/account.oauth",
                data: 'account_id=' + account.id + '&access_token=' + account.accessToken + '&action=disconnect' + '&oauth_type=' + OAUTH_TYPE_GOOGLE,
                dataType: 'json',
                timeout: 30000,
                success: function(response) {

                    if (response.hasOwnProperty('error')) {

                        if (!response.error) {

                            window.location = "/account/settings/services?status=g_disconnected";
                        }
                    }
                },
                error: function(xhr, type){


                }
            });
        }

    </script>

</body
</html>