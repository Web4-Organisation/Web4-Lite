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

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
    }

    $showForm = true;

    $chat_id = 0;
    $user_id = 0;

    $chat_info = array("messages" => array());
    $user_info = array();
    $profile_info = array();

    $profile = new profile($dbo, auth::getCurrentUserId());
    $profile_info = $profile->get();

    $messages = new messages($dbo);
    $messages->setRequestFrom(auth::getCurrentUserId());

    if (!isset($_GET['chat_id']) && !isset($_GET['user_id'])) {

        header('Location: /');
        exit;

    } else {

        $chat_id = isset($_GET['chat_id']) ? $_GET['chat_id'] : 0;
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;

        $chat_id = helper::clearInt($chat_id);
        $user_id = helper::clearInt($user_id);

        $user = new profile($dbo, $user_id);
        $user->setRequestFrom(auth::getCurrentUserId());
        $user_info = $user->get();
        unset($user);

        if ($user_info['error'] === true) {

            header('Location: /');
            exit;
        }

        $chat_id_test = $messages->getChatId(auth::getCurrentUserId(), $user_id);

        if ($chat_id != 0 && $chat_id_test != $chat_id) {

            header('Location: /');
            exit;
        }

        if ($chat_id == 0) {

            $chat_id = $messages->getChatId(auth::getCurrentUserId(), $user_id);

            if ($chat_id != 0) {

                header('Location: /account/chat/?chat_id='.$chat_id.'&user_id='.$user_id);
                exit;
            }
        }

        if ($chat_id != 0) {

            $chat_info = $messages->get($chat_id, 0);
        }
    }

    if ($user_info['state'] != ACCOUNT_STATE_ENABLED) {

        $showForm = false;
    }

    if ($user_info['allowMessages'] == 0 && $user_info['friend'] === false) {

        $showForm = false;
    }

    $blacklist = new blacklist($dbo);
    $blacklist->setRequestFrom($user_info['id']);

    if ($blacklist->isExists(auth::getCurrentUserId())) {

        $showForm = false;
    }

    $items_all = $messages->messagesCountByChat($chat_id);
    $items_loaded = 0;

    $page_id = "chat";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-chat']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-chat has-bottom-footer">


	<?php
		include_once("../html/common/topbar.inc.php");
	?>


	<div class="wrap content-page">

		<div class="main-column">

            <div class="row">

                <div class="col-4 d-none d-lg-block">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['label-chats']; ?></h3>
                        </div>
                        <div class="card-body p-0">

                            <?php

                            $result = $messages->getChats(0);

                            $chats_loaded = count($result['chats']);

                            if ($chats_loaded != 0) {

                                ?>

                                <div class="cards-list chats-content-list">

                                    <?php

                                    foreach ($result['chats'] as $key => $value) {

                                        drawChatItem($value, $chat_id, $LANG, $helper);
                                    }
                                    ?>
                                </div>

                                <?php

                            }
                            ?>

                        </div>
                    </div>

                </div>


                <div class="col-12 col-lg-8">

                    <div class="card main-content" style="max-width: 100%">

                        <div class="card-header">
                            <h3 class="card-title"><?php echo $user_info['fullname']; ?></h3>
                        </div>

                        <div class="card-body standard-page p-2">

                        <div class="content-list-page">

                            <?php

                            if ($items_all > 20) {

                                ?>

                                <header class="top-banner loading-banner">

                                    <div class="prompt">
                                        <button onclick="Messages.more('<?php echo $chat_id ?>', '<?php echo $user_id ?>'); return false;" class="button more loading-button noselect"><?php echo $LANG['action-more']; ?></button>
                                    </div>

                                </header>

                                <?php
                            }

                            ?>

                            <div class="cards-list content-list">

                                <?php

                                $result = $chat_info;

                                $items_loaded = count($result['messages']);

                                if ($items_loaded != 0) {

                                    foreach (array_reverse($result['messages']) as $key => $value) {

                                        draw::messageItem($value, $LANG, $helper);
                                    }
                                }

                                ?>

                            </div>

                            <?php

                            if ($items_loaded == 0) {

                                ?>

                                <div class="warning-container mx-2 mb-4 mt-0 empty-list-banner">
                                    <b><?php echo $LANG['label-chat-empty']; ?></b>
                                    <br>
                                    <?php echo $LANG['label-chat-empty-promo']; ?>
                                </div>

                                <?php
                            }
                            ?>

                            <?php

                            if ($showForm) {

                                ?>

                                <div class="comment_form comment-form mx-2">

                                    <form class="" onsubmit="Messages.create('<?php echo $chat_id; ?>', '<?php echo $user_id; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;">

                                        <input type="hidden" name="message_image" value="">

                                        <div class="input-container">

                                            <input class="comment_text" name="message_text" maxlength="340" placeholder="<?php echo $LANG['label-placeholder-message']; ?>" type="text" value="">

                                            <button class="primary_btn blue comment_send mt-0" title="<?php echo $TEXT['action-send']; ?>"><i class="fa fa-paper-plane"></i></button>

                                        </div>

                                        <div style="" class="input-container-action-bar d-flex mt-2">

                                            <div class="dropdown emoji-dropdown dropup" style="">

                                                    <span class="smile-button btn-emoji-picker flat_btn mr-1" data-toggle="dropdown">
                                                        <i class="btn-emoji-picker-icon far fa-smile"></i>
                                                    </span>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <div class="emoji-items">
                                                        <div class="emoji-item">😀</div>
                                                        <div class="emoji-item">😁</div>
                                                        <div class="emoji-item">😂</div>
                                                        <div class="emoji-item">😃</div>
                                                        <div class="emoji-item">😄</div>
                                                        <div class="emoji-item">😅</div>
                                                        <div class="emoji-item">😆</div>
                                                        <div class="emoji-item">😉</div>
                                                        <div class="emoji-item">😊</div>
                                                        <div class="emoji-item">😋</div>
                                                        <div class="emoji-item">😎</div>
                                                        <div class="emoji-item">😍</div>
                                                        <div class="emoji-item">😘</div>
                                                        <div class="emoji-item">🤗</div>
                                                        <div class="emoji-item">🤩</div>
                                                        <div class="emoji-item">🤔</div>
                                                        <div class="emoji-item">🤨</div>
                                                        <div class="emoji-item">😐</div>
                                                        <div class="emoji-item">🙄</div>
                                                        <div class="emoji-item">😏</div>
                                                        <div class="emoji-item">😣</div>
                                                        <div class="emoji-item">😥</div>
                                                        <div class="emoji-item">😮</div>
                                                        <div class="emoji-item">🤐</div>
                                                        <div class="emoji-item">😯</div>
                                                        <div class="emoji-item">😪</div>
                                                        <div class="emoji-item">😫</div>
                                                        <div class="emoji-item">😴</div>
                                                        <div class="emoji-item">😌</div>
                                                        <div class="emoji-item">😜</div>
                                                        <div class="emoji-item">🤤</div>
                                                        <div class="emoji-item">😓</div>
                                                        <div class="emoji-item">😔</div>
                                                        <div class="emoji-item">🤑</div>
                                                        <div class="emoji-item">😲</div>
                                                        <div class="emoji-item">🙁</div>
                                                        <div class="emoji-item">😖</div>
                                                        <div class="emoji-item">😞</div>
                                                        <div class="emoji-item">😟</div>
                                                        <div class="emoji-item">😤</div>
                                                        <div class="emoji-item">😢</div>
                                                        <div class="emoji-item">😭</div>
                                                        <div class="emoji-item">😦</div>
                                                        <div class="emoji-item">😧</div>
                                                        <div class="emoji-item">😨</div>
                                                        <div class="emoji-item">😩</div>
                                                        <div class="emoji-item">😰</div>
                                                        <div class="emoji-item">😱</div>
                                                        <div class="emoji-item">😳</div>
                                                        <div class="emoji-item">🤪</div>
                                                        <div class="emoji-item">😵</div>
                                                        <div class="emoji-item">😡</div>
                                                        <div class="emoji-item">😠</div>
                                                        <div class="emoji-item">🤬</div>
                                                        <div class="emoji-item">😷</div>
                                                        <div class="emoji-item">🤒</div>
                                                        <div class="emoji-item">🤕</div>
                                                        <div class="emoji-item">🤢</div>
                                                        <div class="emoji-item">🤮</div>
                                                        <div class="emoji-item">🤧</div>
                                                        <div class="emoji-item">😇</div>
                                                        <div class="emoji-item">🤠</div>
                                                        <div class="emoji-item">🤡</div>
                                                        <div class="emoji-item">🤥</div>
                                                        <div class="emoji-item">🤫</div>
                                                        <div class="emoji-item">🤭</div>
                                                        <div class="emoji-item">🧐</div>
                                                        <div class="emoji-item">🤓</div>
                                                        <div class="emoji-item">😈</div>
                                                        <div class="emoji-item">👿</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dropdown emoji-dropdown dropup" style="">

                                                <span class="smile-button btn-sticker-picker flat_btn mr-1" data-toggle="dropdown">
                                                    <i class="far fa-sticky-note"></i>
                                                </span>

                                                <div class="dropdown-menu dropdown-menu-left">
                                                    <div class="sticker-items">

                                                        <?php

                                                            $stickers = new sticker($dbo);
                                                            $stickers->setRequestFrom(auth::getCurrentUserId());

                                                            $result = $stickers->db_get(0, 300);

                                                            foreach ($result['items'] as $item) {

                                                                ?>
                                                                    <div data-id="<?php echo $item['id']; ?>" data-img-url="<?php echo $item['imgUrl']; ?>" class="sticker-item" style="background-image: url('<?php echo $item['imgUrl']; ?>');"></div>
                                                                <?php
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="smile-button flat_btn image-upload-button mr-1">
                                                <input type="file" id="image-upload" name="uploaded_file">
                                                <i class="far fa-image"></i>
                                            </div>

                                        </div>

                                        <div class="mt-2">

                                            <div class="image-upload-progress hidden">
                                                <div style="height: 2px;" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>

                                        </div>

                                        <div class="image-upload-img my-2 hidden">
                                            <img style="width: 100%;" class="msg_img_preview" src="/img/camera.png">
                                            <span title="<?php echo $LANG['action-remove']; ?>" class="remove" onclick="removeUploadedImg(); return false;">×</span>
                                        </div>

                                    </form>

                                </div>

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

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

            App.chatInit('<?php echo $chat_id; ?>', '<?php echo $user_id; ?>', '<?php echo auth::getAccessToken(); ?>');

            $(document).ready(function() {

                $(document).on('click', '.sticker-item', function() {

                    Messages.sendSticker('<?php echo $chat_id; ?>', '<?php echo $user_id; ?>', $(this).attr('data-id'), $(this).attr('data-img-url'));

                    $(".btn-sticker-picker").dropdown('toggle');

                    return false;
                });
            });

            $("#image-upload").fileupload({
                formData: {accountId: <?php echo auth::getCurrentUserId(); ?>, accessToken: "<?php echo auth::getAccessToken(); ?>"},
                name: 'image',
                url: "/api/" + options.api_version + "/method/msg.uploadImg",
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

                    $('div.image-upload-progress').removeClass("hidden");
                    $('div.image-upload-button').addClass('hidden');
                    $('div.image-upload-img').addClass('hidden');
                    $('button.comment_send').addClass("hidden");
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

                            $.colorbox.close();

                            if (result.hasOwnProperty('imgUrl')) {

                                $("input[name=message_image]").val(result.imgUrl);
                                $("img.msg_img_preview").attr("src", result.imgUrl);
                            }
                        }
                    }
                },
                fail: function (e, data) {

                    console.log("always");
                },
                always: function (e, data) {

                    console.log("always");

                    $('button.comment_send').removeClass("hidden");
                    $('div.image-upload-progress').addClass("hidden");
                    $('div.image-upload-img').removeClass('hidden');
                }

            });

            function removeUploadedImg() {

                $('div.image-upload-progress').addClass("hidden");
                $('div.image-upload-img').addClass('hidden');
                $('div.image-upload-button').removeClass('hidden');
                $("input[name=message_image]").val("");
            }

        </script>


