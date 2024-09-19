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

    require_once '../sys/addons/vendor/autoload.php';

    $accountId = auth::getCurrentUserId();

    $error = false;
    $error_message = array();
    $recaptcha_token = "";

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $recaptcha_token = isset($_POST['recaptcha_token']) ? $_POST['recaptcha_token'] : '';

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message[] = "Error. Try again later...";
        }

        // Google Recaptcha

        if (GOOGLE_RECAPTCHA_WEB) {

            $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY);
            $resp = $recaptcha->verify($recaptcha_token, $_SERVER['REMOTE_ADDR']);

            if (!$resp->isSuccess()){

                $error = true;
                $error_message[] = "Google Recaptcha error";
            }
        }

        if (!$error) {

            $account = new account($dbo, $accountId);

            $result = array(
                    "error" => true
            );

            $result = $account->deactivation();

            if (!$result['error']) {

                header("Location: /logout?access_token=".auth::getAccessToken());
                exit;
            }
        }

        header("Location: /account/settings/deactivation?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_deactivation";

    $css_files = array();
    $page_title = $LANG['page-profile-deactivation']." | ".APP_TITLE;

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

                        <h1 class="title"><?php echo $LANG['page-profile-deactivation']; ?></h1>

                        <form accept-charset="UTF-8" action="/account/settings/deactivation" autocomplete="off" class="edit_user" id="settings-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                            <div class="tabbed-content">

                                <div class="alert alert-warning">
                                    <ul>
                                        <?php echo $LANG['page-profile-deactivation-sub-title']; ?>
                                    </ul>
                                </div>

                                <?php

                                    if (isset($_GET['error']) ) {

                                        ?>

                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php echo $LANG['msg-error-deactivation']; ?>
                                            </ul>
                                        </div>

                                        <?php
                                    }
                                ?>

                            </div>

                            <input style="margin-top: 25px" name="commit" class="button primary" type="submit" value="<?php echo $LANG['action-deactivation-profile']; ?>">

                        </form>
                    </div>


                </div>
            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script>

        $('#settings-form').submit(function(event) {

            if (constants.GOOGLE_RECAPTCHA_WEB) {

                event.preventDefault();

                grecaptcha.ready(function() {
                    grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'submit'}).then(function(token) {

                        $('#settings-form').prepend('<input type="hidden" name="recaptcha_token" value="'+ token + '">');
                        $('#settings-form').unbind('submit').submit();
                    });
                });
            }
        });
    </script>

</body
</html>