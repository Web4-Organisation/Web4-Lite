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

    if (!isset($_SESSION['welcome_hash'])) {

        header('Location: /');
        exit;

    } else {

        unset($_SESSION['welcome_hash']);
    }

    $accountId = auth::getCurrentUserId();

    $page_id = "welcome";

    $css_files = array();
    $page_title = $LANG['page-welcome']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");
?>

<body class="welcome-page ">

    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column">

            <div class="main-content">

                <div class="profile-content standard-page">

                    <header class="top-banner">

                        <div class="info">
                            <h1><?php echo $LANG['page-welcome']; ?></h1>
                            <p><?php echo $LANG['page-welcome-sub-title']; ?></p>
                        </div>

                        <div class="prompt">
                            <a href="/account/wall" class="button green"><?php echo $LANG['action-start']; ?></a>
                        </div>

                    </header>

                    <div class="welcome-content">

                        <h1><?php echo $LANG['page-welcome-message-1']; ?></h1>
                        <h3><?php echo $LANG['page-welcome-message-2']; ?></h3>
                        <h3><?php echo $LANG['page-welcome-message-3']; ?></h3>

                        <div class="user-info welcome-photo-box">

                            <div class="profile-photo-progress hidden">
                                <div style="height: 15px" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="profile_img_wrap">
                                <img alt="Photo" class="profile-user-photo user_image" width="90" height="90px" src="/img/profile_default_photo.png">
                                <span class="change_image" onclick="Profile.changePhoto('<?php echo $LANG['action-change-photo']; ?>', '<?php echo auth::getCurrentUserId(); ?>', '<?php echo auth::getAccessToken(); ?>'); return false;"><?php echo $LANG['action-change-photo']; ?>
                                <input type="file" id="photo-upload" name="uploaded_file" style="width: 100%; height: 100%; position: absolute;display: block; opacity: 0; cursor: pointer; z-index: 2; left: 0; top: 0;"></span>
                            </div>

                        </div>

                        <h4 class="d-block"><?php echo $LANG['page-welcome-message-4']; ?></h4>

                    </div>

                    <div class="d-block text-center m-4">
                        <a class="button primary" href="/account/wall"><?php echo $LANG['action-start']; ?></a>
                    </div>

                </div>


            </div>
        </div>


    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        $("#photo-upload").fileupload({
            formData: {accountId: <?php echo auth::getCurrentUserId(); ?>, accessToken: "<?php echo auth::getAccessToken(); ?>", imgType: 0},
            name: 'image',
            url: "/api/" + options.api_version + "/method/profile.uploadImg",
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
                "maxNumberOfFiles":"Maximum number of files exceeded",
                "acceptFileTypes":"File type not allowed",
                "maxFileSize": "File is too big",
                "minFileSize": "File is too small"},
            process: true,
            start: function (e, data) {

                console.log("start");

                $('div.profile-photo-progress').removeClass("hidden");
                $('div.profile_img_wrap').addClass('hidden');

                $("#photo-upload").trigger('start');
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

                $('div.profile-photo-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
            },
            done: function (e, data) {

                console.log("done");

                var result = jQuery.parseJSON(data.jqXHR.responseText);

                if (result.hasOwnProperty('error')) {

                    if (result.error === false) {

                        if (result.hasOwnProperty('lowPhotoUrl')) {

                            $("img.profile-user-photo").attr("src", result.lowPhotoUrl);
                            $("span.avatar").css("background-image", "url(" + result.lowPhotoUrl + ")");
                        }

                    } else {

                        $infobox.find('#info-box-message').text(result.error_description);
                        $infobox.modal('show');
                    }
                }

                $("#photo-upload").trigger('done');
            },
            fail: function (e, data) {

                console.log(data.errorThrown);
            },
            always: function (e, data) {

                console.log("always");

                $('div.profile-photo-progress').addClass("hidden");
                $('div.profile_img_wrap').removeClass('hidden');

                $("#photo-upload").trigger('always');
            }
        });
    </script>

</body
</html>