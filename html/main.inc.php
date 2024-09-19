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

    if (auth::isSession()) {

        header("Location: /account/wall");
        exit;
    }

    include_once('../sys/config/gconfig.inc.php');
    require_once '../sys/addons/vendor/autoload.php';

    $user_username = '';

    $error = false;
    $error_message = array();

    if (!empty($_POST)) {

        $user_username = isset($_POST['user_username']) ? $_POST['user_username'] : '';
        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $recaptcha_token = isset($_POST['recaptcha_token']) ? $_POST['recaptcha_token'] : '';

        $user_username = helper::clearText($user_username);
        $user_password = helper::clearText($user_password);

        $user_username = helper::escapeText($user_username);
        $user_password = helper::escapeText($user_password);

        // Google Recaptcha

        if (GOOGLE_RECAPTCHA_WEB) {

            $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY);
            $resp = $recaptcha->verify($recaptcha_token, $_SERVER['REMOTE_ADDR']);

            if (!$resp->isSuccess()){

                $error = true;
                $error_message[] = "Google Recaptcha error";
            }
        }

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            $access_data = array();

            $account = new account($dbo);

            $access_data = $account->signin($user_username, $user_password);

            unset($account);

            if (!$access_data['error']) {

                $account = new account($dbo, $access_data['accountId']);
                $accountInfo = $account->get();

                //print_r($accountInfo);

                switch ($accountInfo['state']) {

                    case ACCOUNT_STATE_BLOCKED: {

                        break;
                    }

                    case ACCOUNT_STATE_DISABLED: {

                        break;
                    }

                    default: {

                        $clientId = 0; // Desktop version

                        $auth = new auth($dbo);
                        $access_data = $auth->create($accountInfo['id'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);

                        if (!$access_data['error']) {

                            auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                            auth::setCurrentUserAdmobFeature($accountInfo['admob']);
                            auth::setCurrentUserGhostFeature($accountInfo['ghost']);
                            auth::setCurrentUserOtpVerified($accountInfo['otpVerified']);
                            auth::updateCookie($user_username, $access_data['accessToken']);

                            unset($_SESSION['oauth']);
                            unset($_SESSION['oauth_id']);
                            unset($_SESSION['oauth_name']);
                            unset($_SESSION['oauth_email']);
                            unset($_SESSION['oauth_link']);

                            $account->setLastActive();

                            header("Location: /");
                        }
                    }
                }

            } else {

                $error = true;
            }
        }
    }

    auth::newAuthenticityToken();

    $page_id = "main";

    $css_files = array("landing.css");
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="home has-bottom-footer main-page">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="content-page">

        <div class="limiter">

            <?php

            if (WEB_EXPLORE) {

                ?>
                <div class="wrap-landing-info-container explore-promo">

                    <div class="wrap-landing-info">
                        <?php echo sprintf($LANG['main-page-promo-explore'], APP_TITLE); ?>
                        <a href="/explore" class="button green mt-4 p-3"><?php echo $LANG['action-explore']; ?> <i class="fa fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="container-login100">
                <div class="wrap-login100">

                    <form accept-charset="UTF-8" action="/" class="custom-form login100-form" id="login-form" method="post">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                        <span class="login100-form-title "><?php echo $LANG['page-login']; ?></span>

                        <?php

                            if (FACEBOOK_AUTHORIZATION) {

                                ?>

                                <p>
                                    <a class="fb-icon-btn fb-btn-large btn-social btn-facebook" href="/facebook/login">
                                        <span class="icon-container">
                                            <i class="icon icon-facebook"></i>
                                        </span>
                                        <span><?php echo $LANG['action-login-with']." ".$LANG['label-facebook']; ?></span>
                                    </a>
                                </p>
                                <?php
                            }

                            if (GOOGLE_AUTHORIZATION) {

                                ?>
                                    <p>
                                        <a class="gl-icon-btn gl-btn-large btn-social btn-google" href="<?php echo $google_client->createAuthUrl(); ?>">
                                            <span class="icon-container">
                                                <i class="icon icon-google"></i>
                                            </span>
                                            <span><?php echo $LANG['action-signup-with'] . " " . $LANG['label-google']; ?></span>
                                        </a>
                                    </p>
                                <?php
                            }
                        ?>

                        <div class="alert alert-danger" style="<?php if (!$error) echo "display: none"; ?>">
                            <h3><?php echo $LANG['label-errors-title']; ?></h3>
                            <ul>
                                <li><?php echo $LANG['msg-error-authorize']; ?></li>
                            </ul>
                        </div>

                        <input id="username" name="user_username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>">
                        <input id="password" name="user_password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">

                        <div class="login-button">
                            <input class="submit-button button primary" name="commit" type="submit" value="<?php echo $LANG['action-login']; ?>">
                            <a href="/remind" class="button link"><?php echo $LANG['action-forgot-password']; ?></a>
                        </div>
                    </form>

                    <div class="login100-more">
                        <div class="login100_content">
                            <h1 class="mb-10">Create your own <?php echo APP_NAME; ?> App now!</h1>
                            <p><?php echo sprintf($LANG['main-page-promo-login'], APP_TITLE); ?></p>
                        </div>
                    </div>

                </div>

            </div>

            <?php

                if (strlen(GOOGLE_PLAY_LINK) != 0) {

                    ?>
                        <div class="wrap-landing-info-container">

                            <div class="wrap-landing-info">
                                <?php echo sprintf($LANG['main-page-promo-google-app'], APP_TITLE, APP_TITLE); ?>
                                <a href="<?php echo GOOGLE_PLAY_LINK; ?>" target="_blank" rel="nofollow">
                                    <img class="mt-4" width="170" src="/img/google_play.png">
                                </a>
                            </div>
                        </div>
                    <?php
                }
            ?>

            <?php

                include_once("../html/common/footer.inc.php");
            ?>

            <script>

                //

                $('#login-form').submit(function(event) {

                    if (constants.GOOGLE_RECAPTCHA_WEB) {

                        event.preventDefault();

                        grecaptcha.ready(function() {
                            grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'submit'}).then(function(token) {

                                $('#login-form').prepend('<input type="hidden" name="recaptcha_token" value="'+ token + '">');
                                $('#login-form').unbind('submit').submit();
                            });
                        });
                    }
                });

            </script>

        </div>


    </div>

</body
</html>