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

    $groupId = $helper->getUserId($request[0]);
    $groupAccountId = $helper->getUserId($request[0]);

    $group = new group($dbo, $groupId);
    $group->setRequestFrom($accountId);

    $groupInfo = $group->get();

    if ($groupInfo['state'] != ACCOUNT_STATE_ENABLED) {

        header('Location: /');
        exit;
    }

    if ($groupInfo['accountType'] == ACCOUNT_TYPE_USER) {

        header('Location: /');
        exit;
    }

    if ($groupInfo['accountAuthor'] != $accountId) {

        header('Location: /');
        exit;
    }

    $error = false;
    $send_status = false;
    $fullname = "";
    $group_category = 0;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowComments = isset($_POST['allowComments']) ? $_POST['allowComments'] : '';
        $allowPosts = isset($_POST['allowPosts']) ? $_POST['allowPosts'] : '';

        $group_category = isset($_POST['group_category']) ? $_POST['group_category'] : 0;

        $day = isset($_POST['day']) ? $_POST['day'] : 0;
        $month = isset($_POST['month']) ? $_POST['month'] : 0;
        $year = isset($_POST['year']) ? $_POST['year'] : 0;

        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';
        $my_page = isset($_POST['my_page']) ? $_POST['my_page'] : '';

        $allowComments = helper::clearText($allowComments);
        $allowComments = helper::escapeText($allowComments);

        $allowPosts = helper::clearText($allowPosts);
        $allowPosts = helper::escapeText($allowPosts);

        $group_category = helper::clearInt($group_category);

        $day = helper::clearInt($day);
        $month = helper::clearInt($month);
        $year = helper::clearInt($year);

        $fullname = helper::clearText($fullname);
        $fullname = helper::escapeText($fullname);

        $status = helper::clearText($status);
        $status = helper::escapeText($status);

        $location = helper::clearText($location);
        $location = helper::escapeText($location);

        $my_page = helper::clearText($my_page);
        $my_page = helper::escapeText($my_page);

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!$error) {

            if ($allowComments === "on") {

                $group->setAllowComments(1);

            } else {

                $group->setAllowComments(0);
            }

            if ($allowPosts === "on") {

                $group->setAllowPosts(1);

            } else {

                $group->setAllowPosts(0);
            }

//            $account->edit($fullname);

            if (helper::isCorrectFullname($fullname)) {

                $group->edit($fullname);
            }

            $group->setBirth($year, $month, $day);
            $group->setStatus($status);
            $group->setLocation($location);
            $group->setCategory($group_category);

            if (helper::isValidURL($my_page)) {

                $group->setWebPage($my_page);

            } else {

                $group->setWebPage("");
            }

            header("Location: /".$request[0]."/settings/?error=false");
            exit;
        }

        header("Location: /".$request[0]."/settings/?error=true");
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "group_settings";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-settings']." | ".APP_TITLE;

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

            <div class="row col sn-content sn-content-wide-block" id="content">

                <div class="main-content profile-content">

                    <div class="standard-page" style="padding-bottom: 0">
                        <div class="tab-container" style="">
                            <nav class="tabs">
                                <a href="/<?php echo $groupInfo['username']; ?>"><span class="tab"><?php echo $groupInfo['fullname']; ?></span></a>
                                <a href="/<?php echo $groupInfo['username']; ?>/settings"><span class="tab active"><?php echo $LANG['page-settings']; ?></span></a>
                            </nav>
                        </div>
                    </div>

                    <div class="profile-content standard-page">

                        <form accept-charset="UTF-8" action="/<?php echo $groupInfo['username']; ?>/settings" autocomplete="off" class="edit_user" id="settings-form" method="post">

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

                                            <div class="success-container" style="margin-top: 15px;">
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

                                    <div class="link-preference form-row" style="border-bottom: 0; padding-bottom: 0;">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-group-privacy']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowComments" name="allowComments" type="checkbox" <?php if ($groupInfo['allowComments'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowComments"><?php echo $LANG['label-group-allow-comments']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <input id="allowPosts" name="allowPosts" type="checkbox" <?php if ($groupInfo['allowPosts'] == 1) echo "checked=\"checked\""; ?>>
                                                <label for="allowPosts"><?php echo $LANG['label-group-allow-posts']; ?></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="profile-basics form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-settings-main-section-title']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-group-fullname']; ?>" maxlength="64" type="text" value="<?php echo $groupInfo['fullname']; ?>">
                                            <input id="location" name="location" placeholder="<?php echo $LANG['label-group-location']; ?>" maxlength="64" type="text" value="<?php echo $groupInfo['location']; ?>">
                                            <input id="my_page" name="my_page" placeholder="<?php echo $LANG['label-group-web-page']; ?>" maxlength="255" type="text" value="<?php echo $groupInfo['myPage']; ?>">
                                            <textarea placeholder="<?php echo $LANG['label-group-status']; ?>" id="status" name="status" maxlength="400"><?php echo $groupInfo['status']; ?></textarea>

                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-group-category']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">
                                                <select id="group_category" name="group_category" class="selectBox">
                                                    <?php

                                                    for ($i = 0; $i < 42; $i++) {

                                                        $lang_option  = "group-category_".$i;
                                                        ?>
                                                        <option value="<?php echo $i; ?>" <?php if ($groupInfo['accountCategory'] == $i) echo "selected=\"selected\""; ?>><?php echo $LANG[$lang_option]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="link-preference form-row">
                                        <div class="form-cell left">
                                            <h2><?php echo $LANG['label-group-date']; ?></h2>
                                        </div>

                                        <div class="form-cell">
                                            <div class="opt-in">

                                                <select id="day" name="day" class="selectBox" style="width: 30%;">

                                                    <?php

                                                    for ($day = 1; $day <= 31; $day++) {

                                                        if ($day == $groupInfo['day']) {

                                                            echo "<option value=\"$day\" selected=\"selected\">$day</option>";

                                                        } else {

                                                            echo "<option value=\"$day\">$day</option>";
                                                        }
                                                    }
                                                    ?>

                                                </select>

                                                <select id="month" name="month" class="selectBox" style="width: 30%;">
                                                    <option value="0" <?php if ($groupInfo['month'] == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['month-jan']; ?></option>
                                                    <option value="1" <?php if ($groupInfo['month'] == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['month-feb']; ?></option>
                                                    <option value="2" <?php if ($groupInfo['month'] == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['month-mar']; ?></option>
                                                    <option value="3" <?php if ($groupInfo['month'] == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['month-apr']; ?></option>
                                                    <option value="4" <?php if ($groupInfo['month'] == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['month-may']; ?></option>
                                                    <option value="5" <?php if ($groupInfo['month'] == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['month-june']; ?></option>
                                                    <option value="6" <?php if ($groupInfo['month'] == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['month-july']; ?></option>
                                                    <option value="7" <?php if ($groupInfo['month'] == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['month-aug']; ?></option>
                                                    <option value="8" <?php if ($groupInfo['month'] == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['month-sept']; ?></option>
                                                    <option value="9" <?php if ($groupInfo['month'] == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['month-oct']; ?></option>
                                                    <option value="10" <?php if ($groupInfo['month'] == 10) echo "selected=\"selected\""; ?>><?php echo $LANG['month-nov']; ?></option>
                                                    <option value="11" <?php if ($groupInfo['month'] == 11) echo "selected=\"selected\""; ?>><?php echo $LANG['month-dec']; ?></option>
                                                </select>

                                                <select id="year" name="year" class="selectBox" style="width: 30%;">

                                                    <?php

                                                    $current_year = date("Y");

                                                    for ($year = 1915; $year <= $current_year; $year++) {

                                                        if ($year == $groupInfo['year']) {

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

                            <input style="margin-top: 25px" name="commit" class="blue button" type="submit" value="<?php echo $LANG['action-save']; ?>">

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