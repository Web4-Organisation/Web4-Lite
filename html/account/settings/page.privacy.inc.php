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

    $error = false;
    $send_status = false;
    $fullname = "";

    $allowVideoCalls = 1;

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowShowMyFriends = isset($_POST['allowShowMyFriends']) ? $_POST['allowShowMyFriends'] : '';
        $allowShowMyGallery = isset($_POST['allowShowMyGallery']) ? $_POST['allowShowMyGallery'] : '';
        $allowShowMyGifts = isset($_POST['allowShowMyGifts']) ? $_POST['allowShowMyGifts'] : '';
        $allowShowMyInfo = isset($_POST['allowShowMyInfo']) ? $_POST['allowShowMyInfo'] : '';

        $allowShowMyFriends = helper::clearText($allowShowMyFriends);
        $allowShowMyFriends = helper::escapeText($allowShowMyFriends);

        $allowShowMyGallery = helper::clearText($allowShowMyGallery);
        $allowShowMyGallery = helper::escapeText($allowShowMyGallery);

        $allowShowMyGifts = helper::clearText($allowShowMyGifts);
        $allowShowMyGifts = helper::escapeText($allowShowMyGifts);

        $allowShowMyInfo = helper::clearText($allowShowMyInfo);
        $allowShowMyInfo = helper::escapeText($allowShowMyInfo);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($allowShowMyGallery === "on") {

                $allowShowMyGallery = 1;

            } else {

                $allowShowMyGallery = 0;
            }

            if ($allowShowMyGifts === "on") {

                $allowShowMyGifts = 1;

            } else {

                $allowShowMyGifts = 0;
            }

            if ($allowShowMyFriends === "on") {

                $allowShowMyFriends = 1;

            } else {

                $allowShowMyFriends = 0;
            }

            if ($allowShowMyInfo === "on") {

                $allowShowMyInfo = 1;

            } else {

                $allowShowMyInfo = 0;
            }

            $account->setPrivacySettings($allowShowMyGallery, $allowShowMyGifts, $allowShowMyFriends, $allowShowMyInfo, $allowVideoCalls);

            header("Location: /account/settings/privacy/?error=false");
            exit;
        }

        header("Location: /account/settings/privacy/?error=true");
        exit;
    }

    $accountInfo = $account->get();

    auth::newAuthenticityToken();

    $page_id = "settings_privacy";

    $css_files = array();
    $page_title = $LANG['page-privacy-settings']." | ".APP_TITLE;

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

                        <h1 class="title"><?php echo $LANG['page-privacy-settings']; ?></h1>

                        <form accept-charset="UTF-8" action="/account/settings/privacy" autocomplete="off" class="edit_user" id="settings-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                            <div class="tabbed-content">

                                <?php

                                    if (isset($_GET['error']) ) {

                                        switch ($_GET['error']) {

                                            case "true" : {

                                                ?>

                                                <div class="alert alert-danger">
                                                    <ul>
                                                        <?php echo $LANG['msg-error-unknown']; ?>
                                                    </ul>
                                                </div>

                                                <?php

                                                break;
                                            }

                                            default: {

                                                ?>

                                                <div class="alert alert-success">
                                                    <ul>
                                                        <b><?php echo $LANG['label-thanks']; ?></b>
                                                        <br>
                                                        <?php echo $LANG['label-settings-saved']; ?>
                                                    </ul>
                                                </div>

                                                <?php

                                                break;
                                            }
                                        }
                                    }
                                ?>

                                <div class="tab-pane active form-table">

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-allow-show-friends-desc']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowShowMyFriends" name="allowShowMyFriends" type="checkbox" <?php if ($accountInfo['allowShowMyFriends'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowShowMyFriends"><?php echo $LANG['label-allow-show-friends']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-allow-show-gallery-desc']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowShowMyGallery" name="allowShowMyGallery" type="checkbox" <?php if ($accountInfo['allowShowMyGallery'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowShowMyGallery"><?php echo $LANG['label-allow-show-gallery']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-allow-show-gifts-desc']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowShowMyGifts" name="allowShowMyGifts" type="checkbox" <?php if ($accountInfo['allowShowMyGifts'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowShowMyGifts"><?php echo $LANG['label-allow-show-gifts']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-allow-show-info-desc']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowShowMyInfo" name="allowShowMyInfo" type="checkbox" <?php if ($accountInfo['allowShowMyInfo'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowShowMyInfo"><?php echo $LANG['label-allow-show-info']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <input style="margin-top: 25px" class="button primary" name="commit" type="submit" value="<?php echo $LANG['action-save']; ?>">

                        </form>
                    </div>


                </div>
            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        $('textarea[name=status]').autosize();

    </script>


</body
</html>