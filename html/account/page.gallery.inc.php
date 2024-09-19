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

    $gallery = new gallery($dbo);
    $gallery->setRequestFrom(auth::getCurrentUserId());

    $items_all = $gallery->count();
    $items_loaded = 0;

    auth::newAuthenticityToken();

    $page_id = "my-gallery";

    $css_files = array();
    $page_title = $LANG['page-gallery']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="gallery-listings page-gallery">

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

                    <div class="gallery-intro-header">
                        <h1 class="gallery-title"><?php echo $LANG['page-gallery']; ?></h1>
                        <p class="gallery-sub-title"><?php echo $LANG['label-gallery-sub-title']; ?></p>

                        <button class="add-button button green item-add-image">
                            <input type="file" id="gallery-image-upload" name="uploaded_file">
                            <span><?php echo $LANG['action-change-image']; ?></span><?php echo $LANG['action-add-photo']; ?>
                        </button>

                        <div class="image-upload-progress hidden">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                    </div>

                    <div class="content-block">

                        <?php

                        $result = $gallery->get(auth::getCurrentUserId(), -1, 0);

                        $items_loaded = count($result['items']);

                        if ($items_loaded != 0) {

                            ?>
                            <div class="grid-list">
                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    $profileInfo = array("username" => auth::getCurrentUserLogin());

                                    draw::galleryItem($value, $profileInfo, $LANG, $helper);
                                }

                                ?>
                            </div>
                            <?php

                            if ($items_all > 20) {

                                ?>

                                <header class="top-banner loading-banner p-0 pt-3">

                                    <div class="prompt">
                                        <button onclick="Gallery.more('<?php echo $profileInfo['username']; ?>', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }

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

            var auth_token = "<?php echo auth::getAuthenticityToken(); ?>";
            var username = "<?php echo auth::getCurrentUserLogin(); ?>";

            $("#gallery-image-upload").fileupload({
                formData: {accountId: account.id, accessToken: account.accessToken},
                name: 'image',
                url: "/api/" + options.api_version + "/method/gallery.uploadImg",
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

                    $('button.add-button').addClass("hidden");
                    $('div.image-upload-progress').removeClass("hidden");

                    $("#gallery-image-upload").trigger('start');
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

                    $('div.image-upload-progress').find('.progress-bar').attr('aria-valuenow', progress).css('width', progress + '%').text(progress + '%');
                },
                done: function (e, data) {

                    console.log("done");

                    var result = jQuery.parseJSON(data.jqXHR.responseText);

                    if (result.hasOwnProperty('error')) {

                        if (result.error === false) {

                            if (result.hasOwnProperty('originPhotoUrl')) {

                                Gallery.add(result.normalPhotoUrl, result.previewPhotoUrl, result.originPhotoUrl)
                            }

                        } else {

                            $infobox.find('#info-box-message').text(result.error_description);
                            $infobox.modal('show');
                        }
                    }

                    $("#gallery-image-upload").trigger('done');
                },
                fail: function (e, data) {

                    console.log(data.errorThrown);
                },
                always: function (e, data) {

                    console.log("always");

                    $('button.add-button').removeClass("hidden");
                    $('div.image-upload-progress').addClass("hidden");

                    $("#gallery-image-upload").trigger('always');
                }
            });

        </script>


</body
</html>
