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

    $profile = new profile($dbo, auth::getCurrentUserId());

    $refsys = new refsys($dbo);
    $refsys->setRequestFrom(auth::getCurrentUserId());

    $items_all = $refsys->getReferralsCount(auth::getCurrentUserId());
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : '';
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $refsys->getReferrals($itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleItem($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Referrals.more('<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "referrals";

    $css_files = array("main.css");
    $page_title = $LANG['page-referrals']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="cards-page settings-page">


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

            <div class="row col sn-content d-block" id="content">

                <div class="card">

                    <div class="standard-page">

                        <h1 class="title"><?php echo $LANG['page-referrals']; ?></h1>

                        <h1><?php echo $LANG['page-referrals-label-id']; ?> <?php echo auth::getCurrentUserId(); ?></h1>
                        <p><?php echo $LANG['page-referrals-label-hint'] ?></p>
                        <p><?php echo $LANG['page-referrals-label-hint2']; ?></p>

                    </div>

                </div>

                <div class="content-list-page">



                    <?php

                    $result = $refsys->getReferrals(0);

                    $items_loaded = count($result['items']);

                    if ($items_loaded != 0) {

                        ?>

                        <div class="grid-list row">

                            <?php

                            foreach ($result['items'] as $key => $value) {

                                draw::peopleItem($value, $LANG, $helper);
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
                                <button onclick="Referrals.more('<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                            </div>

                        </header>

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

        window.Referrals || ( window.Referrals = {} );

        Referrals.more = function (offset) {

            $('button.loading-button').attr("disabled", "disabled");

            $.ajax({
                type: 'POST',
                url: '/account/settings/referrals',
                data: 'itemId=' + offset + "&loaded=" + items_loaded,
                dataType: 'json',
                timeout: 30000,
                success: function(response){

                    $('header.loading-banner').remove();

                    if (response.hasOwnProperty('html')){

                        $("div.grid-list").append(response.html);
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

    </script>


</body
</html>

<?php

    function draw($item, $LANG, $helper)
    {

        $time = new language(NULL, $LANG['lang-code']);

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($item['normalPhotoUrl']) != 0) {

            $profilePhotoUrl = $item['normalPhotoUrl'];

        }

        ?>

                <li class="custom-list-item item" data-id="<?php echo $item['id']; ?>">

                    <a href="/<?php echo $item['username']; ?>" class="item-logo" style="background-image:url(<?php echo $profilePhotoUrl; ?>)"></a>

                    <a href="/<?php echo $item['username']; ?>" class="custom-item-link"><?php echo  $item['fullname']; ?></a>

                    <div class="item-meta">
                        <span class="featured">@<?php echo $item['username']; ?></span>
                    </div

                </li>

        <?php
    }

?>