</body
</html>

<?php

function drawChatItem($item, $current_chat_id, $LANG, $helper)
{
    $time = new language(NULL, $LANG['lang-code']);
    $profilePhotoUrl = "/img/profile_default_photo.png";

    if (strlen($item['withUserPhotoUrl']) != 0) {

        $profilePhotoUrl = $item['withUserPhotoUrl'];
    }

    ?>

    <li class="card-item classic-item default-item" data-id="<?php echo $item['id']; ?>" >
        <a class="touch-item d-block <?php if ($current_chat_id == $item['id']) echo "active"; ?>" href="/account/chat/?chat_id=<?php echo $item['id']; ?>&user_id=<?php echo $item['withUserId']; ?>">
            <div class="card-body p-2 bg-transparent">
                <span class="card-header p-0 border-0">
                    <span>
                        <img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/>
                    </span>

                    <?php if ($item['withUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                    <div class="card-content">
                        <div class="card-title">
                            <span><?php echo $item['withUserFullname']; ?></span>

                            <?php

                                if ($item['withUserVerify'] == 1) {

                                    ?>
                                        <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                    <?php
                                }
                            ?>
                        </div>
                        <span class="card-status-text">

                            <?php

                                if (strlen($item['lastMessage']) == 0) {

                                    echo "Image";

                                } else {

                                    echo $item['lastMessage'];
                                }
                            ?>

                            <?php

                                if ($item['newMessagesCount'] != 0) {

                                    ?>
                                        <span class="card-counter red"><?php echo $item['newMessagesCount']; ?></span>
                                    <?php
                                }
                            ?>

                        </span>
                    </div>
                </span>
            </div>
        </a>
    </li>

    <?php
}