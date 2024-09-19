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

    $accountId = auth::getCurrentUserId();
    $account = new account($dbo, $accountId);
    $accountInfo = $account->get();

    $error = false;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $login = isset($_POST['clogin']) ? $_POST['clogin'] : '';
        $password = isset($_POST['cpassword']) ? $_POST['cpassword'] : '';

        $old_password = isset($_POST['old_password']) ? $_POST['old_password'] : '';
        $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

        $login = helper::clearText($login);
        $password = helper::clearText($password);

        $login = helper::escapeText($login);
        $password = helper::escapeText($password);

        $old_password = helper::clearText($old_password);
        $new_password = helper::clearText($new_password);

        $old_password = helper::clearText($old_password);
        $new_password = helper::clearText($new_password);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($accountInfo['account_free'] != 0) {

                // Create Login and Password

                $result = $account->createPassword($login, $password);

                if (!$result['error']) {

                    // Password and login created

                    header("Location: /account/settings/password/?error=false&error_code=5");
                    exit;

                } else {

                    if ($result['error_type'] == 0) {

                        // Error login

                        header("Location: /account/settings/password?error=true&error_code=1");
                        exit;

                    } else {

                        // Error password

                        header("Location: /account/settings/password?error=true&error_code=2");
                        exit;
                    }
                }

            } else {

                // Change Password

                if (helper::isCorrectPassword($new_password)) {

                    $result = array();

                    $result = $account->setPassword($old_password, $new_password);

                    if (!$result['error']) {

                        // New password saved

                        header("Location: /account/settings/password/?error=false&error_code=4");
                        exit;

                    } else {

                        // Error Old password

                        header("Location: /account/settings/password?error=true&error_code=3");
                        exit;
                    }

                } else {

                    // Error password

                    header("Location: /account/settings/password?error=true&error_code=2");
                    exit;
                }
            }
        }

        header("Location: /account/settings/password?error=true&error_code=0");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "settings_password";
    $page_ctitle = $LANG['page-profile-password'];

    if ($accountInfo['account_free'] != 0) {

        $page_ctitle = $LANG['label-login-create'];
    }

    $css_files = array();
    $page_title = $page_ctitle." | ".APP_TITLE;

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

                        <h1 class="title"><?php echo $page_ctitle; ?></h1>

                        <form accept-charset="UTF-8" action="/account/settings/password" autocomplete="off" class="edit_user" id="settings-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                            <div class="tabbed-content">

                                <?php

                                if (isset($_GET['error_code'])) {

                                    $error_code = $_GET['error_code'];

                                    switch ($error_code) {

                                        case 1: {

                                            // Error login

                                            ?>

                                            <div class="alert alert-danger">
                                                <ul>
                                                    <?php echo $LANG['label-login-create-error']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }

                                        case 2: {

                                            ?>

                                            <div class="alert alert-danger">
                                                <ul>
                                                    <?php echo $LANG['msg-password-incorrect']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }

                                        case 3: {

                                            ?>

                                            <div class="alert alert-danger">
                                                <ul>
                                                    <?php echo $LANG['label-password-old-error']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }

                                        case 4: {

                                            // New password saved

                                            ?>

                                            <div class="alert alert-success">
                                                <ul>
                                                    <?php echo $LANG['label-password-saved']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }

                                        case 5: {

                                            // Password and login created

                                            ?>

                                            <div class="alert alert-success">
                                                <ul>
                                                    <?php echo $LANG['label-login-create-success']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }

                                        default: {

                                            // Error token

                                            ?>

                                            <div class="alert alert-danger">
                                                <ul>
                                                    <?php echo $LANG['msg-error-unknown']; ?>
                                                </ul>
                                            </div>

                                            <?php

                                            break;
                                        }
                                    }
                                }
                                ?>

                                <div class="tab-pane active form-table">

                                    <?php

                                        if ($accountInfo['account_free'] != 0) {

                                            ?>
                                                <div class="profile-basics form-row">
                                                    <div class="form-cell left">
                                                        <p class="info"><?php echo $LANG['label-login-current'].": ".$accountInfo['username']; ?></p>
                                                        <p class="info"><?php echo $LANG['label-login-create-sub-title']; ?></p>
                                                    </div>

                                                    <div class="form-cell">
                                                        <input id="clogin" name="clogin" placeholder="<?php echo $LANG['label-login-new']; ?>" maxlength="32" type="text" value="">
                                                        <input id="cpassword" name="cpassword" placeholder="<?php echo $LANG['label-new-password']; ?>" maxlength="32" type="password" value="">
                                                    </div>
                                                </div>
                                            <?php

                                        } else {

                                            ?>
                                                <div class="profile-basics form-row">
                                                    <div class="form-cell left">
                                                        <p class="info"><?php echo $LANG['label-settings-password-sub-title']; ?></p>
                                                    </div>

                                                    <div class="form-cell">
                                                        <input id="old_password" name="old_password" placeholder="<?php echo $LANG['label-old-password']; ?>" maxlength="32" type="password" value="">
                                                        <input id="new_password" name="new_password" placeholder="<?php echo $LANG['label-new-password']; ?>" maxlength="32" type="password" value="">

                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    ?>

                                </div>

                            </div>

                            <input style="margin-top: 25px" name="commit" class="button primary" type="submit" value="<?php echo $LANG['action-save']; ?>">

                        </form>
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