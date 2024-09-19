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

    use Google\Cloud\Storage\StorageClient;

    // Administrator info

    $admin = new admin($dbo);
    $admin->setId(admin::getCurrentAdminId());

    //

    $stats = new stats($dbo);
    $settings = new settings($dbo);

    $settings_result = $settings->get();

    if (strlen($settings_result['gcs_photo_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_photo_bucket', 0, 'qa-app-photo-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_cover_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_cover_bucket', 0, 'qa-app-cover-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_gallery_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_gallery_bucket', 0, 'qa-app-gallery-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_video_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_video_bucket', 0, 'qa-app-video-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_item_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_item_bucket', 0, 'qa-app-item-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_market_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_market_bucket', 0, 'qa-app-market-'.helper::generateHash(7));
    }

    if (strlen($settings_result['gcs_chat_bucket']['textValue']) == 0) {

        $settings->setValue('gcs_chat_bucket', 0, 'qa-app-chat-'.helper::generateHash(7));
    }

    if (!empty($_POST)) {

        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        $gcs_photo = isset($_POST['gcs_photo']) ? $_POST['gcs_photo'] : 0;
        $gcs_cover = isset($_POST['gcs_cover']) ? $_POST['gcs_cover'] : 0;
        $gcs_gallery = isset($_POST['gcs_gallery']) ? $_POST['gcs_gallery'] : 0;
        $gcs_video = isset($_POST['gcs_video']) ? $_POST['gcs_video'] : 0;
        $gcs_item = isset($_POST['gcs_item']) ? $_POST['gcs_item'] : 0;
        $gcs_market = isset($_POST['gcs_market']) ? $_POST['gcs_market'] : 0;
        $gcs_chat = isset($_POST['gcs_chat']) ? $_POST['gcs_chat'] : 0;

        $gcs_photo = helper::clearInt($gcs_photo);
        $gcs_cover = helper::clearInt($gcs_cover);
        $gcs_gallery = helper::clearInt($gcs_gallery);
        $gcs_video = helper::clearInt($gcs_video);
        $gcs_item = helper::clearInt($gcs_item);
        $gcs_market = helper::clearInt($gcs_market);
        $gcs_chat = helper::clearInt($gcs_chat);

        if ($access_token === admin::getAccessToken() && !APP_DEMO) {

            $settings->setValue("gcs_photo", $gcs_photo);
            $settings->setValue("gcs_cover", $gcs_cover);
            $settings->setValue("gcs_gallery", $gcs_gallery);
            $settings->setValue("gcs_video", $gcs_video);
            $settings->setValue("gcs_video", $gcs_video);
            $settings->setValue("gcs_item", $gcs_item);
            $settings->setValue("gcs_market", $gcs_market);
            $settings->setValue("gcs_chat", $gcs_chat);

            header("Location: /admin/gcs");
            exit;
        }
    }

    $page_id = "gcs";

    $error = false;
    $error_message = '';

    $css_files = array("mytheme.css");
    $page_title = "Google Cloud Storage";

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
                            <li class="breadcrumb-item active">Google Cloud Storage</li>
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
                                <h4 class="card-title">Google Cloud Storage</h4>
                                <h6 class="card-subtitle">Cloud hosting</h6>
                                <h6 class="card-subtitle">How to enable Google Cloud Storage you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>
                                <h6 class="card-subtitle">Select the options you need: "Local" - files are stored on your hosting (server), "Google Cloud Storage" - files will be stored in Google storage</h6>

                                <?php

                                    if ($settings_result['S3_AMAZON']['intValue'] == 1) {

                                        echo "You have Amazon s3 enabled. You need to disable Amazon s3 and then you can use Google Cloud Storage.";

                                    } else {

                                        try {

                                            $jsonFileName = "";

                                            if ($files = glob("js/firebase/*.json")) {

                                                $jsonFileName = $files[0];
                                            }

                                            $storage = new StorageClient([

                                                'keyFilePath' => $jsonFileName
                                            ]);

                                            ?>

                                            <form action="/admin/gcs" method="post">

                                                <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">

                                                <?php

                                                $result = $settings->get();
                                                ?>

                                                <div class="form-group">
                                                    <label>Profile Photos</label>
                                                    <select class="form-control" name="gcs_photo">
                                                        <option <?php if ($result['gcs_photo']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_photo']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Profile Covers</label>
                                                    <select class="form-control" name="gcs_cover">
                                                        <option <?php if ($result['gcs_cover']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_cover']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Gallery Images</label>
                                                    <select class="form-control" name="gcs_gallery">
                                                        <option <?php if ($result['gcs_gallery']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_gallery']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Video Files From Gallery and Posts (also video thumbnails)</label>
                                                    <select class="form-control" name="gcs_video">
                                                        <option <?php if ($result['gcs_video']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_video']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Image Posts Files</label>
                                                    <select class="form-control" name="gcs_item">
                                                        <option <?php if ($result['gcs_item']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_item']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Market Items Images</label>
                                                    <select class="form-control" name="gcs_market">
                                                        <option <?php if ($result['gcs_market']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_market']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Images from Chats</label>
                                                    <select class="form-control" name="gcs_chat">
                                                        <option <?php if ($result['gcs_chat']['intValue'] == 0) echo 'selected="selected"'; ?> value="0">Local</option>
                                                        <option <?php if ($result['gcs_chat']['intValue'] == 1) echo 'selected="selected"'; ?> value="1">Google Cloud Storage</option>
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

                                            echo "Error: ".$e->getMessage();
                                        }
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