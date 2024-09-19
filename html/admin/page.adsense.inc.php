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

    $f_adsense_wide_block = "../html/common/adsense_wide.inc.php";
    $f_adsense_square_block = "../html/common/adsense_square.inc.php";
    $f_adsense_vertical_block = "../html/common/adsense_vertical.inc.php";

    $adsense_wide = "";
    $adsense_square = "";
    $adsense_vertical = "";

    if (!empty($_POST)) {

        $accessToken = isset($_POST['access_token']) ? $_POST['access_token'] : '';
        $adsense_wide = isset($_POST['adsense_wide']) ? $_POST['adsense_wide'] : '';
        $adsense_square = isset($_POST['adsense_square']) ? $_POST['adsense_square'] : '';
        $adsense_vertical = isset($_POST['adsense_vertical']) ? $_POST['adsense_vertical'] : '';

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            if (strlen($adsense_wide) != 0) {

                $adsense_wide = mb_convert_encoding($adsense_wide, 'UTF-8', 'auto');
                file_put_contents($f_adsense_wide_block, $adsense_wide);

            } else {

                if (file_exists($f_adsense_wide_block)) {

                    unlink($f_adsense_wide_block);
                }
            }

            if (strlen($adsense_square) != 0) {

                $adsense_square = mb_convert_encoding($adsense_square, 'UTF-8', 'auto');
                file_put_contents($f_adsense_square_block, $adsense_square);

            } else {

                if (file_exists($f_adsense_square_block)) {

                    unlink($f_adsense_square_block);
                }
            }

            if (strlen($adsense_vertical) != 0) {

                $adsense_vertical = mb_convert_encoding($adsense_vertical, 'UTF-8', 'auto');
                file_put_contents($f_adsense_vertical_block, $adsense_vertical);

            } else {

                if (file_exists($f_adsense_vertical_block)) {

                    unlink($f_adsense_vertical_block);
                }
            }
        }
    }

    if (file_exists($f_adsense_wide_block)) {

        $adsense_wide = file_get_contents($f_adsense_wide_block);
    }

    if (file_exists($f_adsense_square_block)) {

        $adsense_square = file_get_contents($f_adsense_square_block);
    }

    if (file_exists($f_adsense_vertical_block)) {

        $adsense_vertical = file_get_contents($f_adsense_vertical_block);
    }

    $page_id = "adsense";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Adsense | Admin Panel";

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
                            <li class="breadcrumb-item active">Adsense</li>
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
                                <h4 class="card-title">Adsense units</h4>
                                <h6 class="card-subtitle">Add ad unit code to display Google ad units or remove ad code to prevent ads from showing.</h6>
                                <h6 class="card-subtitle">How to create units and get html code you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one</a></h6>

                                <form class="form-material m-t-40" method="post" action="/admin/adsense">

                                    <input type="hidden" name="access_token" value="<?php echo admin::getAccessToken(); ?>">

                                    <div class="form-group">
                                        <label class="pb-2">Wide ad unit code</label>
                                        <textarea class="form-control" name="adsense_wide" placeholder="Paste here you wide ad unit code..." style="height: 250px"><?php echo $adsense_wide; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="pb-2">Square ad unit code</label>
                                        <textarea class="form-control" name="adsense_square" placeholder="Paste here you square ad unit code..." style="height: 250px"><?php echo $adsense_square; ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="pb-2">Vertical ad unit code</label>
                                        <textarea class="form-control" name="adsense_vertical" placeholder="Paste here you vertical ad unit code..." style="height: 250px"><?php echo $adsense_vertical; ?></textarea>
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
