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

    $defaultInterstitialAdAfterNewItem = 1;
    $defaultInterstitialAdAfterNewGalleryItem = 1;
    $defaultInterstitialAdAfterNewMarketItem = 1;
    $defaultInterstitialAdAfterNewLike = 5;

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        $defaultInterstitialAdAfterNewItem = isset($_POST['defaultInterstitialAdAfterNewItem']) ? $_POST['defaultInterstitialAdAfterNewItem'] : 1;
        $defaultInterstitialAdAfterNewGalleryItem = isset($_POST['defaultInterstitialAdAfterNewGalleryItem']) ? $_POST['defaultInterstitialAdAfterNewGalleryItem'] : 1;
        $defaultInterstitialAdAfterNewMarketItem = isset($_POST['defaultInterstitialAdAfterNewMarketItem']) ? $_POST['defaultInterstitialAdAfterNewMarketItem'] : 1;
        $defaultInterstitialAdAfterNewLike = isset($_POST['defaultInterstitialAdAfterNewLike']) ? $_POST['defaultInterstitialAdAfterNewLike'] : 1;

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            $defaultInterstitialAdAfterNewItem = helper::clearInt($defaultInterstitialAdAfterNewItem);
            $defaultInterstitialAdAfterNewGalleryItem = helper::clearInt($defaultInterstitialAdAfterNewGalleryItem);
            $defaultInterstitialAdAfterNewMarketItem = helper::clearInt($defaultInterstitialAdAfterNewMarketItem);
            $defaultInterstitialAdAfterNewLike = helper::clearInt($defaultInterstitialAdAfterNewLike);

            $settings->setValue("interstitialAdAfterNewItem", $defaultInterstitialAdAfterNewItem);
            $settings->setValue("interstitialAdAfterNewGalleryItem", $defaultInterstitialAdAfterNewGalleryItem);
            $settings->setValue("interstitialAdAfterNewMarketItem", $defaultInterstitialAdAfterNewMarketItem);
            $settings->setValue("interstitialAdAfterNewLike", $defaultInterstitialAdAfterNewLike);
        }
    }

    $config = $settings->get();

    $arr = array();

    $arr = $config['interstitialAdAfterNewItem'];
    $defaultInterstitialAdAfterNewItem = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewGalleryItem'];
    $defaultInterstitialAdAfterNewGalleryItem = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewMarketItem'];
    $defaultInterstitialAdAfterNewMarketItem = $arr['intValue'];

    $arr = $config['interstitialAdAfterNewLike'];
    $defaultInterstitialAdAfterNewLike = $arr['intValue'];

    $page_id = "interstitial";

    $error = false;
    $error_message = '';

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Interstitial Settings";

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
                            <li class="breadcrumb-item active">Interstitial Settings</li>
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
                                <h4 class="card-title">Interstitial Settings</h4>
                                <h6 class="card-subtitle">Change Interstitial settings</h6>
                                <h6 class="card-subtitle">How to create interstitial ad block you can read here: <a href="https://docs.web4.one/" target="_blank">https://docs.web4.one/</a></h6>

                                <form action="/admin/interstitial" method="post">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewItem" class="active">Show ads after how many posts have been added (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewItem" type="number" size="4" name="defaultInterstitialAdAfterNewItem" value="<?php echo $defaultInterstitialAdAfterNewItem; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewGalleryItem" class="active">Show ads after how many added gallery elements (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewGalleryItem" type="number" size="4" name="defaultInterstitialAdAfterNewGalleryItem" value="<?php echo $defaultInterstitialAdAfterNewGalleryItem; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewMarketItem" class="active">Show ads after how many added market items (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewMarketItem" type="number" size="4" name="defaultInterstitialAdAfterNewMarketItem" value="<?php echo $defaultInterstitialAdAfterNewMarketItem; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultInterstitialAdAfterNewLike" class="active">Show ads after how many likes (0 = do not show)</label>
                                        <input class="form-control" id="defaultInterstitialAdAfterNewLike" type="number" size="4" name="defaultInterstitialAdAfterNewLike" value="<?php echo $defaultInterstitialAdAfterNewLike; ?>">
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