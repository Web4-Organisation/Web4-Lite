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

    $account = new account($dbo, auth::getCurrentUserId());
    $account->setLastGuestsView();
    unset($account);

    $guests = new guests($dbo, auth::getCurrentUserId());
    $guests->setRequestFrom(auth::getCurrentUserId());

    $items_all = $guests->count();
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $act = isset($_POST['act']) ? $_POST['act'] : '';
        $access_token = isset($_POST['access_token']) ? $_POST['access_token'] : '';

        if ($act === 'clear' && $access_token === auth::getAccessToken()) {

            $guests->clear();

            exit;
        }

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $guests->get($itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::guestItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Guests.more('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "guests";

    $css_files = array("main.css", "tipsy.css", "my.css");
    $page_title = $LANG['page-guests']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="guests-page">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <div class="row col sn-content sn-content-wide-block" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-guests']; ?>
                        </div>

                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['label-guests-sub-title']; ?>
                        </div>

                        <?php

                            if ($items_all > 0) {

                                ?>
                                <div class="page-title-content-extra">
                                    <a class="extra-button button blue" href="javascript:void(0)" onclick="Guests.clear('<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo$LANG['action-clear-all']; ?></a>
                                </div>
                                <?php
                            }

                        ?>

                    </div>

                    <div class="content-list-page mt-3">

                        <?php

                        $result = $guests->get(0);

                        $items_loaded = count($result['items']);

                        if ($items_loaded != 0) {

                            ?>

                            <div class="content-list grid-list">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::guestItem($value, $LANG, $helper);
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

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Guests.more('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

        var items_all = <?php echo $items_all; ?>;
        var items_loaded = <?php echo $items_loaded; ?>;

    </script>


</body
</html>