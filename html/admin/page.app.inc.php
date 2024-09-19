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
    $settings = new settings($dbo);
    $admin = new admin($dbo);

    $allowFacebookAuthorization = 1;
    $allowMultiAccountsFunction = 1;
    $allowRecaptchaSignupApp = 0;

    $defaultAllowMessages = 0;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $allowFacebookAuthorization_checkbox = isset($_POST['allowFacebookAuthorization']) ? $_POST['allowFacebookAuthorization'] : '';
        $allowMultiAccountsFunction_checkbox = isset($_POST['allowMultiAccountsFunction']) ? $_POST['allowMultiAccountsFunction'] : '';
        $allowRecaptchaSignupApp_checkbox = isset($_POST['allowRecaptchaSignupApp']) ? $_POST['allowRecaptchaSignupApp'] : '';

        $defaultAllowMessages_checkbox = isset($_POST['defaultAllowMessages']) ? $_POST['defaultAllowMessages'] : '';

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if ($allowFacebookAuthorization_checkbox === "on") {

                $allowFacebookAuthorization = 1;

            } else {

                $allowFacebookAuthorization = 0;
            }

            if ($allowMultiAccountsFunction_checkbox === "on") {

                $allowMultiAccountsFunction = 1;

            } else {

                $allowMultiAccountsFunction = 0;
            }

            if ($allowRecaptchaSignupApp_checkbox === "on") {

                $allowRecaptchaSignupApp = 1;

            } else {

                $allowRecaptchaSignupApp = 0;
            }

            if ($defaultAllowMessages_checkbox === "on") {

                $defaultAllowMessages = 1;

            } else {

                $defaultAllowMessages = 0;
            }

            $settings->setValue("allowFacebookAuthorization", $allowFacebookAuthorization);
            $settings->setValue("allowMultiAccountsFunction", $allowMultiAccountsFunction);
            $settings->setValue("RECAPTCHA_SIGNUP_APP", $allowRecaptchaSignupApp);
            $settings->setValue("defaultAllowMessages", $defaultAllowMessages);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['allowFacebookAuthorization'];
    $allowFacebookAuthorization = $arr['intValue'];

    $arr = $config['allowMultiAccountsFunction'];
    $allowMultiAccountsFunction = $arr['intValue'];

    $arr = $config['RECAPTCHA_SIGNUP_APP'];
    $allowRecaptchaSignupApp = $arr['intValue'];

    $arr = $config['defaultAllowMessages'];
    $defaultAllowMessages = $arr['intValue'];

    $page_id = "app";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "App Settings";

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
                            <li class="breadcrumb-item active">App Settings</li>
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
                                <h4 class="card-title">App Settings</h4>
                                <h6 class="card-subtitle">Change application settings</h6>

                                <form action="/admin/app" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">

                                        <p style="display: none">
                                            <input type="checkbox" name="allowFacebookAuthorization" id="allowFacebookAuthorization" <?php if ($allowFacebookAuthorization == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowFacebookAuthorization">Allow registration/authorization via Facebook</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowMultiAccountsFunction" id="allowMultiAccountsFunction" <?php if ($allowMultiAccountsFunction == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowMultiAccountsFunction">Enable creation of multi-accounts</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="allowRecaptchaSignupApp" id="allowRecaptchaSignupApp" <?php if ($allowRecaptchaSignupApp == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowRecaptchaSignupApp">Validate reCaptcha from the application when registering a new account</label>
                                        </p>

                                        <p>
                                            <input type="checkbox" name="defaultAllowMessages" id="defaultAllowMessages" <?php if ($defaultAllowMessages == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="defaultAllowMessages">Allow private messages from all users by default (activating this option can increase the flow of spam in messages, each user can change this option in the settings of his account)</label>
                                        </p>
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