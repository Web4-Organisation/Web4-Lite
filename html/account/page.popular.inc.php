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

    $popular = new popular($dbo);
    $popular->setRequestFrom(auth::getCurrentUserId());

    $page_id = "popular";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-popular']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-popular">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <div class="row col sn-content sn-content-sidebar-block" id="content">

                <div class="main-content">

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['page-popular']; ?></h3>
                            <h5 class="card-description"><?php echo $LANG['page-popular-description']; ?></h5>
                        </div>
                    </div>

                    <div class="content-list-page posts-list-page posts-list-page-bordered">

                        <?php

                        $result = $popular->get(0, 0);

                        $inbox_loaded = count($result['items']);

                        if ($inbox_loaded != 0) {

                            ?>

                            <div class="items-list content-list">

                                <?php

                                    $showed_ad = false;

                                    foreach ($result['items'] as $key => $value) {

                                        draw::post($value, $LANG, $helper);

                                        if (!$showed_ad) {

                                            $showed_ad = true;

                                            require_once ("../html/common/adsense_banner.inc.php");
                                        }
                                    }
                                ?>

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card information-banner">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="m-0"><?php echo $LANG['label-empty-list']; ?></h5>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                    </div>

                </div>

            </div>

            <?php

                include_once("../html/common/sidebar.inc.php");
            ?>

        </div>
    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        var inbox_loaded = <?php echo $inbox_loaded; ?>;

    </script>


</body
</html>
