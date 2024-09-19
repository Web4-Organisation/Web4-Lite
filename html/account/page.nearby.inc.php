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

    $gender = 3; // any
    $distance = 100;
    $sex_orientation = 0; // any

    if (isset($_COOKIE['nearby_gender'])) {

        $gender = isset($_COOKIE['nearby_gender']) ? $_COOKIE['nearby_gender'] : 3; // any

        $gender = helper::clearInt($gender);
    }

    if (isset($_COOKIE['nearby_distance'])) {

        $distance = isset($_COOKIE['nearby_distance']) ? $_COOKIE['nearby_distance'] : 30;

        $distance = helper::clearInt($distance);
    }

    $profile = new profile($dbo, auth::getCurrentUserId());
    $profileInfo = $profile->getVeryShort();
    unset($profile);

    $geo = new geo($dbo);
    $geo->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $geo->getPeopleNearby($itemId, $profileInfo['lat'], $profileInfo['lng'], $distance, $gender);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleItem($value, $LANG);
            }

            $result['html'] = ob_get_clean();

            if ($items_loaded == 20) {

                ob_start();

                ?>

                    <header class="top-banner loading-banner">

                        <div class="prompt">
                            <button onclick="NearbyItems.more('/account/nearby', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                        </div>

                    </header>

                <?php

                $result['html2'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "nearby";

    $css_files = array();
    $page_title = $LANG['page-nearby']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="manual-page">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <div class="col sn-content sn-content-wide-block" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-nearby']; ?>
                        </div>
                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['page-nearby-desc']; ?>
                        </div>

                        <div class="page-title-content-extra <?php if ($profileInfo['lat'] == 0 && $profileInfo['lng'] == 0) {echo "hidden";} ?>">

                            <div class="dropdown">

                                <a id="settings-button" class="extra-button button blue" data-toggle="dropdown" href="javascript:void(0)" ><i class="fa fa-sliders-h"></i></a>

                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                    <div class="dropdown__content no_autoloader" style="min-width: 250px">
                                        <div class="encounters-filter">

                                            <fieldset class="encounters-filter__field">
                                                <div class="encounters-filter__control">
                                                    <div class="search-filter-form-line">
                                                        <h5><?php echo $LANG['search-filters-gender']; ?></h5>
                                                        <label class="search-filter-radio-button" for="gender-radio-4">
                                                            <input type="radio" name="gender" id="gender-radio-4" value="3" <?php if ($gender == 3) echo "checked" ?>><?php echo $LANG['search-filters-all']; ?></label>
                                                        <label class="search-filter-radio-button" for="gender-radio-1">
                                                            <input type="radio" name="gender" id="gender-radio-1" value="0" <?php if ($gender == 0) echo "checked" ?>><?php echo $LANG['search-filters-male']; ?></label>
                                                        <label class="search-filter-radio-button" for="gender-radio-2">
                                                            <input type="radio" name="gender" id="gender-radio-2" value="1" <?php if ($gender == 1) echo "checked" ?>><?php echo $LANG['search-filters-female']; ?></label>
                                                    </div>

                                                    <div class="search-filter-form-line mt-3">
                                                        <h5><?php echo $LANG['label-distance']; ?> <span id="distance"><?php echo $distance; ?></span> km</h5>
                                                        <input id="distance-slider" type="text" name="distance" data-slider-min="30" data-slider-max="1500" data-slider-step="1" data-slider-value="<?php echo $distance; ?>"/>
                                                    </div>

                                                </div>
                                            </fieldset>

                                            <div class="encounters-filter__action">

                                                <div class="button-group button-group--horizontal">

                                                    <div class="button-group__item">
                                                        <button type="button" class="button blue btn--sm btn--block" id="apply-button">
                                                            <span class="btn__content">
                                                                <span class="btn__text"><?php echo $LANG['action-apply']; ?></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                    <div class="button-group__item">
                                                        <button type="button" class="button gray btn--sm btn--block js-toggle" id="close-button">
                                                            <span class="btn__content">
                                                                <span class="btn__text"><?php echo $LANG['action-cancel']; ?></span>
                                                            </span>
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="content-list-page">

                        <?php

                            if ($profileInfo['lat'] != 0 && $profileInfo['lng'] != 0) {

                                $result = $geo->getPeopleNearby(0, $profileInfo['lat'], $profileInfo['lng'], $distance, $gender);

                                $items_loaded = count($result['items']);

                                if ($items_loaded == 0) {

                                    ?>

                                        <header class="top-banner info-banner empty-list-banner">

                                        </header>
                                    <?php
                                }

                            } else {

                                ?>
                                    <header class="top-banner info-banner-2 text-center d-block">
                                        <h5 class=""><?php echo $LANG['label-location-request']; ?></h5>
                                        <button class="btn blue mt-2 hidden" onclick="getLocation();"><?php echo $LANG['action-allow']; ?></button>
                                    </header>
                                <?php
                            }
                        ?>


                    </div>

                </div>

                <div class="main-content cardview-content">

                    <div class="standard-page cardview-container items-container content-list-page">

                        <?php

                        if ($items_loaded != 0) {

                            ?>

                            <div class="grid-list items-view">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::peopleItem($value, $LANG);
                                }
                                ?>
                            </div>

                            <?php

                            if ($items_loaded == 20) {

                                ?>

                                <header class="top-banner loading-banner">

                                    <div class="prompt">
                                        <button onclick="NearbyItems.more('/account/nearby', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }
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

            var lat = <?php echo $profileInfo['lat']; ?>;
            var lng = <?php echo $profileInfo['lng']; ?>;

            strings.sz_message_location_request = "<?php echo $LANG['label-location-request']; ?>";
            strings.sz_message_location_denied = "<?php echo $LANG['label-location-denied']; ?>";
            strings.sz_message_location_unsupported = "<?php echo $LANG['label-location-unsupported']; ?>";

            $(document).ready(function() {

                $("#distance-slider").slider();

                $("#distance-slider").on("change", function(slideEvt) {

                    $("#distance").text(slideEvt.value.newValue);
                });

                if (lat == 0 && lng == 0) {

                    if (navigator.geolocation) {

                        $('.info-banner-2').find('h5').text(strings.sz_message_location_request);
                        $('.info-banner-2').find('button').removeClass("hidden");

                    } else {

                        $('.info-banner-2').find('h5').text(strings.sz_message_location_unsupported);
                    }

                } else {


                }

                $("#apply-button").click(function(){

                    var gender = $("input[name='gender']:checked").val();
                    var distance = $("input[name='distance']").val();

                    $.cookie("nearby_gender", gender, { expires : 7, path: '/' });
                    $.cookie("nearby_distance", distance, { expires : 7, path: '/' });

                    $(this).parents('.dropdown').find('#settings-button').dropdown('toggle');

                    if ($("div.items-view").length == 0) {

                        $("div.items-container").html("<div class=\"cardview items-view\"></div>");

                    } else {

                        $("div.items-view").html("");
                    }

                    NearbyItems.more('/account/nearby', 0);
                });

            });

            function getLocation() {

                var watchId = navigator.geolocation.watchPosition(function(position) {

                        lat = position.coords.latitude;
                        lng = position.coords.longitude;

                        console.log("Lat: " + position.coords.latitude);
                        console.log("Lng: " + position.coords.longitude);

                        if (lat != 0 && lng != 0) {

                            $('.info-banner-2').addClass("hidden");

                            navigator.geolocation.clearWatch(watchId);

                            setLocation(lat, lng);
                        }

                    }, function(error) {

                        if (error.code == error.PERMISSION_DENIED) {

                            $('.info-banner-2').find('h5').text(strings.sz_message_location_denied);
                            $('.info-banner-2').find('button').addClass("hidden");
                        }

                    }, {

                        maximumAge: Infinity,
                        timeout: Infinity
                    });

            }

            function setLocation(lat, lng) {

                $.ajax({
                    type: 'POST',
                    url: '/api/' + options.api_version + '/method/account.setGeoLocation',
                    data: 'accountId=' + account.id + "&accessToken=" + account.accessToken + "&lat=" + lat + "&lng=" + lng,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('error')) {

                            if (response.error === false) {

                                if ($("div.items-view").length == 0) {

                                    $("div.items-container").html("<div class=\"cardview items-view\"></div>");
                                }

                                $("div.page-title-content-extra").removeClass("hidden");

                                NearbyItems.more('/account/nearby', 0);
                            }
                        }
                    },
                    error: function(xhr, type){

                        //
                    }
                });
            }

        </script>

</body>
</html>
