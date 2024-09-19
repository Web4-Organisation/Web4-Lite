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

    require_once '../sys/addons/vendor/autoload.php';

    use Google\Cloud\Vision\V1\Feature\Type;
    use Google\Cloud\Vision\V1\ImageAnnotatorClient;
    use Google\Cloud\Vision\V1\Likelihood;

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $gcv_adult = isset($_POST['gcv_adult']) ? $_POST['gcv_adult'] : 0;
        $gcv_violence = isset($_POST['gcv_violence']) ? $_POST['gcv_violence'] : 0;
        $gcv_racy = isset($_POST['gcv_racy']) ? $_POST['gcv_racy'] : 0;

        $gcv_adult = helper::clearInt($gcv_adult);
        $gcv_violence = helper::clearInt($gcv_violence);
        $gcv_racy = helper::clearInt($gcv_racy);

        if ($access_token === admin::getAccessToken() && !APP_DEMO) {

            $settings->setValue("gcv_adult", $gcv_adult);
            $settings->setValue("gcv_violence", $gcv_violence);
            $settings->setValue("gcv_racy", $gcv_racy);

            header("Location: /admin/vision");
            exit;
        }
    }

    $page_id = "vision";

    $error = false;
    $error_message = '';

    $css_files = array("mytheme.css");
    $page_title = "Google Cloud Vision";

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
                            <li class="breadcrumb-item active">Google Cloud Vision</li>
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
                                <h4 class="card-title">Google Cloud Vision</h4>
                                <h6 class="card-subtitle">Image filtering</h6>
                                <h6 class="card-subtitle">How to enable Google Cloud Vision you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>
                                <h6 class="card-subtitle">If a match is found, the image will not be published. I recommend not to set values less than "LIKELY" and "VERY_LIKELY" - experiment with the parameters and find the most suitable for your project.</h6>

                                <?php

                                    try {

                                        $jsonFileName = "";

                                        if ($files = glob("js/firebase/*.json")) {

                                            $jsonFileName = $files[0];
                                        }

                                        $client = new ImageAnnotatorClient([

                                            'credentials' => $jsonFileName
                                        ]);

                                        ?>

                                            <form action="/admin/vision" method="post">

                                                <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">

                                                <?php

                                                    $result = $settings->get();
                                                ?>

                                                <div class="form-group">
                                                    <label>Adult</label>
                                                    <select class="form-control" name="gcv_adult">
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Off (Do not search for a match)</option>
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">VERY_UNLIKELY</option>
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 2) echo 'selected="selected"'; ?> value="2">UNLIKELY</option>
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 3) echo 'selected="selected"'; ?> value="3">POSSIBLE</option>
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 4) echo 'selected="selected"'; ?> value="4">LIKELY</option>
                                                        <option <?php if ($result['gcv_adult']['intValue'] == 5) echo 'selected="selected"'; ?> value="5">VERY_LIKELY</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Violence</label>
                                                    <select class="form-control" name="gcv_violence">
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Off (Do not search for a match)</option>
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">VERY_UNLIKELY</option>
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 2) echo 'selected="selected"'; ?> value="2">UNLIKELY</option>
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 3) echo 'selected="selected"'; ?> value="3">POSSIBLE</option>
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 4) echo 'selected="selected"'; ?> value="4">LIKELY</option>
                                                        <option <?php if ($result['gcv_violence']['intValue'] == 5) echo 'selected="selected"'; ?> value="5">VERY_LIKELY</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Racy</label>
                                                    <select class="form-control" name="gcv_racy">
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Off (Do not search for a match)</option>
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">VERY_UNLIKELY</option>
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 2) echo 'selected="selected"'; ?> value="2">UNLIKELY</option>
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 3) echo 'selected="selected"'; ?> value="3">POSSIBLE</option>
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 4) echo 'selected="selected"'; ?> value="4">LIKELY</option>
                                                        <option <?php if ($result['gcv_racy']['intValue'] == 5) echo 'selected="selected"'; ?> value="5">VERY_LIKELY</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-xs-12">
                                                        <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                                    </div>
                                                </div>
                                            </form>

                                        <?php

                                    } catch (\Google\ApiCore\ValidationException $e) {

                                        echo "The service account \".json\" file was not found or the file is invalid.";

                                    } catch (Exception $e) {

                                        echo "Error.";
                                    }
                                ?>

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