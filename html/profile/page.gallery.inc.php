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

    $profileId = $helper->getUserId($request[0]);

    $profile = new profile($dbo, $profileId);

    $profile->setRequestFrom(auth::getCurrentUserId());
    $profileInfo = $profile->get();

    if ($profileInfo['error']) {

        include_once("../html/error.inc.php");
        exit;
    }

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("../html/stubs/profile.inc.php");
        exit;
    }

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom($profileInfo['id']);

    $items_all = $gallery->count();
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $gallery->get($profileInfo['id'], -1, $itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::galleryItem($value, $profileInfo, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner p-0 pt-3">

                    <div class="prompt">
                        <button onclick="Gallery.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    auth::newAuthenticityToken();

    $page_id = "gallery";

    $css_files = array("main.css");
    $page_title = $LANG['page-gallery']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="gallery-listings page-gallery">

    <?php
        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/profile/profile_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="gallery-intro-header">
                        <h1 class="gallery-title"><?php echo $LANG['page-gallery']; ?></h1>
                        <p class="gallery-sub-title"><?php echo $LANG['label-gallery-sub-title']; ?></p>
                    </div>

                    <div class="content-block">

                        <?php

                            if ($profileInfo['id'] != auth::getCurrentUserId() && !$profileInfo['friend'] && $profileInfo['allowShowMyGallery'] == 1) {

                                ?>

                                <div class="card information-banner">
                                    <div class="card-header">
                                        <div class="card-body">
                                            <h5 class="m-0"><?php echo $LANG['label-error-permission']; ?></h5>
                                        </div>
                                    </div>
                                </div>

                                <?php

                            } else {

                                $result = $gallery->get($profileInfo['id'], -1, 0);

                                $items_loaded = count($result['items']);

                                if ($items_loaded != 0) {

                                    ?>
                                        <div class="grid-list">
                                    <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw::galleryItem($value, $profileInfo, $LANG, $helper);
                                    }

                                    ?>
                                        </div>
                                    <?php

                                    if ($items_all > 20) {

                                        ?>

                                        <header class="top-banner loading-banner p-0 pt-3">

                                            <div class="prompt">
                                                <button onclick="Gallery.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                            </div>

                                        </header>

                                        <?php
                                    }

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

                            <?php
                            }
                        ?>

                    </div>


                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

            var auth_token = "<?php echo auth::getAuthenticityToken(); ?>";
            var username = "<?php echo auth::getCurrentUserLogin(); ?>";

        </script>


</body
</html>
