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

    $user = new profile($dbo, $profileId);

    $user->setRequestFrom(auth::getCurrentUserId());
    $profileInfo = $user->get();

    if ($profileInfo['error'] === true) {

        include_once("../html/error.inc.php");
        exit;
    }

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("../html/stubs/profile.inc.php");
        exit;
    }

    if ($profileInfo['accountType'] != ACCOUNT_TYPE_USER) {

        header("Location: /");
        exit;
    }

    $gifts = new gift($dbo);
    $gifts->setRequestFrom($profileId);

    $items_all = $gifts->count();
    $items_loaded = 0;

    if (isset($_GET['action'])) {

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $itemId = isset($_GET['itemId']) ? $_GET['itemId'] : 0;

        $itemId = helper::clearInt($itemId);

        switch ($action) {

            case "delete": {

                $gifts->setRequestFrom(auth::getCurrentUserId());
                $result = $gifts->remove($itemId);

                echo json_encode($result);
                exit;

                break;
            }

            default: {

                break;
            }
        }
    }

    if (!empty($_POST)) {

        $id = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $id = helper::clearInt($id);
        $loaded = helper::clearInt($loaded);

        $result = $gifts->get($profileId, $id);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ( $items_loaded != 0 ) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button
                            onclick="Gifts.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;"
                            class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "gifts";

    $css_files = array("main.css", "my.css", "tipsy.css", "gifts.css");
    $page_title = $LANG['page-gifts']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-wall">


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

                    <div class="standard-page page-title-content profile-content">
                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-gifts']; ?>
                        </div>
                    </div>

                    <div class="content-list-page posts-list-page posts-list-page-bordered mt-3">

                        <?php

                        if ($profileInfo['id'] != auth::getCurrentUserId() && !$profileInfo['friend'] && $profileInfo['allowShowMyGifts'] == 1) {

                            ?>
                                <header class="top-banner info-banner">

                                    <div class="info">
                                        <h1><?php echo $LANG['label-error-permission']; ?></h1>
                                    </div>

                                </header>
                            <?php

                        } else {

                            $result = $gifts->get($profileId, 0);

                            $items_loaded = count($result['items']);

                            if ($items_loaded != 0) {

                                ?>

                                <div class="items-list content-list">

                                    <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw($value, $LANG, $helper);
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
                                        <button onclick="Gifts.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

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

            window.Gifts || ( window.Gifts = {} );

            Gifts.more = function (profile, offset) {

                $('button.loading-button').attr("disabled", "disabled");

                $.ajax({
                    type: 'POST',
                    url: '/' + profile + '/gifts',
                    data: 'itemId=' + offset + "&loaded=" + items_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('header.loading-banner').remove();

                        if (response.hasOwnProperty('html')){

                            $("ul.content-list").append(response.html);
                        }

                        if (response.hasOwnProperty('banner')){

                            $("div.content-list-page").append(response.banner);
                        }

                        items_loaded = response.items_loaded;
                        items_all = response.items_all;
                    },
                    error: function(xhr, type){

                        $('button.loading-button').removeAttr("disabled");
                    }
                });
            }

            Gifts.remove = function (profile, gift) {

                $.ajax({
                    type: 'GET',
                    url: '/' + profile + '/gifts',
                    data: 'itemId=' + gift + "&action=delete",
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        $('.card[data-id='+ gift +']').remove();
                    },
                    error: function(xhr, type){


                    }
                });
            }

        </script>


</body
</html>


<?php

    function draw($giftInfo, $LANG, $helper = null)
    {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($giftInfo['giftFromUserPhoto']) != 0) {

            $profilePhotoUrl = $giftInfo['giftFromUserPhoto'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <div class="card " data-id="<?php echo $giftInfo['id']; ?>">

            <div class="custom-list-item post-item" data-id="305">

                <div class="mb-2 item-header">

                    <a href="/<?php echo $giftInfo['giftFromUserUsername']; ?>" class="item-logo" style="background-image:url(<?php echo $profilePhotoUrl; ?>)"></a>

                    <?php

                        if (auth::getCurrentUserId() != 0 && $giftInfo['giftTo'] == auth::getCurrentUserId()) {

                            ?>
                                <div class="dropdown">
                                    <a class="mb-sm-0 item-menu" data-toggle="dropdown">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </a>

                                    <div class="dropdown-menu">

                                        <a class="dropdown-item" href="javascript:void(0)" onclick="Gifts.remove('<?php echo auth::getCurrentUserLogin(); ?>', '<?php echo $giftInfo['id']; ?>'); return false;"><?php echo $LANG['action-remove']; ?></a>

                                    </div>
                                </div>
                            <?php
                        }
                    ?>

                    <?php
                        if ($giftInfo['giftFromUserOnline']) {

                            ?>
                                <span title="Online" class="item-logo-online"></span>
                            <?php
                        }
                    ?>



                    <a href="/<?php echo $giftInfo['giftFromUserUsername']; ?>" class="custom-item-link post-item-fullname"><?php echo $giftInfo['giftFromUserFullname']; ?></a>

                    <?php
                        if ($giftInfo['giftFromUserVerified'] == 1) {

                            ?>
                                <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                            <?php
                        }
                    ?>


                    <span class="post-item-time"><?php echo $giftInfo['timeAgo']; ?></span>

                </div>

                <div class="item-meta post-item-content">

                    <?php

                    if (strlen($giftInfo['message']) > 0) {

                        ?>
                        <p class="post-text mx-2"><?php echo $giftInfo['message']; ?></p>
                        <?php
                    }
                    ?>

                    <img class="post-img mb-3" alt="post-img" style="width: 192px; margin: auto;" src="<?php echo $giftInfo['imgUrl']; ?>">


                </div>

            </div>
        </div>

        <?php
    }

?>
