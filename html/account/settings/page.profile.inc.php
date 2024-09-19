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

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowComments = isset($_POST['allowComments']) ? $_POST['allowComments'] : '';
        $allowGalleryComments = isset($_POST['allowGalleryComments']) ? $_POST['allowGalleryComments'] : '';
        $allowMessages = isset($_POST['allowMessages']) ? $_POST['allowMessages'] : '';

        $gender = isset($_POST['gender']) ? $_POST['gender'] : 0;

        $day = isset($_POST['day']) ? $_POST['day'] : 0;
        $month = isset($_POST['month']) ? $_POST['month'] : 0;
        $year = isset($_POST['year']) ? $_POST['year'] : 0;

        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';
        $facebook_page = isset($_POST['facebook_page']) ? $_POST['facebook_page'] : '';
        $instagram_page = isset($_POST['instagram_page']) ? $_POST['instagram_page'] : '';

        $allowComments = helper::clearText($allowComments);
        $allowComments = helper::escapeText($allowComments);

        $allowGalleryComments = helper::clearText($allowGalleryComments);
        $allowGalleryComments = helper::escapeText($allowGalleryComments);

        $allowMessages = helper::clearText($allowMessages);
        $allowMessages = helper::escapeText($allowMessages);

        $gender = helper::clearInt($gender);

        $day = helper::clearInt($day);
        $month = helper::clearInt($month);
        $year = helper::clearInt($year);

        $fullname = helper::clearText($fullname);
        $fullname = helper::escapeText($fullname);

        $status = helper::clearText($status);
        $status = helper::escapeText($status);

        $location = helper::clearText($location);
        $location = helper::escapeText($location);

        $facebook_page = helper::clearText($facebook_page);
        $facebook_page = helper::escapeText($facebook_page);

        $instagram_page = helper::clearText($instagram_page);
        $instagram_page = helper::escapeText($instagram_page);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($allowComments === "on") {

                $account->setAllowComments(1);

            } else {

                $account->setAllowComments(0);
            }

            if ($allowGalleryComments === "on") {

                $account->setAllowGalleryComments(1);

            } else {

                $account->setAllowGalleryComments(0);
            }

            if ($allowMessages === "on") {

                $account->setAllowMessages(1);

            } else {

                $account->setAllowMessages(0);
            }

