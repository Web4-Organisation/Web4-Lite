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

    $messages = new messages($dbo);
    $messages->setRequestFrom(auth::getCurrentUserId());

    $account = new account($dbo, auth::getCurrentUserId());
    $account->setLastActive();
    unset($account);

    $chats_all = $messages->myActiveChatsCount();
    $chats_loaded = 0;

    if (!empty($_POST)) {

        $messageCreateAt = isset($_POST['messageCreateAt']) ? $_POST['messageCreateAt'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $messageCreateAt = helper::clearInt($messageCreateAt);
        $loaded = helper::clearInt($loaded);

        $result = $messages->getChats($messageCreateAt);

        $chats_loaded = count($result['chats']);

        $result['chats_loaded'] = $chats_loaded + $loaded;
        $result['chats_all'] = $chats_all;

        if ($chats_loaded != 0) {

            ob_start();

            foreach ($result['chats'] as $key => $value) {

                draw($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['chats_loaded'] < $chats_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Chats.more('<?php echo $result['messageCreateAt']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "messages";

    $css_files = array();
    $page_title = $LANG['page-messages']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-messages">


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

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title"><?php echo $LANG['page-messages']; ?></h3>
                            <h5 class="card-description"><?php echo $LANG['label-messages-sub-title']; ?></h5>
                        </div>
                    </div>

                    <div class="content-list-page">

                        <?php

                        $result = $messages->getChats(0);

                        $chats_loaded = count($result['chats']);

                        if ($chats_loaded != 0) {

                            ?>

                            <div class="card cards-list extended-cards-list content-list">

                                <?php

                                foreach ($result['chats'] as $key => $value) {

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

                        if ($chats_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Chats.more('<?php echo $result['messageCreateAt']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
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

        var chats_all = <?php echo $chats_all; ?>;
        var chats_loaded = <?php echo $chats_loaded; ?>;

    </script>


</body
</html>

<?php

    function draw($chat, $LANG, $helper)
    {

        $profilePhotoUrl = "/img/profile_default_photo.png";

        if (strlen($chat['withUserPhotoUrl']) != 0) {

            $profilePhotoUrl = $chat['withUserPhotoUrl'];
        }

        $time = new language(NULL, $LANG['lang-code']);

        ?>

        <li class="card-item classic-item default-item extended-list-item chat-item" data-id="<?php echo $chat['id']; ?>">
            <div class="card-body">
                    <span class="card-header px-0 pt-0 border-0">
                        <a href="/<?php echo $chat['withUserUsername']; ?>">
                            <img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/>
                        </a>

                        <?php if ($chat['withUserOnline']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>

                        <?php

                        if ($chat['newMessagesCount'] != 0) {

                            $count = $chat['newMessagesCount'];

                            if ($chat['newMessagesCount'] > 9) {

                                $count = "9+";
                            }

                            ?>
                                <span title="" class="card-counter-icon noselect"><?php echo $count; ?></span>
                            <?php
                        }
                        ?>

                        <div class="card-content">
                            <span class="card-title">
                                <a href="/<?php echo $chat['withUserUsername']; ?>"><?php echo $chat['withUserFullname']; ?></a>
                                    <?php

                                    if ($chat['withUserVerify'] == 1) {

                                        ?>
                                            <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                        <?php
                                    }
                                    ?>
                            </span>

                            <span class="card-date"><i class="iconfont icofont-clock-time"></i> <?php echo $time->timeAgo($chat['lastMessageCreateAt']); ?></span>

                            <span class="card-subtitle">

                                <?php

                                if (strlen($chat['lastMessage']) == 0) {

                                    echo "Image";

                                } else {

                                    echo $chat['lastMessage'];
                                }
                                ?>

                            </span>

                        </div>
                        <span class="card-controls">
                            <a href="/account/chat/?chat_id=<?php echo $chat['id']; ?>&user_id=<?php echo $chat['withUserId']; ?>" class="button secondary"><?php echo $LANG['action-go']; ?></a>
                            <a href="javascript:void(0)" onclick="Messages.removeChat('<?php echo $chat['id']; ?>', '<?php echo $chat['withUserId']; ?>', '<?php echo auth::getAccessToken(); ?>'); return false;" class="button link"><?php echo $LANG['action-remove']; ?></a>
                        </span>
                    </span>
            </div>
        </li>

        <?php
    }

?>