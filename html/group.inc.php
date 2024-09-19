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

    $groupId = $profileInfo['id'];

    $myPage = false;

    if (auth::getCurrentUserId() == $profileInfo['accountAuthor']) {

        $myPage = true;
    }

    $accessMode = 0;

    if ($profileInfo['follow'] === true || $myPage) {

        $accessMode = 1;
    }

    $group = new group($dbo, $groupId);
    $group->setRequestFrom(auth::getCurrentUserId());

    $groupInfo = $group->get();

    $posts_all = $profileInfo['postsCount'];
    $posts_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $group->getPosts($itemId);

        $posts_loaded = count($result['items']);

        $result['posts_loaded'] = $posts_loaded + $loaded;
        $result['posts_all'] = $posts_all;

        if ($posts_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::post($value, $LANG, $helper, false);
            }

            $result['html'] = ob_get_clean();

            if ($result['posts_loaded'] < $posts_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Items.more('/<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $profilePhotoUrl = APP_URL."/img/profile_default_photo.png";
    $photo = '';

    if (strlen($profileInfo['normalPhotoUrl']) != 0) {

        $profilePhotoUrl = $profileInfo['normalPhotoUrl'];
        $photo = "/photo";
    }

    auth::newAuthenticityToken();

    $page_id = "group";

    $css_files = array("tipsy.css");
    $page_title = $profileInfo['fullname']." | ".APP_HOST."/".$profileInfo['username'];

    include_once("../html/common/header.inc.php");
?>

<body class="user-profile page-profile page-group">

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

                    <div id="profile-column-main" class="content-block col-sm-12 col-md-12 col-lg-12 order-md-1 order-lg-1">

                        <div class="sidebar-wrapper">

                            <div class="card">

                                <!-- Profile Cover -->

                                <div class="profile-cover">
                                    <div class="profile-main-cover">
                                        <div class="cover-wrapper d-flex">
                                            <div class="loader profile-cover-loader">
                                                <i class="fa fa-spin fa-spin"></i>
                                            </div>
                                            <div class="profile-cover-progress">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="profile-cover-img" style="background-image: url(<?php echo $profilePhotoUrl ?>);"></div>
                                            <?php

                                            if ($myPage) {

                                                ?>
                                                <div class="btn btn-secondary photo-upload-button">
                                                    <input type="file" id="photo-upload" name="uploaded_file">
                                                    <i class="iconfont icofont-camera-alt"></i>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div> <!-- End Profile Cover -->

                                <div class="profile-info">

                                    <div class="profile-info-block profile-main-info">

                                        <div class="d-flex">

                                            <div class="w-100 justify-content-end profile-main-info-container">
                                                <div class="fullname-line ">
                                                    <h1 class="display-name"><?php echo $profileInfo['fullname']; ?></h1>
                                                    <?php

                                                    if ($profileInfo['verified']) {

                                                        ?>
                                                        <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <?php

                                    if (auth::getCurrentUserId() != 0) {

                                        ?>

                                        <div class="profile-info-block profile-main-actions text-right pt-0">

                                            <?php

                                            if ($myPage) {

                                                ?>
                                                <a class="btn button blue btn-primary mb-2 mb-sm-0" href="/<?php echo $profileInfo['username']; ?>/settings">
                                                    <i class="ic icon-edit-2 mr-1"></i>
                                                    <?php echo $LANG['page-settings']; ?>
                                                </a>
                                                <?php

                                            } else {

                                                ?>

                                                <div class="js_follow_block">
                                                    <a class="button <?php if ($profileInfo['follow']) {echo "yellow";} else { echo "green"; } ?> js_follow_btn" href="javascript:void(0)" onclick="Users.follow('<?php echo $profileInfo['id']; ?>'); return false;">
                                                        <?php

                                                        if ($profileInfo['follow']) {

                                                            echo $LANG['action-unfollow'];

                                                        } else {

                                                            echo $LANG['action-follow'];
                                                        }
                                                        ?>
                                                    </a>
                                                </div>

                                                <?php
                                            }
                                            ?>

                                        </div>

                                        <?php

                                    } else {

                                        ?>
                                        <div class="profile-info-block profile-main-actions text-right pt-0">
                                            <span class="promo-msg mr-3"><?php echo sprintf($LANG['msg-contact-promo'], "<strong>" . $profileInfo['fullname'] . "</strong>"); ?></span>
                                            <a class="btn btn-primary mr-1" href="/signup"><?php echo $LANG['action-signup']; ?></a>
                                            <a class="btn btn-secondary" href="/"><?php echo $LANG['action-login']; ?></a>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>

                            </div>
                            <!-- end photos card -->

                        </div>
                    </div>

                    <div id="profile-column-content" class="row content-block col-sm-12 col-md-12 col-lg-12 order-md-1 order-lg-1">

                        <div class="column-content-sidebar col-md-4">

                            <?php

                                if (strlen($profileInfo['location']) != 0 || strlen($profileInfo['my_page']) != 0 || strlen($profileInfo['status']) != 0) {

                                    ?>
                                    <div class="card" id="preview-info-block">
                                        <div class="card-header border-0">
                                            <h3 class="card-title"><i class="icofont icofont-info-circle mr-2"></i><?php echo $LANG['label-profile-info']; ?></h3>
                                        </div>
                                        <div class="card-body p-3">
                                            <?php

                                            if (strlen($profileInfo['location']) != 0) {

                                                ?>
                                                <div class="addon-line d-flex align-content-center flex-column flex-sm-row">
                                                    <div class="user-location mt-2 mt-sm-0 ml-0">
                                                        <i class="iconfont icofont-location-pin mr-1"></i>
                                                        <?php echo $profileInfo['location']; ?>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <?php

                                            if (strlen($profileInfo['my_page']) != 0) {

                                                ?>
                                                <div class="addon-line d-flex align-content-center flex-column flex-sm-row">
                                                    <div class="user-link mt-2 mt-sm-0 ml-0">
                                                        <i class="iconfont icofont-link mr-1"></i>
                                                        <a target="_blank" rel="nofollow"
                                                           href="/go?to=<?php echo $profileInfo['my_page']; ?>"><?php echo $profileInfo['my_page']; ?></a>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <?php

                                            if (strlen($profileInfo['status']) != 0) {

                                                ?>
                                                <div class="addon-line d-flex align-content-center flex-column flex-sm-row">
                                                    <div class="user-bio mt-2 mt-sm-0 ml-0">
                                                        <i class="iconfont icofont-quote-right mr-1"></i>
                                                        <span><?php echo $profileInfo['status']; ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>

                            <div class="card hidden" id="preview-posts-block">
                                <div class="card-header border-0">
                                    <h3 class="card-title"><i class="icofont icofont-newspaper mr-2"></i><span class="counter-button-title"><?php echo $LANG['page-posts']; ?> <span id="stat_posts_count" class="counter-button-indicator"><?php if ($profileInfo['postsCount'] > 0) echo $profileInfo['postsCount']; ?></span></span></h3>
                                </div>
                            </div>

                            <div class="card" id="preview-people-block">
                                <div class="card-header border-0">
                                    <h3 class="card-title"><i class="icofont icofont-users-alt-4 mr-2"></i><span class="counter-button-title"><?php echo $LANG['page-followers']; ?> <span id="stat_friends_count" class="counter-button-indicator"><?php if ($profileInfo['friendsCount'] > 0) echo $profileInfo['friendsCount']; ?></span></span></h3>
                                    <span class="action-link">
                                        <a href="/<?php echo $profileInfo['username']; ?>/followers"><?php echo $LANG['action-show-all']; ?></a>
                                    </span>
                                </div>

                                <?php

                                if ($profileInfo['followersCount'] != 0) {

                                    ?>
                                    <div class="card-body p-2">
                                        <div class="grid-list row">

                                            <?php

                                            $result = $group->getFollowers(0, 6);

                                            foreach ($result['items'] as $key => $value) {

                                                draw::previewPeopleItem($value, $LANG, $helper);
                                            }
                                            ?>

                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>

                        <div class="column-content-items col-md-8">

                            <?php

                            if (auth::getCurrentUserId() != 0 && ($myPage || $groupInfo['allowPosts'] == 1)) {

                                ?>

                                <?php

                                    include_once("../html/common/postform.inc.php");
                                ?>

                                <?php
                            }
                            ?>

                            <div class="content-list-page section posts-list-page" style="margin: 0; padding: 0">

                                <?php

                                $result = $group->getPosts(0);

                                $posts_loaded = count($result['items']);

                                if ($posts_loaded != 0) {

                                    ?>

                                    <div class="items-list content-list">

                                        <?php

                                        $showed_ad = false;

                                        foreach ($result['items'] as $key => $value) {

                                            draw::post($value, $LANG, $helper, false);

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

                                    <?php

                                    $text = $LANG['label-empty-list'];

                                    ?>

                                    <div class="card information-banner">
                                        <div class="card-header">
                                            <div class="card-body">
                                                <h5 class="m-0"><?php echo $text; ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                }
                                ?>

                                <?php

                                if ($posts_all > 20) {

                                    ?>

                                    <header class="top-banner loading-banner">

                                        <div class="prompt">
                                            <button onclick="Items.more('/<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button green loading-button"><?php echo $LANG['action-more']; ?></button>
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

        </div>
    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript" src="/js/draggable_background.js"></script>

    <script type="text/javascript">

        var inbox_all = <?php echo $posts_all; ?>;
        var inbox_loaded = <?php echo $posts_loaded; ?>;

        var auth_token = "<?php echo auth::getAuthenticityToken(); ?>";

        <?php

            if ($myPage) {

                ?>
                    var myPage = true;
                <?php

                 if (strlen($profileInfo['normalCoverUrl']) != 0) {

                    ?>

                    var CoverExists = true;

                    <?php

                 } else {

                    ?>

                    var CoverExists = false;

                    <?php
                 }

                if (strlen($profileInfo['bigPhotoUrl']) != 0) {

                    ?>
                    var PhotoExists = true;
                    <?php

                } else {

                    ?>
                    var PhotoExists = false;
                    <?php
                }
            }
        ?>

        $("textarea[name=postText]").autosize();

        $("textarea[name=postText]").bind('keyup mouseout', function() {

            var max_char = 400;

            var count = $("textarea[name=postText]").val().length;

            $("span#word_counter").empty();
            $("span#word_counter").html(max_char - count);

            event.preventDefault();
        });

        $("#photo-upload").fileupload({
            formData: {accountId: <?php echo auth::getCurrentUserId(); ?>, accessToken: "<?php echo auth::getAccessToken(); ?>", imgType: 0, groupId: <?php echo $profileInfo['id']; ?>},
            name: 'image',
            url: "/api/v2/method/profile.uploadImg",
            dropZone:  '',
            dataType: 'json',
            singleFileUploads: true,
            multiple: false,
            maxNumberOfFiles: 1,
            maxFileSize: constants.MAX_FILE_SIZE,
            acceptFileTypes: "", // or regex: /(jpeg)|(jpg)|(png)$/i
            "files":null,
            minFileSize: null,
            messages: {
                "maxNumberOfFiles": "Maximum number of files exceeded",
                "acceptFileTypes": "File type not allowed",
                "maxFileSize": "File is too big",
                "minFileSize": "File is too small"},
            process: true,
            start: function (e, data) {

                console.log("start");

                $('div.profile-cover-progress').css("display", "block");
                $('div.profile-cover-loader').addClass('hidden');
                $('div.photo-upload-button').addClass('hidden');
                $('div.profile-cover-img').addClass('hidden');
            },
            processfail: function(e, data) {

                console.log("processfail");

                if (data.files.error) {

                    $infobox.find('#info-box-message').text(data.files[0].error);
                    $infobox.modal('show');
                }
            },
            progressall: function (e, data) {

                console.log("progressall");

                var progress = parseInt(data.loaded / data.total * 100, 10);

                $('div.profile-cover-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
            },
            done: function (e, data) {

                console.log("done");

                var result = jQuery.parseJSON(data.jqXHR.responseText);

                if (result.hasOwnProperty('error')) {

                    if (result.error === false) {

                        if (result.hasOwnProperty('normalPhotoUrl')) {

                            $("div.profile-cover-img").css("background-image", "url(" + result.normalPhotoUrl + ")");
                        }

                    } else {

                        $infobox.find('#info-box-message').text(result.error_description);
                        $infobox.modal('show');
                    }
                }
            },
            fail: function (e, data) {

                console.log("always");
            },
            always: function (e, data) {

                console.log("always");

                $('div.profile-cover-progress').css("display", "none");
                $('div.profile-cover-loader').removeClass('hidden');
                $('div.photo-upload-button').removeClass('hidden');
                $('div.profile-cover-img').removeClass('hidden');
            }

        });

    </script>


</body
</html>