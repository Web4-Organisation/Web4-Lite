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

    if (!auth::isSession()) {

        header('Location: /');
        exit;
    }

    $stream = new stream($dbo);
    $stream->setRequestFrom(auth::getCurrentUserId());

    $inbox_all = $stream->getRecentlyDeletedCount();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $stream->getRecentlyDeleted($itemId);

        $inbox_loaded = count($result['items']);

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::post($value, $LANG, $helper, false, 1);
            }

            $result['html'] = ob_get_clean();

            if ($result['inbox_loaded'] < $inbox_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Items.more('/account/settings/recently_deleted', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

    $page_id = "recently_deleted";

    $css_files = array("main.css");
    $page_title = $LANG['page-recently-deleted']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-stream">


<?php
    include_once("../html/common/topbar.inc.php");
?>


<div class="wrap content-page">

    <div class="main-column row">

        <?php

            include_once("../html/common/sidenav.inc.php");
        ?>

        <?php

            include_once("../html/account/settings/settings_nav.inc.php");
        ?>

        <div class="row col sn-content" id="content">

            <div class="main-content">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title"><?php echo $LANG['page-recently-deleted']; ?></h3>
                        <h5 class="card-description"><?php echo $LANG['page-recently-deleted-desc']; ?></h5>
                    </div>
                </div>

                <div class="content-list-page posts-list-page posts-list-page-bordered">

                    <?php

                    $result = $stream->getRecentlyDeleted(0);

                    $inbox_loaded = count($result['items']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="items-list content-list">

                            <?php

                            foreach ($result['items'] as $key => $value) {

                                draw::post($value, $LANG, $helper, false, 1);
                            }
                            ?>

                        </div>

                        <?php

                    } else {

                        ?>

                        <div class="card information-banner">
                            <div class="card-header">
                                <div class="card-body">
                                    <header class="top-banner info-banner empty-list-banner">

                                    </header>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    ?>

                    <?php

                    if ($inbox_all > 20) {

                        ?>

                        <header class="top-banner loading-banner">

                            <div class="prompt">
                                <button onclick="Items.more('/account/settings/recently_deleted', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                            </div>

                        </header>

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

    var inbox_all = <?php echo $inbox_all; ?>;
    var inbox_loaded = <?php echo $inbox_loaded; ?>;

</script>


</body
</html>
