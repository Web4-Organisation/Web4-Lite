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

    $stream = new stream($dbo);
    $stream->setRequestFrom($auth::getCurrentUserId());

    if (isset($_COOKIE['feed-mode'])) {

        if ($_COOKIE['feed-mode'] === "true") {

            $stream = new feed($dbo);
        }
    }

    $stream->setRequestFrom(auth::getCurrentUserId());

    $inbox_all = $stream->count();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $stream->get($itemId);

        $inbox_loaded = count($result['items']);
        $alerts_loaded = count($result['alerts']);

        if ($itemId == 0) {

            $loaded = 0;
        }

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0 || $alerts_loaded != 0) {

            ob_start();

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

            //

            // Show posts

            $showed_ad = false;

            foreach ($result['items'] as $key => $value) {

                draw::post($value, $LANG, $helper);

                if (!$showed_ad) {

                    $showed_ad = true;

                    require_once ("../html/common/adsense_banner.inc.php");
                }
            }

            $result['html'] = ob_get_clean();

            if ($result['inbox_loaded'] < $inbox_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Wall.more('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button noselect"><?php echo $LANG['action-more']; ?></button>
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

    $page_id = "wall";

    $css_files = array();
    $page_title = $LANG['page-wall']." | ".APP_TITLE;

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

            <div class="row col sn-content sn-content-sidebar-block" id="content">

                <div class="main-content">

                    <div class="main-content">

                        <div class="card">

                            <div class="card-header">

                                <div class="header-text-block">
                                    <h3 class="card-title sw-title"><?php echo $LANG['page-wall']; ?></h3>
                                    <h5 class="card-description sw-description"><?php echo $LANG['page-news-description']; ?></h5>
                                </div>

                                <label class="switch header-switch">
                                    <input id="switch-stream-mode-button" type="checkbox">
                                    <span class="sw-slider round"></span>
                                </label>

                            </div>
                        </div>

                        <?php

                            if (auth::getCurrentUserOtpVerified() == 0) {

                                if (!isset($_COOKIE['tooltip_otp_verification'])) {

                                    ?>
                                        <div class="card tooltip-card" data-id="tooltip_otp_verification">

                                            <div class="tooltip-header">

                                                <a data-id="tooltip_otp_verification" class="tooltip-close" title="<?php echo $LANG['action-close']; ?>">
                                                    <i class="fa fa-times"></i>
                                                </a>

                                                <span class="tooltip-title">
                                                    <?php echo $LANG['tooltip-otp-verification-title']; ?>
                                                </span>

                                                <span class="tooltip-subtitle">
                                                    <?php echo $LANG['tooltip-otp-verification-desc']; ?>
                                                </span>

                                                <a href="/account/settings/otp" class="button primary tooltip-action-button"><?php echo $LANG['tooltip-otp-verification-action']; ?></a>

                                            </div>

                                        </div>
                                    <?php
                                }
                            }
                        ?>

                        <?php
                            include_once("../html/common/postform.inc.php");
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

                        <div class="content-list-page posts-list-page posts-list-page-bordered">

                            <div class="items-list content-list">

                            <?php

                            $result = $stream->get(0);

                            $inbox_loaded = count($result['items']);
                            $alerts_loaded = count($result['alerts']);

                            if ($inbox_loaded != 0 || $alerts_loaded != 0) {

                                ?>
                                    <?php

                                        // Show admin alert

                                        if ($alerts_loaded != 0) {

                                            $result['alerts'][0]['admin_account'] = 1;

                                            if (isset($_COOKIE['hidden_item_id'])) {

                                                if ($_COOKIE['hidden_item_id'] != $result['alerts'][0]['id']) {

                                                    draw::post($result['alerts'][0], $LANG, $helper);

                                                } else {

                                                    $alerts_loaded = 0;
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

                                <?php
                            }
                            ?>

                            </div>

                            <?php

                            if ($inbox_all > 20) {

                                ?>

                                <header class="top-banner loading-banner">

                                    <div class="prompt">
                                        <button onclick="Wall.more('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button noselect"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }
                            ?>

                        </div>

                        <div class="card empty-card text-center border-0 bg-transparent shadow-none hidden">
                            <div class="card-header border-0">
                                <h5><?php echo $LANG['label-empty-list']; ?></h5>
                                <h7><?php echo $LANG['label-empty-wall-desc']; ?></h7>
                            </div>
                        </div>

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

        function updateSwitchBtn() {

            if (typeof $.cookie('feed-mode') === 'undefined') {

                $('#switch-stream-mode-button').removeAttr('checked');
                $('h3.sw-title').text(strings.sz_page_stream);
                $('h5.sw-description').text(strings.sz_page_stream_description);

            } else {

                if ($.cookie('feed-mode') === 'true') {

                    $('#switch-stream-mode-button').attr('checked','checked')
                    $('h3.sw-title').text(strings.sz_page_wall);
                    $('h5.sw-description').text(strings.sz_page_wall_description);

                } else {

                    $('#switch-stream-mode-button').removeAttr('checked');
                    $('h3.sw-title').text(strings.sz_page_stream);
                    $('h5.sw-description').text(strings.sz_page_stream_description);
                }
            }
        }

        window.Wall || ( window.Wall = {} );

        Wall.more = function (offset) {

            $('div.empty-card').addClass('hidden');

            if (offset == 0) {

                $('div.loading-card').removeClass('hidden');

                $('header.loading-banner').remove();
                $('div.items-list').html('');
            }

            $('#switch-stream-mode-button').attr('disabled','disabled')

            $('button.loading-button').attr("disabled", "disabled");

            $.ajax({
                type: 'POST',
                url: '/account/wall',
                data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
                dataType: 'json',
                timeout: 30000,
                success: function(response) {

                    $('div.loading-card').addClass('hidden');
                    $('#switch-stream-mode-button').removeAttr('disabled');

                    console.log("success");

                    $('header.loading-banner').remove();

                    if (response.hasOwnProperty('html')){

                        $("div.items-list").append(response.html);
                    }

                    if (response.hasOwnProperty('banner')){

                        $("div.posts-list-page").append(response.banner);
                    }

                    if ($('div.items-list').children().length == 0) {

                        $('div.empty-card').removeClass('hidden');
                    }

                    inbox_loaded = response.inbox_loaded;
                    inbox_all = response.inbox_all;
                },
                error: function(xhr, type) {

                    $('div.loading-card').addClass('hidden');
                    $('#switch-stream-mode-button').removeAttr('disabled');

                    if ($('div.items-list').children().length == 0) {

                        $('div.empty-card').removeClass('hidden');
                    }
                }
            });
        };

        $(document).ready(function() {

            if (typeof $.cookie('feed-mode') === 'undefined') {

                $.cookie("feed-mode", false);
            }

            updateSwitchBtn();

            if ($('div.items-list').children().length == 0) {

                $('div.empty-card').removeClass('hidden');
            }

            $('#switch-stream-mode-button').change(function() {

                if (typeof $.cookie('feed-mode') === 'undefined') {

                    $.cookie("feed-mode", false);

                } else {

                    if ($.cookie('feed-mode') === 'true') {

                        $.cookie("feed-mode", false);

                    } else {

                        $.cookie("feed-mode", true);
                    }
                }

                updateSwitchBtn();

                Wall.more(0);
            });

        });

    </script>


</body
</html>
