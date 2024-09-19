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
        exit;
    }

    $profile = new profile($dbo, auth::getCurrentUserId());
    $profile->setRequestFrom(auth::getCurrentUserId());

    $friends = new friends($dbo, auth::getCurrentUserId());
    $friends->setRequestFrom(auth::getCurrentUserId());

    $friend_requests_all = $profile->getFollowersCount();
    $friend_requests_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['id']) ? $_POST['id'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $friends->getInboxRequests($itemId);

        $friend_requests_loaded = count($result['items']);

        $result['friend_requests_loaded'] = $friend_requests_loaded + $loaded;
        $result['friend_requests_all'] = $friend_requests_all;

        if ($friend_requests_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::inboxFriendRequestItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();



            if ($result['friend_requests_loaded'] < $friend_requests_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Friends.moreInboxRequests('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "friends_inbox_requests";

    $css_files = array("main.css", "tipsy.css", "my.css");
    $page_title = $LANG['page-friends-requests']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-friends-inbox-requests">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/account/friends/friends_nav.inc.php");
            ?>

            <div class="row col sn-content" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-friends-requests']; ?>
                        </div>

                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['label-friends-inbox-requests-sub-title']; ?>
                        </div>

                        <div class="page-title-content-extra">
                            <a class="extra-button button blue" href="/search/name"><?php echo$LANG['label-friends-search-sub-title']; ?></a>
                        </div>

                    </div>

                    <div class=" content-list-page mt-3">

                        <?php

                        $result = $friends->getInboxRequests(0);

                        $friend_requests_loaded = count($result['items']);

                        if ($friend_requests_loaded != 0) {

                            ?>

                            <div class="card cards-list content-list">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::inboxFriendRequestItem($value, $LANG, $helper);
                                }
                                ?>
                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card information-banner border-0">
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

                        if ($friend_requests_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Friends.moreInboxRequests('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

    <script type="text/javascript" src="/js/friends.js?x=2"></script>

    <script type="text/javascript">

        var friend_requests_all = <?php echo $friend_requests_all; ?>;
        var friend_requests_loaded = <?php echo $friend_requests_loaded; ?>;

    </script>


</body
</html>
