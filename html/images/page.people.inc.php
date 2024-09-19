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

    $postExists = true;

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
    $gallery->setRequestFrom(auth::getCurrentUserId());

    $itemId = helper::clearInt($request[2]);

    $photoInfo = $gallery->info($itemId);

    if ($photoInfo['error'] === true) {

        // Missing
        $postExists = false;
    }

    if ($postExists && $photoInfo['removeAt'] != 0) {

        // Missing
        $postExists = false;
    }

    if ($postExists && $profileInfo['id'] != $photoInfo['fromUserId'] ) {

        // Missing
        $postExists = false;
    }

    $items_all = 0;

    if ($postExists) {

        $items_all = $photoInfo['likesCount'];
    }

    $items_loaded = 0;

    if (!empty($_POST)) {

        $likeId = isset($_POST['likeId']) ? $_POST['likeId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $likeId = helper::clearInt($likeId);
        $loaded = helper::clearInt($loaded);

        $likes = new likes($dbo, ITEM_TYPE_GALLERY);
        $likes->setRequestFrom(auth::getCurrentUserId());

        $result = $likes->get($photoInfo['id'], 0);

        unset($likes);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['likers'] as $key => $value) {

                draw::peopleItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Images.getLikers('<?php echo $profileInfo['username']; ?>', '<?php echo $photoInfo['id']; ?>', '<?php echo $result['likeId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "people";

    $css_files = array("main.css", "my.css", "tipsy.css");
    $page_title = $LANG['page-likes']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/images/images_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="content-list-page">

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['page-likes']; ?></h3>
                        </div>
                    </div>

                    <?php

                    if ($postExists) {

                        $likes = new likes($dbo, ITEM_TYPE_GALLERY);
                        $likes->setRequestFrom(auth::getCurrentUserId());

                        $result = $likes->get($photoInfo['id'], 0);

                        unset($likes);

                        $items_loaded = count($result['items']);

                        if ($items_loaded != 0) {

                            ?>

                            <div class="row">
                                <div class="items-list grid-list row col-12 mx-0">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::peopleItem($value, $LANG, $helper);
                                }
                                ?>

                                </div>
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

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button
                                            onclick="Images.getLikers('<?php echo $profileInfo['username']; ?>', '<?php echo $photoInfo['id']; ?>', '<?php echo $result['likeId']; ?>'); return false;"
                                            class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                                </div>

                            </header>

                            <?php
                        }
                        ?>
                        <?php

                    } else {

                        ?>

                        <div class="card">

                            <div class="card-header">
                                <h3 class="card-title"><?php echo $LANG['label-post-missing']; ?></h3>
                            </div>
                        </div>

                        <?php
                    }
                    ?>


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

    </script>


</body
</html>