//            $account->edit($fullname);

            if (helper::isCorrectFullname($fullname)) {

                $account->edit($fullname);
            }

            $account->setSex($gender);
            $account->setBirth($year, $month, $day);
            $account->setStatus($status);
            $account->setLocation($location);

            if (helper::isValidURL($facebook_page)) {

                $account->setFacebookPage($facebook_page);

            } else {

                $account->setFacebookPage("");
            }

            if (helper::isValidURL($instagram_page)) {

                $account->setInstagramPage($instagram_page);

            } else {

                $account->setInstagramPage("");
            }

            header("Location: /account/settings/profile/?error=false");
            exit;
        }

        header("Location: /account/settings/profile/?error=true");
        exit;
    }

    $accountInfo = $account->get();

    auth::newAuthenticityToken();

    $page_id = "settings_profile";

    $css_files = array();
    $page_title = $LANG['page-profile-settings']." | ".APP_TITLE;

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

                        <h1 class="title"><?php echo $LANG['page-profile-settings']; ?></h1>

                        <form accept-charset="UTF-8" action="/account/settings/profile" autocomplete="off" class="edit_user" id="settings-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                            <div class="tabbed-content">

                                <?php

                                if ( isset($_GET['error']) ) {

                                    switch ($_GET['error']) {

                                        case "true" : {

                                            ?>

                                            <div class="errors-container" style="margin-top: 15px;">
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

                                <div class="errors-container" style="margin-top: 15px; <?php if (!$error) echo "display: none"; ?>">
                                    <ul>
                                        <?php echo $LANG['ticket-send-error']; ?>
                                    </ul>
                                </div>

                                <div class="tab-pane active form-table">

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-posts-privacy']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowComments" name="allowComments" type="checkbox" <?php if ($accountInfo['allowComments'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowComments"><?php echo $LANG['label-comments-allow']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-gallery-privacy']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowGalleryComments" name="allowGalleryComments" type="checkbox" <?php if ($accountInfo['allowGalleryComments'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowGalleryComments"><?php echo $LANG['label-gallery-comments-allow']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-messages-privacy']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowMessages" name="allowMessages" type="checkbox" <?php if ($accountInfo['allowMessages'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowMessages"><?php echo $LANG['label-messages-allow']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="profile-basics form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-settings-main-section-title']; ?></h2>
                                            <p class="info"><?php echo $LANG['label-settings-main-section-sub-title']; ?></p>
                                        </div>

                                        <div class="form-cell">
                                            <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-fullname']; ?>" maxlength="64" type="text" value="<?php echo $accountInfo['fullname']; ?>">
                                            <input id="location" name="location" placeholder="<?php echo $LANG['label-location']; ?>" maxlength="64" type="text" value="<?php echo $accountInfo['location']; ?>">
                                            <input id="facebook_page" name="facebook_page" placeholder="<?php echo $LANG['label-facebook-link']; ?>" maxlength="255" type="text" value="<?php echo $accountInfo['fb_page']; ?>">
                                            <input id="instagram_page" name="instagram_page" placeholder="<?php echo $LANG['label-instagram-link']; ?>" maxlength="255" type="text" value="<?php echo $accountInfo['instagram_page']; ?>">
                                            <textarea placeholder="<?php echo $LANG['label-status']; ?>" id="status" name="status" maxlength="400"><?php echo $accountInfo['status']; ?></textarea>

                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-gender']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <select id="gender" name="gender" class="selectBox">
                                                    <option value="0" <?php if ($accountInfo['sex'] != SEX_FEMALE && $accountInfo['sex'] != SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-unknown']; ?></option>
                                                    <option value="1" <?php if ($accountInfo['sex'] == SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-male']; ?></option>
                                                    <option value="2" <?php if ($accountInfo['sex'] == SEX_FEMALE) echo "selected=\"selected\""; ?>><?php echo $LANG['gender-female']; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-birth-date']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <select id="day" name="day" class="selectBox" style="width: 30%;">

                                                    <?php

                                                    for ($day = 1; $day <= 31; $day++) {

                                                        if ($day == $accountInfo['day']) {

                                                            echo "<option value=\"$day\" selected=\"selected\">$day</option>";

                                                        } else {

                                                            echo "<option value=\"$day\">$day</option>";
                                                        }
                                                    }
                                                    ?>

                                                </select>

                                                <select id="month" name="month" class="selectBox" style="width: 30%;">
                                                    <option value="0" <?php if ($accountInfo['month'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['month-jan']; ?></option>
                                                    <option value="1" <?php if ($accountInfo['month'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['month-feb']; ?></option>
                                                    <option value="2" <?php if ($accountInfo['month'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['month-mar']; ?></option>
                                                    <option value="3" <?php if ($accountInfo['month'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['month-apr']; ?></option>
                                                    <option value="4" <?php if ($accountInfo['month'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['month-may']; ?></option>
                                                    <option value="5" <?php if ($accountInfo['month'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['month-june']; ?></option>
                                                    <option value="6" <?php if ($accountInfo['month'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['month-july']; ?></option>
                                                    <option value="7" <?php if ($accountInfo['month'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['month-aug']; ?></option>
                                                    <option value="8" <?php if ($accountInfo['month'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['month-sept']; ?></option>
                                                    <option value="9" <?php if ($accountInfo['month'] == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['month-oct']; ?></option>
                                                    <option value="10" <?php if ($accountInfo['month'] == 10) echo "selected=\"selected\""; ?>><?php echo $LANG['month-nov']; ?></option>
                                                    <option value="11" <?php if ($accountInfo['month'] == 11) echo "selected=\"selected\""; ?>><?php echo $LANG['month-dec']; ?></option>
                                                </select>

                                                <select id="year" name="year" class="selectBox" style="width: 30%;">

                                                    <?php

                                                    $current_year = date("Y");

                                                    for ($year = 1915; $year <= $current_year; $year++) {

                                                        if ($year == $accountInfo['year']) {

                                                            echo "<option value=\"$year\" selected=\"selected\">$year</option>";

                                                        } else {

                                                            echo "<option value=\"$year\">$year</option>";
                                                        }
                                                    }
                                                    ?>

                                                </select>
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