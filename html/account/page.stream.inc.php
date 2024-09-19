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

    $inbox_all = $stream->count();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : '';
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $stream->get($itemId);

        $inbox_loaded = count($result['items']);

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::post($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['inbox_loaded'] < $inbox_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Items.more('/account/stream', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

    $page_id = "stream";

    $css_files = array("main.css");
    $page_title = $LANG['page-stream']." | ".APP_TITLE;

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

            <div class="row col sn-content sn-content-sidebar-block" id="content">

                <div class="main-content">

                    <div class="card">

                        <div class="card-header">

                            <div class="header-text-block">
                                <h3 class="card-title"><?php echo $LANG['page-stream']; ?></h3>
                                <h5 class="card-description"><?php echo $LANG['page-stream-description']; ?></h5>
                            </div>

                            <label class="switch header-switch hidden">
                                <input id="switch-stream-mode-button" type="checkbox" checked="checked" disabled>
                                <span class="sw-slider round"></span>
                            </label>

                        </div>
                    </div>

                    <?php
                    include_once("../html/common/postform.inc.php");
                    ?>

                    <div class="content-list-page posts-list-page posts-list-page-bordered">

                        <?php

                        $result = $stream->get(0);

                        $inbox_loaded = count($result['items']);
                        $alerts_loaded = count($result['alerts']);

                        if ($inbox_loaded != 0 || $alerts_loaded != 0) {

                            ?>

                            <div class="items-list content-list">

                                <?php

                                // Show admin alert

                                if ($alerts_loaded != 0) {

                                    $result['alerts'][0]['admin_account'] = 1;

                                    if (isset($_COOKIE['hidden_item_id'])) {

                                        if ($_COOKIE['hidden_item_id'] != $result['alerts'][0]['id']) {

                                            draw::post($result['alerts'][0], $LANG, $helper);
                                        }

                                    } else {

                                        draw::post($result['alerts'][0], $LANG, $helper);
                                    }
                                }

                                // Show posts

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
                                    <button onclick="Items.more('/account/stream', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                </div>

                            </header>

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

        var inbox_all = <?php echo $inbox_all; ?>;
        var inbox_loaded = <?php echo $inbox_loaded; ?>;

        $("textarea[name=postText]").autosize();

        $("textarea[name=postText]").bind('keyup mouseout', function() {

            var max_char = 1000;

            var count = $("textarea[name=postText]").val().length;

            $("span#word_counter").empty();
            $("span#word_counter").html(max_char - count);

            event.preventDefault();
        });

    </script>


</body
</html>
