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

    $stats = new stats($dbo);

    $error = false;
    $error_message = '';

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $current_passw = isset($_POST['current_passw']) ? $_POST['current_passw'] : '';
        $new_passw = isset($_POST['new_passw']) ? $_POST['new_passw'] : '';

        $current_passw = helper::clearText($current_passw);
        $current_passw = helper::escapeText($current_passw);

        $new_passw = helper::clearText($new_passw);
        $new_passw = helper::escapeText($new_passw);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            $admin = new admin($dbo);
            $admin->setId(admin::getCurrentAdminId());

            $result = $admin->setPassword($current_passw, $new_passw);

            if ($result['error'] === false) {

                header("Location: /admin/settings/?result=success");
                exit;

            } else {

                header("Location: /admin/settings/?result=error");
                exit;
            }
        }

        header("Location: /admin/settings");
        exit;
    }

    $page_id = "settings";

    $css_files = array("mytheme.css");
    $page_title = "Settings | Admin Panel";

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
                            <li class="breadcrumb-item active">Settings</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <?php

                    if (isset($_GET['result'])) {

                        $result = isset($_GET['result']) ? $_GET['result'] : '';

                        switch ($result) {

                            case "success": {

                                ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h4 class="card-title">Thanks!</h4>
                                                <p class="card-text">New password is saved.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php

                                break;
                            }

                            case "error": {

                                ?>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h4 class="card-title">Error!</h4>
                                                <p class="card-text">Invalid current password or incorrectly enter a new password.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php

                                break;
                            }

                            default: {

                                break;
                            }
                        }
                    }
                ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Change Password</h4>
                                <h6 class="card-subtitle">Enter the current and new password</h6>

                                <form class="form-material m-t-40" method="post" action="/admin/settings">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password" class="form-control" name="current_passw" id="current_passw">
                                    </div>

                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password" class="form-control"  name="new_passw" id="new_passw">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                        </div>
                                    </div>
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