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

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $s3_key = "";
    $s3_secret = "";
    $s3_region = "";

    $allowAmazonS3 = 0;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $s3_key = isset($_POST['s3_key']) ? $_POST['s3_key'] : '';
        $s3_secret = isset($_POST['s3_secret']) ? $_POST['s3_secret'] : '';
        $s3_region = isset($_POST['s3_region']) ? $_POST['s3_region'] : '';

        $allowAmazonS3 = isset($_POST['allowAmazonS3']) ? $_POST['allowAmazonS3'] : '';

        $s3_key = helper::clearText($s3_key);
        $s3_secret = helper::clearText($s3_secret);
        $s3_region = helper::clearText($s3_region);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if ($allowAmazonS3 === "on") {

                $allowAmazonS3 = 1;

            } else {

                $allowAmazonS3 = 0;
            }

            $settings->setValue("S3_REGION", 0, $s3_region);
            $settings->setValue("S3_KEY", 0, $s3_key);
            $settings->setValue("S3_SECRET", 0, $s3_secret);

            if (strlen($s3_key) == 0 || strlen($s3_secret) == 0) {

                $allowAmazonS3 = 0;
            }

            $settings->setValue("S3_AMAZON", $allowAmazonS3);
        }
    }

    $page_id = "amazon";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Amazon s3";

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
                            <li class="breadcrumb-item active">Amazon S3</li>
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
                                <h4 class="card-title">Amazon S3</h4>
                                <h6 class="card-subtitle">Store users data on AWS S3</h6>
                                <h6 class="card-subtitle">If saving to AWS S3 is disabled or configured incorrectly, then user data is saved on your current hosting</h6>
                                <h6 class="card-subtitle">How to add Amazon s3 support you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>

                                <form action="/admin/amazon" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <?php

                                        $result = $settings->get();

                                        if (APP_DEMO && strlen($result['S3_KEY']['textValue']) > 8) {

                                            $result['S3_KEY']['textValue'] = "*****".substr($result['S3_KEY']['textValue'], -8);
                                        }

                                        if (APP_DEMO && strlen($result['S3_SECRET']['textValue']) > 10) {

                                            $result['S3_SECRET']['textValue'] = "*****".substr($result['S3_SECRET']['textValue'], -10);
                                        }
                                    ?>

                                    <div class="form-group">
                                        <label for="s3_key" class="active">Access Key ID</label>
                                        <input class="form-control" id="s3_key" type="text" size="32" name="s3_key" value="<?php echo $result['S3_KEY']['textValue']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="s3_secret" class="active">Secret Access Key</label>
                                        <input class="form-control" id="s3_secret" type="text" size="32" name="s3_secret" value="<?php echo $result['S3_SECRET']['textValue']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>AWS Region</label>
                                        <select class="form-control" name="s3_region">
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'us-east-1') echo 'selected="selected"'; ?> value="us-east-1">US East (N. Virginia) us-east-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'us-east-2') echo 'selected="selected"'; ?> value="us-east-2">US East (Ohio) us-east-2</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'us-west-1') echo 'selected="selected"'; ?> value="us-west-1">US West (N. California) us-west-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'us-west-2') echo 'selected="selected"'; ?> value="us-west-2">US West (Oregon) us-west-2</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'af-south-1') echo 'selected="selected"'; ?> value="af-south-1">Africa (Cape Town) af-south-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-east-1') echo 'selected="selected"'; ?> value="ap-east-1">Asia Pacific (Hong Kong) ap-east-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-southeast-3') echo 'selected="selected"'; ?> value="ap-southeast-3">Asia Pacific (Jakarta) ap-southeast-3</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-south-1') echo 'selected="selected"'; ?> value="ap-south-1">Asia Pacific (Mumbai) ap-south-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-northeast-3') echo 'selected="selected"'; ?> value="ap-northeast-3">Asia Pacific (Osaka) ap-northeast-3</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-northeast-2') echo 'selected="selected"'; ?> value="ap-northeast-2">Asia Pacific (Seoul) ap-northeast-2</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-southeast-1') echo 'selected="selected"'; ?> value="ap-southeast-1">Asia Pacific (Singapore) ap-southeast-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-southeast-2') echo 'selected="selected"'; ?> value="ap-southeast-2">Asia Pacific (Sydney) ap-southeast-2</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ap-northeast-1') echo 'selected="selected"'; ?> value="ap-northeast-1">Asia Pacific (Tokyo) ap-northeast-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'ca-central-1') echo 'selected="selected"'; ?> value="ca-central-1">Canada (Central) ca-central-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-central-1') echo 'selected="selected"'; ?> value="eu-central-1">Europe (Frankfurt) eu-central-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-west-1') echo 'selected="selected"'; ?> value="eu-west-1">Europe (Ireland) eu-west-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-west-2') echo 'selected="selected"'; ?> value="eu-west-2">Europe (London) eu-west-2</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-south-1') echo 'selected="selected"'; ?> value="eu-south-1">Europe (Milan) eu-south-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-west-3') echo 'selected="selected"'; ?> value="eu-west-3">Europe (Paris) eu-west-3</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'eu-north-1') echo 'selected="selected"'; ?> value="eu-north-1">Europe (Stockholm) eu-north-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'me-south-1') echo 'selected="selected"'; ?> value="me-south-1">Middle East (Bahrain) me-south-1</option>
                                            <option <?php if ($result['S3_REGION']['textValue'] === 'sa-east-1') echo 'selected="selected"'; ?> value="sa-east-1">South America (São Paulo) sa-east-1</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <p>
                                            <input type="checkbox" name="allowAmazonS3" id="allowAmazonS3" <?php if ($result['S3_AMAZON']['intValue'] == 1) echo "checked=\"checked\"";  ?> />
                                            <label for="allowAmazonS3">Allow use Amazon S3</label>
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