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

    if (!auth::isSession() && !WEB_EXPLORE) {

        header('Location: /');
        exit;
    }

    $query = '';

    $u_online = -1;
    $u_gender = -1;
    $u_photo = -1;

    $search = new search($dbo);
    $search->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (isset($_GET['query'])) {

        $query = isset($_GET['query']) ? $_GET['query'] : '';

        $u_online = isset($_GET['online']) ? $_GET['online'] : 'all';
        $u_gender = isset($_GET['gender']) ? $_GET['gender'] : -1;
        $u_photo = isset($_GET['photo']) ? $_GET['photo'] : 'all';

        $u_online = helper::clearText($u_online);
        $u_online = helper::escapeText($u_online);

        $u_photo = helper::clearText($u_photo);
        $u_photo = helper::escapeText($u_photo);

        $u_gender = helper::clearInt($u_gender);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        if ($u_online === "yes") {

            $u_online = 1;

        } else {

            $u_online = -1;
        }

        if ($u_photo === "yes") {

            $u_photo = 1;

        } else {

            $u_photo = -1;
        }
    }

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;
        $query = isset($_POST['query']) ? $_POST['query'] : '';

        $u_online = isset($_POST['online']) ? $_POST['online'] : -1;
        $u_gender = isset($_POST['gender']) ? $_POST['gender'] : -1;
        $u_photo = isset($_POST['photo']) ? $_POST['photo'] : -1;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        if ($u_gender != -1) $u_gender = helper::clearInt($u_gender);
        if ($u_online != -1) $u_online = helper::clearInt($u_online);
        if ($u_photo != -1) $u_photo = helper::clearInt($u_photo);

        $result = $search->query($query, $itemId, $u_gender, $u_online, $u_photo);


