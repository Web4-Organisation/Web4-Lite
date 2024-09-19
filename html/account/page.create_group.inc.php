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
    $error_type = 0;
    $send_status = false;
    $fullname = "";
    $username = "";
    $status = "";
    $location = "";
    $my_page = "";
    $group_category = 0;
    $year = 1916;
    $month = 1;
    $day = 1;
    $allowPosts = 1;
    $allowComments = 1;

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowComments = isset($_POST['allowComments']) ? $_POST['allowComments'] : '';
        $allowPosts = isset($_POST['allowPosts']) ? $_POST['allowPosts'] : '';

        $group_category = isset($_POST['group_category']) ? $_POST['group_category'] : 0;

        $day = isset($_POST['day']) ? $_POST['day'] : 0;
        $month = isset($_POST['month']) ? $_POST['month'] : 0;
        $year = isset($_POST['year']) ? $_POST['year'] : 0;

        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
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

        $username = helper::clearText($username);
        $username = helper::escapeText($username);

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

                $allowComments = 1;

            } else {

                $allowComments = 0;
            }

            if ($allowPosts === "on") {

                $allowPosts = 1;

            } else {

                $allowPosts = 0;
            }

            $group = new group($dbo);
            $group->setRequestFrom($accountId);

            $result = $group->create($username, $fullname, $group_category, $status, $my_page, $location, $year, $month, $day, $allowPosts, $allowComments);

            if (!$result['error']) {

                $group->addFollower($accountId);

                header("Location: /".$username);
                exit;

            } else {

                $error = true;

                if ($result['error_type'] == 0 || $result['error_type'] == 1) {

                    $error_type = 1;
                }

                if ($result['error_type'] == 3) {

                    $error_type = 3;
                }
            }
        }

        $error = true;
    }

    $accountInfo = $account->get();

    auth::newAuthenticityToken();

    $page_id = "create_group";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-create-communities']." | ".APP_TITLE;

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

                <div class="main-column">

                    <div class="main-content">

                        <div class="standard-page page-title-content bordered">
                            <div class="page-title-content-inner">
                                <?php echo $LANG['page-create-communities']; ?>
                            </div>
                            <div class="page-title-content-bottom-inner">
                                <?php echo $LANG['page-create-communities-description']; ?>
                            </div>
                        </div>

                        <div class="profile-content standard-page">

                            <form accept-charset="UTF-8" action="/account/create_group" autocomplete="off" class="edit_user" id="settings-form" method="post">

                                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                                <div class="tabbed-content">

                                    <div class="errors-container <?php if (!$error) echo "gone"; ?>" style="margin-top: 15px;">
                                        <ul>
                                            <?php

                                                switch ($error_type) {

                                                    case 1: {

                                                        echo $LANG['label-group-name-error'];

                                                        break;
                                                    }

                                                    default: {

                                                        echo $LANG['label-group-fullname-error'];

                                                        break;
                                                    }
                                                }
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="tab-pane active form-table">

                                        <div class="link-preference form-row" style="border-bottom: 0; padding-bottom: 0;">
                                            <div class="form-cell left">
                                                <h2><?php echo $LANG['label-group-privacy']; ?></h2>
                                            </div>

                                            <div class="form-cell">
                                                <div class="opt-in">
                                                    <input id="allowComments" name="allowComments" type="checkbox" <?php if ($allowComments == 1) echo "checked=\"checked\""; ?>>
                                                    <label for="allowComments"><?php echo $LANG['label-group-allow-comments']; ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="link-preference form-row">
                                            <div class="form-cell left">
                                            </div>

                                            <div class="form-cell">
                                                <div class="opt-in">
                                                    <input id="allowPosts" name="allowPosts" type="checkbox" <?php if ($allowPosts == 1) echo "checked=\"checked\""; ?>>
                                                    <label for="allowPosts"><?php echo $LANG['label-group-allow-posts']; ?></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-basics form-row">
                                            <div class="form-cell left">
                                                <h2><?php echo $LANG['label-settings-main-section-title']; ?></h2>
                                            </div>

                                            <div class="form-cell">
                                                <input id="fullname" name="fullname" placeholder="<?php echo $LANG['label-group-fullname']; ?>" maxlength="64" type="text" value="<?php echo $fullname; ?>">
                                                <input id="username" name="username" placeholder="<?php echo $LANG['label-group-username']." - ".APP_URL."/"; ?>" maxlength="64" type="text" value="<?php echo $username; ?>">
                                                <input id="location" name="location" placeholder="<?php echo $LANG['label-group-location']; ?>" maxlength="64" type="text" value="<?php echo $location; ?>">
                                                <input id="my_page" name="my_page" placeholder="<?php echo $LANG['label-group-web-page']; ?>" maxlength="255" type="text" value="<?php echo $my_page; ?>">
                                                <textarea placeholder="<?php echo $LANG['label-group-status']; ?>" id="status" name="status" maxlength="400"><?php echo $status; ?></textarea>

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
                                                            <option value="<?php echo $i; ?>" <?php if ($group_category == $i) echo "selected=\"selected\""; ?>><?php echo $LANG[$lang_option]; ?></option>
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

                                                        for ($day2 = 1; $day2 <= 31; $day2++) {

                                                            if ($day2 == $day) {

                                                                echo "<option value=\"$day2\" selected=\"selected\">$day2</option>";

                                                            } else {

                                                                echo "<option value=\"$day2\">$day2</option>";
                                                            }
                                                        }
                                                        ?>

                                                    </select>

                                                    <select id="month" name="month" class="selectBox" style="width: 30%;">
                                                        <option value="0" <?php if ($month == 0) echo "selected=\"selected\""; ?>><?php echo $LANG['month-jan']; ?></option>
                                                        <option value="1" <?php if ($month == 1) echo "selected=\"selected\""; ?>><?php echo $LANG['month-feb']; ?></option>
                                                        <option value="2" <?php if ($month == 2) echo "selected=\"selected\""; ?>><?php echo $LANG['month-mar']; ?></option>
                                                        <option value="3" <?php if ($month == 3) echo "selected=\"selected\""; ?>><?php echo $LANG['month-apr']; ?></option>
                                                        <option value="4" <?php if ($month == 4) echo "selected=\"selected\""; ?>><?php echo $LANG['month-may']; ?></option>
                                                        <option value="5" <?php if ($month == 5) echo "selected=\"selected\""; ?>><?php echo $LANG['month-june']; ?></option>
                                                        <option value="6" <?php if ($month == 6) echo "selected=\"selected\""; ?>><?php echo $LANG['month-july']; ?></option>
                                                        <option value="7" <?php if ($month == 7) echo "selected=\"selected\""; ?>><?php echo $LANG['month-aug']; ?></option>
                                                        <option value="8" <?php if ($month == 8) echo "selected=\"selected\""; ?>><?php echo $LANG['month-sept']; ?></option>
                                                        <option value="9" <?php if ($month == 9) echo "selected=\"selected\""; ?>><?php echo $LANG['month-oct']; ?></option>
                                                        <option value="10" <?php if ($month == 10) echo "selected=\"selected\""; ?>><?php echo $LANG['month-nov']; ?></option>
                                                        <option value="11" <?php if ($month == 11) echo "selected=\"selected\""; ?>><?php echo $LANG['month-dec']; ?></option>
                                                    </select>

                                                    <select id="year" name="year" class="selectBox" style="width: 30%;">

                                                        <?php

                                                        $current_year = date("Y");

                                                        for ($year2 = 1908; $year2 <= $current_year; $year2++) {

                                                            if ($year2 == $year) {

                                                                echo "<option value=\"$year2\" selected=\"selected\">$year2</option>";

                                                            } else {

                                                                echo "<option value=\"$year2\">$year2</option>";
                                                            }
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <input name="commit" type="submit" class="blue button mt-3" value="<?php echo $LANG['action-create-group']; ?>">

                            </form>
                        </div>


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