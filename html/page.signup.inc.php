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
    }

    include_once('../sys/config/gconfig.inc.php');
    require_once '../sys/addons/vendor/autoload.php';

    $user_username = '';
    $user_email = '';
    $user_fullname = '';
    $user_referrer = 0;

    $error = false;
    $error_message = array();

    if (!empty($_POST)) {

        $error = false;

        $user_username = isset($_POST['username']) ? $_POST['username'] : '';
        $user_fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $user_password = isset($_POST['password']) ? $_POST['password'] : '';
        $user_email = isset($_POST['email']) ? $_POST['email'] : '';
        $user_referrer = isset($_POST['referrer']) ? $_POST['referrer'] : 0;
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $recaptcha_token = isset($_POST['recaptcha_token']) ? $_POST['recaptcha_token'] : '';

        $user_referrer = helper::clearInt($user_referrer);

        $user_username = helper::clearText($user_username);
        $user_fullname = helper::clearText($user_fullname);
        $user_password = helper::clearText($user_password);
        $user_email = helper::clearText($user_email);

        $user_username = helper::escapeText($user_username);
        $user_fullname = helper::escapeText($user_fullname);
        $user_password = helper::escapeText($user_password);
        $user_email = helper::escapeText($user_email);

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
            $error_token = true;
            $error_message[] = $LANG['msg-error-unknown'];
        }

        if (!helper::isCorrectLogin($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = $LANG['msg-login-incorrect'];
        }

        if ($helper->isLoginExists($user_username)) {

            $error = true;
            $error_username = true;
            $error_message[] = $LANG['msg-login-taken'];
        }

        if (!helper::isCorrectFullname($user_fullname)) {

            $error = true;
            $error_fullname = true;
            $error_message[] = $LANG['msg-fullname-incorrect'];
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_password = true;
            $error_message[] = $LANG['msg-password-incorrect'];
        }

        if (!helper::isCorrectEmail($user_email)) {

            $error = true;
            $error_email = true;
            $error_message[] = $LANG['msg-email-incorrect'];
        }

        if ($helper->isEmailExists($user_email)) {

            $error = true;
            $error_email = true;
            $error_message[] = $LANG['msg-email-taken'];
        }

        if (!$error) {

            $account = new account($dbo);

            $result = array();
            $result = $account->signup($user_username, $user_fullname, $user_password, $user_email, $LANG['lang-code']);

            if (!$result['error']) {

                $clientId = 0; // Desktop version

                $auth = new auth($dbo);
                $access_data = $auth->create($result['accountId'], $clientId, APP_TYPE_WEB, "", $LANG['lang-code']);

                $account = new account($dbo, $access_data['accountId']);
                $accountInfo = $account->get();

                if (!$access_data['error']) {

                    auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                    auth::setCurrentUserOtpVerified($accountInfo['otpVerified']);
                    auth::updateCookie($user_username, $access_data['accessToken']);

                    $language = $account->getLanguage();

                    $account->setLastActive();

                    // refsys

                    if ($user_referrer != 0) {

                        $ref = new refsys($dbo);
                        $ref->setRequestFrom($account->getId());
                        $ref->setBonus(BONUS_REFERRAL);
                        $ref->setReferrer($user_referrer);

                        unset($ref);
                    }

                    //Facebook connect

                    if (isset($_SESSION['oauth'])) {

                        switch ($_SESSION['oauth']) {

                            case 'facebook': {

                                if ($helper->getUserIdByFacebook($_SESSION['oauth_id']) == 0) {

                                    $account->setFacebookId($_SESSION['oauth_id']);

                                    $time = time();
                                    $fb_id = $_SESSION['oauth_id'];

                                    $img = @file_get_contents('https://graph.facebook.com/'.$fb_id.'/picture?type=large');
                                    $file =  TEMP_PATH.$time.".jpg";
                                    @file_put_contents($file, $img);

                                    $imglib = new imglib($dbo);
                                    $response = $imglib->createPhoto($file, $file);
                                    unset($imglib);

                                    if ($response['error'] === false) {

                                        $account->setPhoto($response);
                                    }
                                }

                                break;
                            }

                            default: {

                                // google signin

                                if ($helper->getUserIdByGoogle($_SESSION['oauth_id']) == 0) {

                                    $account->setGoogleFirebaseId($_SESSION['oauth_id']);
                                }
                            }
                        }
                    }

                    unset($_SESSION['oauth']);
                    unset($_SESSION['oauth_id']);
                    unset($_SESSION['oauth_name']);
                    unset($_SESSION['oauth_email']);
                    unset($_SESSION['oauth_link']);

                    $_SESSION['welcome_hash'] = helper::generateHash(5);

                    header("Location: /account/welcome");
                    exit;
                }

            } else {

                $error = true;
                $error_message[] = "You can not create multi-accounts!";
            }
        }
    }

    if (isset($_SESSION['oauth']) && empty($user_username) && empty($user_email)) {

        $user_fullname = $_SESSION['oauth_name'];
        $user_email = $_SESSION['oauth_email'];
    }

    auth::newAuthenticityToken();

    $page_id = "signup";

    $css_files = array("landing.css");
    $page_title = $LANG['page-signup']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="home has-bottom-footer signup-page">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="content-page">

        <div class="limiter">

            <div class="container-login100" style="margin-bottom: 50px">

                <div class="wrap-login100">

                    <form accept-charset="UTF-8" action="/signup" class="custom-form login100-form" id="signup-form" method="post">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                        <span class="login100-form-title "><?php echo $LANG['page-signup']; ?></span>

                        <?php

                        if (isset($_SESSION['oauth'])) {

                            ?>

                            <div class="opt-in">
                                <label for="user_receive_digest">
                                    <?php

                                        if ($_SESSION['oauth'] === 'facebook') {

                                            $headers = get_headers('https://graph.facebook.com/'.$_SESSION['oauth_id'].'/picture',1);
                                        }

                                        if (isset($headers) && isset($headers['Location'])) {

                                            $url = $headers['Location']; // string

                                            ?>

                                            <img src="<?php echo $url; ?>" alt="" style="width: 50px; float: left">

                                            <?php

                                        } else {

                                            if (isset($_SESSION['oauth_img_link']) && strlen($_SESSION['oauth_img_link']) != 0) {

                                                ?>
                                                <img src="<?php echo $_SESSION['oauth_img_link']; ?>" alt="" style="width: 50px; float: left">
                                                <?php

                                            } else {

                                                ?>
                                                <img src="\img\profile_default_photo.png" alt="" style="width: 50px; float: left">
                                                <?php

                                            }
                                        }
                                    ?>

                                    <div style="padding-left: 60px;">

                                        <?php

                                            if ($_SESSION['oauth'] === 'facebook') {

                                                ?>
                                                    <b><a target="_blank" href="https://www.facebook.com/app_scoped_user_id/<?php echo $_SESSION['oauth_id']; ?>"><?php echo $_SESSION['oauth_name']; ?></a></b>
                                                    <span><?php echo $LANG['label-authorization-with-facebook']; ?></span>

                                                <?php

                                            } else {

                                                ?>
                                                    <b><?php echo $_SESSION['oauth_name']; ?></b>
                                                    <span><?php echo $LANG['label-authorization-with-google']; ?></span>
                                                <?php
                                            }
                                        ?>

                                        <br>
                                        <a href="/facebook"><?php echo $LANG['action-back-to-default-signup']; ?></a>
                                    </div>
                                </label>
                            </div>

                            <?php

                        } else {

                            if (FACEBOOK_AUTHORIZATION) {

                                ?>

                                    <p>
                                        <a class="fb-icon-btn fb-btn-large btn-social btn-facebook" href="/facebook/signup">
                                            <span class="icon-container">
                                                <i class="icon icon-facebook"></i>
                                            </span>
                                            <span><?php echo $LANG['action-signup-with'] . " " . $LANG['label-facebook']; ?></span>
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
                        }

                        ?>

                        <div class="alert alert-danger" style="<?php if (!$error) echo "display: none"; ?>">
                            <h3><?php echo $LANG['label-errors-title']; ?></h3>
                            <ul>
                                <?php

                                foreach ($error_message as $key => $value) {

                                    echo "<li>{$value}</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <input id="username" name="username" placeholder="<?php echo $LANG['label-username']; ?>" required="required" size="30" type="text" value="<?php echo $user_username; ?>">
                        <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-fullname']; ?>" required="required" size="30" type="text" value="<?php echo $user_fullname; ?>">
                        <input id="password" name="password" placeholder="<?php echo $LANG['label-password']; ?>" required="required" size="30" type="password" value="">
                        <input id="email" name="email" placeholder="<?php echo $LANG['label-email']; ?>" required="required" size="48" type="text" value="<?php echo $user_email; ?>">

                        <div class="opt-in">
                            <label for="user_receive_digest">
                                <?php echo $LANG['label-signup-invite']; ?>
                            </label>
                        </div>

                        <input style="margin-bottom: 15px;" id="referrer" name="referrer" placeholder="<?php echo $LANG['label-user-id']; ?>" size="8" type="number" value="<?php echo $user_referrer; ?>">

                        <div class="opt-in">
                            <label for="user_receive_digest">
                                <b><?php echo $LANG['label-signup-confirm']; ?></b>
                                <a style="font-size: 0.8rem;" href="/terms"><?php echo $LANG['page-terms']; ?></a>
                            </label>
                        </div>

                        <input class="submit-button primary button" name="commit" type="submit" value="<?php echo $LANG['action-signup']; ?>">
                    </form>

                    <div class="login100-more">
                        <div class="login100_content">
                            <h1 class="mb-10"><?php echo $LANG['label-signup-sub-title']; ?></h1>
                            <p><?php echo $LANG['main-page-prompt-app']; ?></p>
                        </div>
                    </div>

                </div>

            </div>

            <?php

                include_once("../html/common/footer.inc.php");
            ?>

            <script>

                $('#signup-form').submit(function(event) {

                    if (constants.GOOGLE_RECAPTCHA_WEB) {

                        event.preventDefault();

                        grecaptcha.ready(function() {
                            grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'submit'}).then(function(token) {

                                $('#signup-form').prepend('<input type="hidden" name="recaptcha_token" value="'+ token + '">');
                                $('#signup-form').unbind('submit').submit();
                            });
                        });
                    }
                });
            </script>

        </div>


    </div>



</body
</html>