//        if (strlen($query) > 0) {
//
//            $result = $search->query($query, $itemId, $u_gender, $u_online, $u_photo);
//
//        } else {
//
//            $result = $search->preload($itemId, $u_gender, $u_online, $u_photo);
//        }

        $items_loaded = count($result['items']);
        $items_all = $result['itemCount'];


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

                <header class="more-card top-banner loading-banner border-0">

                    <span class="button-more-loader hidden">
                        <svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 90" enable-background="new 0 0 0 0" xml:space="preserve" style="width: 100px; height: 36px;">
                            <circle fill="var(--icon_tint)" stroke="none" cx="6" cy="50" r="6">
                                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"></animate>
                            </circle>
                            <circle fill="var(--icon_tint)" stroke="none" cx="26" cy="50" r="6">
                                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"></animate>
                            </circle>
                            <circle fill="var(--icon_tint)" stroke="none" cx="46" cy="50" r="6">
                                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"></animate>
                            </circle>
                        </svg>
                    </span>

                    <div class="prompt">
                        <button onclick="Finder.go('<?php echo $result['itemId']; ?>'); return false;" class="button more more-button loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search";

    $css_files = array();
    $page_title = $LANG['page-search']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-search">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/search/search_nav.inc.php");
            ?>

            <div class="row col-lg-7 col-md-12 sn-content" id="content">

                <div class="main-content">

                    <div class="card card-search-box">

                        <form id="search-form" class="search-form" method="get" action="/search/name">

                            <div class="standard-page page-title-content shadow-none">
                                <div class="page-title-content-inner">
                                    <?php echo $LANG['tab-search-users']; ?>
                                </div>
                                <div class="page-title-content-bottom-inner">
                                    <?php echo $LANG['tab-search-users-description']; ?>
                                </div>
                            </div>

                            <div class="standard-page search-box">

                                <div class="search-editbox-line">

                                    <input class="search-field" name="query" id="query" autocomplete="off" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">

                                    <button type="submit" class="button primary"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                                </div>

                            </div>

                        </form>

                    </div>

                    <?php


                        $result = $search->query($query, 0, $u_gender, $u_online, $u_photo);

                        $items_all = $result['itemCount'];
                        $items_loaded = count($result['items']);
                    ?>

                    <div class="card loading-card text-center border-0 bg-transparent shadow-none hidden">
                        <div class="card-header border-0">

                            <svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 50 100" enable-background="new 0 0 0 0" xml:space="preserve" style="width: 70px; height: 70px;">
                                    <circle fill="var(--icon_tint)" stroke="none" cx="6" cy="50" r="6">
                                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"/>
                                    </circle>
                                <circle fill="var(--icon_tint)" stroke="none" cx="26" cy="50" r="6">
                                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"/>
                                </circle>
                                <circle fill="var(--icon_tint)" stroke="none" cx="46" cy="50" r="6">
                                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"/>
                                </circle>
                                </svg>

                        </div>
                    </div>

                    <div class="card empty-card text-center border-0 bg-transparent shadow-none <?php if ($items_loaded != 0) echo 'hidden'; ?> ">
                        <div class="card-header border-0">
                            <h5><?php echo $LANG['label-search-empty']; ?></h5>
                            <h7><?php echo $LANG['label-search-empty-desc']; ?></h7>
                        </div>
                    </div>

                    <div class="card result-card hidden">
                        <header class="top-banner">
                            <div class="info">
                                <h1><?php echo $LANG['label-search-result']; ?> (<span id="finder-result"></span>)</h1>
                            </div>
                        </header>
                    </div>

                    <div class="content-list-page">

                        <div class="grid-list row">

                            <?php

                            if ($items_loaded != 0) {

                                foreach ($result['items'] as $key => $value) {

                                    draw::peopleItem($value, $LANG, $helper);
                                }
                            }
                            ?>

                        </div>

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="more-card top-banner loading-banner border-0">

                                <span class="button-more-loader hidden">
                                    <svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 90" enable-background="new 0 0 0 0" xml:space="preserve" style="width: 100px; height: 36px;">
                                    <circle fill="var(--icon_tint)" stroke="none" cx="6" cy="50" r="6">
                                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1"></animate>
                                    </circle>
                                    <circle fill="var(--icon_tint)" stroke="none" cx="26" cy="50" r="6">
                                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2"></animate>
                                    </circle>
                                    <circle fill="var(--icon_tint)" stroke="none" cx="46" cy="50" r="6">
                                        <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3"></animate>
                                    </circle>
                                    </svg>
                                </span>

                                <div class="prompt">
                                    <button onclick="Finder.go('<?php echo $result['itemId']; ?>'); return false;" class="button more more-button loading-button"><?php echo $LANG['action-more']; ?></button>
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
        var query = "<?php echo $query; ?>";

        var $onlineCheckbox = $("#switch-online-button");
        var $photoCheckbox = $("#switch-photo-button");
        var $genderSelect = $("select#gender");

        var $searchBox = $('input.search-field');

        $(document).ready(function() {

            $(window).resize(function() {

                if ($(this).width() >= 960) {

                    // card-search-box

                    $('div.sidebar-right-menu').append($('div.card-search-filters'));

                } else {

                    $('div.card-search-box').after($('div.card-search-filters'));
                }
            });

            $($onlineCheckbox).change(function() {

                Finder.go(0);
            });

            $($photoCheckbox).change(function() {

                Finder.go(0);
            });

            $genderSelect.on('change', function() {

                Finder.go(0);
            });

            $("#search-form").submit(function(e) {

                var query = $.trim($searchBox.val());

                if (query.length != 0) {

                    Finder.go(0);
                }

                //prevent Default functionality
                e.preventDefault();

                //get the action-url of the form
                //var actionurl = e.currentTarget.action;

            });

        });

        window.Finder || ( window.Finder = {} );

        Finder.go = function (offset) {

            var $loadingCard = $('div.loading-card');
            var $emptyCard = $('div.empty-card');
            var $resultCard = $('div.result-card');
            var $contentList = $('div.content-list-page');
            var $gridList = $('div.grid-list');
            var $moreCard = $('header.more-card');

            var $moreButton = $('button.more-button');
            var $moreButtonLoader = $('span.button-more-loader');

            //

            var query = $.trim($searchBox.val());
            var gender = $genderSelect.find(":selected").val();

            var online = -1;

            if ($onlineCheckbox.is(":checked")) {

                online = 1;
            }

            var photo = -1;

            if ($photoCheckbox.is(":checked")) {

                photo = 1;
            }

            $genderSelect.attr('disabled', 'disabled');
            $onlineCheckbox.attr('disabled', 'disabled');
            $photoCheckbox.attr('disabled', 'disabled');

            $emptyCard.addClass('hidden');

            if (offset == 0) {

                items_loaded = 0;

                $moreCard.remove();

                $resultCard.addClass('hidden');
                $loadingCard.removeClass('hidden');
                $contentList.addClass('hidden');
            }

            $moreButton.attr("disabled", "disabled");
            $moreButtonLoader.removeClass('hidden');

            $.ajax({
                type: 'POST',
                url: '/search/name',
                data: 'itemId=' + offset + "&loaded=" + items_loaded + "&query=" + query + "&online=" + online + "&gender=" + gender + "&photo=" + photo,
                dataType: 'json',
                timeout: 30000,
                success: function(response) {

                    $moreCard.remove();

                    if (offset == 0) {

                        $gridList.html('');
                    }

                    if (response.hasOwnProperty('html')){

                        $gridList.append(response.html);
                    }

                    if (response.hasOwnProperty('banner')){

                        $contentList.append(response.banner);
                    }

                    $loadingCard.addClass('hidden');

                    if (query.length != 0 && response.items_all != 0) {

                        $resultCard.removeClass('hidden');
                        $('span#finder-result').text(response.items_all);
                    }

                    if ($('div.grid-item').length != 0) {

                        $contentList.removeClass('hidden');

                    } else {

                        $emptyCard.removeClass('hidden');
                    }

                    $genderSelect.removeAttr('disabled');
                    $onlineCheckbox.removeAttr('disabled');
                    $photoCheckbox.removeAttr('disabled');

                    items_loaded = response.items_loaded;
                    items_all = response.items_all;
                },
                error: function(xhr, type) {

                    $moreButton.removeAttr("disabled");
                    $moreButtonLoader.addClass('hidden');
                }
            });
        };

    </script>


</body
</html>
