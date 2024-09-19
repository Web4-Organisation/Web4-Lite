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

    if (isset($_GET['hash'])) {

        $hash = isset($_GET['hash']) ? $_GET['hash'] : '';

        $hash = helper::clearText($hash);
        $hash = helper::escapeText($hash);

        $restorePointInfo = $helper->getRestorePoint($hash);

        if ($restorePointInfo['error'] !== false) {

            header("Location: /");
        }

    } else {

        header("Location: /");
    }


    $error = false;
    $error_message = array();

    $user_password = '';
    $user_password_repeat = '';

    if (!empty($_POST)) {

        $error = false;

        $user_password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
        $user_password_repeat = isset($_POST['user_password_repeat']) ? $_POST['user_password_repeat'] : '';
        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $user_password = helper::clearText($user_password);
        $user_password_repeat = helper::clearText($user_password_repeat);

        $user_password = helper::escapeText($user_password);
        $user_password_repeat = helper::escapeText($user_password_repeat);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
            $error_message[] = 'Error!';
        }

        if (!helper::isCorrectPassword($user_password)) {

            $error = true;
            $error_message[] = 'Incorrect password.';
        }

        if ($user_password != $user_password_repeat) {

            $error = true;
            $error_message[] = 'Passwords do not match.';
        }

        if (!$error) {

            $account = new account($dbo, $restorePointInfo['accountId']);

            $account->newPassword($user_password);
            $account->restorePointRemove();

            header("Location: /restore/success");
            exit;
        }
    }

    auth::newAuthenticityToken();

    $page_id = "restore";

    $css_files = array("landing.css");
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="home has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="content-page">

        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">

                    <div class="standard-page">

                        <h1><?php echo $LANG['page-restore']; ?></h1>

                        <form accept-charset="UTF-8" action="/restore/?hash=<?php echo $hash; ?>" class="custom-form" id="remind-page" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                            <div class="alert alert-danger" style="<?php if (!$error) echo "display: none"; ?>">
                                <h3><?php echo $LANG['label-errors-title']; ?></h3>
                                <ul>
                                    <?php

                                    foreach ($error_message as $msg) {

                                        echo "<li>{$msg}</li>";
                                    }
                                    ?>
                                </ul>
                            </div>

                            <input id="user_password" name="user_password" placeholder="<?php echo $LANG['label-new-password']; ?>" required="required" size="30" type="password" value="">
                            <input id="user_password_repeat" name="user_password_repeat" placeholder="<?php echo $LANG['label-repeat-password']; ?>" required="required" size="30" type="password" value="">

                            <div class="login-button">
                                <input class="submit-button primary button" name="commit" type="submit" value="<?php echo $LANG['action-change']; ?>">
                            </div>
                        </form>
                    </div>

                </div>

            </div>

            <?php

                include_once("../html/common/footer.inc.php");
            ?>

        </div>


    </div>



</body
</html>