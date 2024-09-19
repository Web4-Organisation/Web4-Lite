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

    //

    $error = false;
    $error_message = '';

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $android_admob_app_id = "ca-app-pub-3940256099942544~3347511713";
    $android_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/6300978111";
    $android_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/5224354917";
    $android_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/1033173712";
    $android_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";

    $ios_admob_app_id = "ca-app-pub-3940256099942544~1458002511";
    $ios_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/2934735716";
    $ios_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/1712485313";
    $ios_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/4411468910";
    $ios_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $android_admob_app_id = isset($_POST['android_admob_app_id']) ? $_POST['android_admob_app_id'] : '';
        $android_admob_banner_ad_unit_id = isset($_POST['android_admob_banner_ad_unit_id']) ? $_POST['android_admob_banner_ad_unit_id'] : '';
        $android_admob_rewarded_ad_unit_id = isset($_POST['android_admob_rewarded_ad_unit_id']) ? $_POST['android_admob_rewarded_ad_unit_id'] : '';
        $android_admob_interstitial_ad_unit_id = isset($_POST['android_admob_interstitial_ad_unit_id']) ? $_POST['android_admob_interstitial_ad_unit_id'] : '';
        $android_admob_banner_native_ad_unit_id = isset($_POST['android_admob_banner_native_ad_unit_id']) ? $_POST['android_admob_banner_native_ad_unit_id'] : '';

        $ios_admob_app_id = isset($_POST['ios_admob_app_id']) ? $_POST['ios_admob_app_id'] : '';
        $ios_admob_banner_ad_unit_id = isset($_POST['ios_admob_banner_ad_unit_id']) ? $_POST['ios_admob_banner_ad_unit_id'] : '';
        $ios_admob_rewarded_ad_unit_id = isset($_POST['ios_admob_rewarded_ad_unit_id']) ? $_POST['ios_admob_rewarded_ad_unit_id'] : '';
        $ios_admob_interstitial_ad_unit_id = isset($_POST['ios_admob_interstitial_ad_unit_id']) ? $_POST['ios_admob_interstitial_ad_unit_id'] : '';
        $ios_admob_banner_native_ad_unit_id = isset($_POST['ios_admob_banner_native_ad_unit_id']) ? $_POST['ios_admob_banner_native_ad_unit_id'] : '';

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            $android_admob_app_id = helper::clearText($android_admob_app_id);
            $android_admob_banner_ad_unit_id = helper::clearText($android_admob_banner_ad_unit_id);
            $android_admob_rewarded_ad_unit_id = helper::clearText($android_admob_rewarded_ad_unit_id);
            $android_admob_interstitial_ad_unit_id = helper::clearText($android_admob_interstitial_ad_unit_id);
            $android_admob_banner_native_ad_unit_id = helper::clearText($android_admob_banner_native_ad_unit_id);

            $ios_admob_app_id = helper::clearText($ios_admob_app_id);
            $ios_admob_banner_ad_unit_id = helper::clearText($ios_admob_banner_ad_unit_id);
            $ios_admob_rewarded_ad_unit_id = helper::clearText($ios_admob_rewarded_ad_unit_id);
            $ios_admob_interstitial_ad_unit_id = helper::clearText($ios_admob_interstitial_ad_unit_id);
            $ios_admob_banner_native_ad_unit_id = helper::clearText($ios_admob_banner_native_ad_unit_id);

            $settings->setValue("android_admob_app_id", 0, $android_admob_app_id);

            if (strlen($android_admob_banner_ad_unit_id) == 0) {

                $android_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/6300978111";
            }

            $settings->setValue("android_admob_banner_ad_unit_id", 0, $android_admob_banner_ad_unit_id);

            if (strlen($android_admob_rewarded_ad_unit_id) == 0) {

                $android_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/5224354917";
            }

            $settings->setValue("android_admob_rewarded_ad_unit_id", 0, $android_admob_rewarded_ad_unit_id);

            if (strlen($android_admob_interstitial_ad_unit_id) == 0) {

                $android_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/1033173712";
            }

            $settings->setValue("android_admob_interstitial_ad_unit_id", 0, $android_admob_interstitial_ad_unit_id);

            if (strlen($android_admob_banner_native_ad_unit_id) == 0) {

                $android_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";
            }

            $settings->setValue("android_admob_banner_native_ad_unit_id", 0, $android_admob_banner_native_ad_unit_id);

            // iOS

            $settings->setValue("ios_admob_app_id", 0, $ios_admob_app_id);

            if (strlen($ios_admob_banner_ad_unit_id) == 0) {

                $ios_admob_banner_ad_unit_id = "ca-app-pub-3940256099942544/2934735716";
            }

            $settings->setValue("ios_admob_banner_ad_unit_id", 0, $ios_admob_banner_ad_unit_id);

            if (strlen($ios_admob_rewarded_ad_unit_id) == 0) {

                $ios_admob_rewarded_ad_unit_id = "ca-app-pub-3940256099942544/1712485313";
            }

            $settings->setValue("ios_admob_rewarded_ad_unit_id", 0, $ios_admob_rewarded_ad_unit_id);

            if (strlen($ios_admob_interstitial_ad_unit_id) == 0) {

                $ios_admob_interstitial_ad_unit_id = "ca-app-pub-3940256099942544/4411468910";
            }

            $settings->setValue("ios_admob_interstitial_ad_unit_id", 0, $ios_admob_interstitial_ad_unit_id);

            if (strlen($ios_admob_banner_native_ad_unit_id) == 0) {

                $ios_admob_banner_native_ad_unit_id = "ca-app-pub-3940256099942544/2247696110";
            }

            $settings->setValue("ios_admob_banner_native_ad_unit_id", 0, $ios_admob_banner_native_ad_unit_id);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['android_admob_app_id'];
    $android_admob_app_id = $arr['textValue'];

    $arr = $config['android_admob_banner_ad_unit_id'];
    $android_admob_banner_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_rewarded_ad_unit_id'];
    $android_admob_rewarded_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_interstitial_ad_unit_id'];
    $android_admob_interstitial_ad_unit_id = $arr['textValue'];

    $arr = $config['android_admob_banner_native_ad_unit_id'];
    $android_admob_banner_native_ad_unit_id = $arr['textValue'];

    // iOS

    $arr = $config['ios_admob_app_id'];
    $ios_admob_app_id = $arr['textValue'];

    $arr = $config['ios_admob_banner_ad_unit_id'];
    $ios_admob_banner_ad_unit_id = $arr['textValue'];

    $arr = $config['ios_admob_rewarded_ad_unit_id'];
    $ios_admob_rewarded_ad_unit_id = $arr['textValue'];

    $arr = $config['ios_admob_interstitial_ad_unit_id'];
    $ios_admob_interstitial_ad_unit_id = $arr['textValue'];

    $arr = $config['ios_admob_banner_native_ad_unit_id'];
    $ios_admob_banner_native_ad_unit_id = $arr['textValue'];

    //

    if (APP_DEMO && strlen($android_admob_app_id) > 14) {

        $android_admob_app_id = "*****".substr($android_admob_app_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_banner_ad_unit_id) > 14) {

        $android_admob_banner_ad_unit_id = "*****".substr($android_admob_banner_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_rewarded_ad_unit_id) > 14) {

        $android_admob_rewarded_ad_unit_id = "*****".substr($android_admob_rewarded_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_interstitial_ad_unit_id) > 14) {

        $android_admob_interstitial_ad_unit_id = "*****".substr($android_admob_interstitial_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($android_admob_banner_native_ad_unit_id) > 14) {

        $android_admob_banner_native_ad_unit_id = "*****".substr($android_admob_banner_native_ad_unit_id, -14);
    }

    // iOS

    if (APP_DEMO && strlen($ios_admob_app_id) > 14) {

        $ios_admob_app_id = "*****".substr($ios_admob_app_id, -14);
    }

    if (APP_DEMO && strlen($ios_admob_banner_ad_unit_id) > 14) {

        $ios_admob_banner_ad_unit_id = "*****".substr($ios_admob_banner_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($ios_admob_rewarded_ad_unit_id) > 14) {

        $ios_admob_rewarded_ad_unit_id = "*****".substr($ios_admob_rewarded_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($ios_admob_interstitial_ad_unit_id) > 14) {

        $ios_admob_interstitial_ad_unit_id = "*****".substr($ios_admob_interstitial_ad_unit_id, -14);
    }

    if (APP_DEMO && strlen($ios_admob_banner_native_ad_unit_id) > 14) {

        $ios_admob_banner_native_ad_unit_id = "*****".substr($ios_admob_banner_native_ad_unit_id, -14);
    }

    //

    $page_id = "admob_settings";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "AdMob Ad Settings | Admin Panel";

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
                            <li class="breadcrumb-item active">AdMob Ad Settings</li>
                        </ol>
                    </div>
                </div>

                <?php
                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="card-title">Warning!</h4>
                                <p class="card-text">In application changes will take effect during the next user authorization.</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Admob Info</h4>
                                <h6 class="card-subtitle">How to get banner_ad_unit_id from AdMob: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>
                                <h6 class="card-subtitle">How to get ad_unit_id for Rewarded Ads from Admob: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>
                                <h6 class="card-subtitle">How to create Interstitial ad block you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>

                                <form action="/admin/admob_settings" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group d-none">
                                        <label for="android_admob_app_id" class="active">Admob App ID</label>
                                        <input class="form-control" id="android_admob_app_id" type="text" size="64" name="android_admob_app_id" value="<?php echo $android_admob_app_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_banner_ad_unit_id" class="active">Admob Banner Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_banner_ad_unit_id" type="text" size="64" name="android_admob_banner_ad_unit_id" value="<?php echo $android_admob_banner_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_rewarded_ad_unit_id" class="active">Admob Rewarded Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_rewarded_ad_unit_id" type="text" size="64" name="android_admob_rewarded_ad_unit_id" value="<?php echo $android_admob_rewarded_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_interstitial_ad_unit_id" class="active">Admob Interstitial Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_interstitial_ad_unit_id" type="text" size="64" name="android_admob_interstitial_ad_unit_id" value="<?php echo $android_admob_interstitial_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="android_admob_banner_native_ad_unit_id" class="active">Admob Native Ad Unit ID</label>
                                        <input class="form-control" id="android_admob_banner_native_ad_unit_id" type="text" size="64" name="android_admob_banner_native_ad_unit_id" value="<?php echo $android_admob_banner_native_ad_unit_id; ?>">
                                    </div>

                                    <h6><br><br></h6>

                                    <!--  iOS  -->

                                    <div class="form-group d-none">
                                        <label for="ios_admob_app_id" class="active">iOS Admob App ID</label>
                                        <input class="form-control" id="ios_admob_app_id" type="text" size="64" name="ios_admob_app_id" value="<?php echo $ios_admob_app_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ios_admob_banner_ad_unit_id" class="active">iOS Admob Banner Ad Unit ID</label>
                                        <input class="form-control" id="ios_admob_banner_ad_unit_id" type="text" size="64" name="ios_admob_banner_ad_unit_id" value="<?php echo $ios_admob_banner_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ios_admob_rewarded_ad_unit_id" class="active">iOS Admob Rewarded Ad Unit ID</label>
                                        <input class="form-control" id="ios_admob_rewarded_ad_unit_id" type="text" size="64" name="ios_admob_rewarded_ad_unit_id" value="<?php echo $ios_admob_rewarded_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ios_admob_interstitial_ad_unit_id" class="active">iOS Admob Interstitial Ad Unit ID</label>
                                        <input class="form-control" id="ios_admob_interstitial_ad_unit_id" type="text" size="64" name="ios_admob_interstitial_ad_unit_id" value="<?php echo $ios_admob_interstitial_ad_unit_id; ?>">
                                    </div>

                                    <div class="form-group d-none">
                                        <label for="ios_admob_banner_native_ad_unit_id" class="active">iOS Admob Native Ad Unit ID</label>
                                        <input class="form-control" id="ios_admob_banner_native_ad_unit_id" type="text" size="64" name="ios_admob_banner_native_ad_unit_id" value="<?php echo $ios_admob_banner_native_ad_unit_id; ?>">
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
