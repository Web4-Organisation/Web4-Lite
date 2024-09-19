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

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $error = false;
    $error_message = '';

    $stats = new stats($dbo);
    $admin = new admin($dbo);
    $alert = new alert($dbo);
    $settings = new settings($dbo);
    $helper = new helper($dbo);

    $admin_account = "";
    $allow_alerts = 0;

    if (!empty($_POST)) {

        $accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';
        $admin_account = isset($_POST['admin_account']) ? $_POST['admin_account'] : '';
        $allow_alerts = isset($_POST['allow_alerts']) ? $_POST['allow_alerts'] : '';

        $admin_account = helper::clearText($admin_account);
        $admin_account = helper::escapeText($admin_account);

        if ($allow_alerts === "on") {

            $allow_alerts = 1;

        } else {

            $allow_alerts = 0;
        }

        // if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

        if ($accessToken === admin::getAccessToken()) {

            $account_id = $helper->getUserId($admin_account);

            if ($account_id != 0) {

                $settings->setValue("admin_account", 0, $admin_account);
                $settings->setValue("admin_account_id", $account_id);
                $settings->setValue("admin_account_allow_alerts", $allow_alerts);

            } else {

                $settings->setValue("admin_account", 0, "");
                $settings->setValue("admin_account_id", 0);
                $settings->setValue("admin_account_allow_alerts", 0);
            }
        }

        header("Location: /admin/alert");
        exit;
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['admin_account'];
    $admin_account = $arr['textValue'];

    $arr = $config['admin_account_allow_alerts'];
    $allow_alerts = $arr['intValue'];

    //

    $page_id = "alert";

    $error = false;
    $error_message = '';

    $css_files = array("mytheme.css");
    $page_title = "Info posts | Admin Panel";

    include_once("../html/common/admin_header.inc.php");
?>

<body class="fix-header fix-sidebar card-no-border">

    <div id="main-wrapper">

        <?php

            include_once("../html/common/admin_topbar.inc.php");
        ?>

        <?php

            include_once("../html/common/admin_sidebar.inc.php");
        ?>

        <div class="page-wrapper">

            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">Info posts</li>
                        </ol>
                    </div>
                </div>

                <?php
                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Info posts</h4>
                                <h6 class="card-subtitle">Enter the username from which you want to display posts in the news feed for all users</h6>
                                <h6 class="card-subtitle">For example: You want to display posts from the user "https://yousite.com/myprofile", then enter "myprofile" in the field below and activate the display of posts. All users will see the latest post from the "myprofile" profile. So you can create information messages, congratulations or announcements for all users of the project.</h6>


                                <form class="form-material m-t-40" method="post" action="/admin/alert">
                                    <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="admin_account" name="admin_account" value="<?php echo stripslashes($admin_account); ?>" placeholder="Administrator profile username (not admin panel account)">
                                            </div>
                                            <div class="form-group">
                                                <p>
                                                    <input <?php if ($allow_alerts == 1) echo "checked" ?> type="checkbox" name="allow_alerts" id="allow_alerts">
                                                    <label for="allow_alerts">Show post from entered account in stream and users feed.</label>
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-xs-12">
                                                    <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <!-- form-group -->
                                </form>

                            </div>
                        </div>
                    </div>
                </div>


            </div> <!-- End Container fluid  -->

            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>