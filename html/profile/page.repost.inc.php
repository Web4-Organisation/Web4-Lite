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

$accountId = auth::getCurrentUserId();
$accessToken = auth::getAccessToken();

if (!$auth->authorize($accountId, $accessToken)) {

    exit;
}

$profile = new profile($dbo, $accountId);
$profileInfo = $profile->get();

$act = '';
$msg = '';

if (isset($_GET['action'])) {

    $act = isset($_GET['action']) ? $_GET['action'] : '';
    $postId = isset($_GET['postId']) ? $_GET['postId'] : 0;

    $postId = helper::clearInt($postId);

    $act = helper::clearText($act);

    ?>

        <div class="box-body">
            <div style="padding-left: 0px; text-align: left" class="prompt_header"><?php echo $LANG['label-share-on-wall-desc']; ?></div>
            <form onsubmit="Post.rePost('<?php echo $profileInfo['username']; ?>'); return false;" class="repost_form" action="/<?php echo $profileInfo['username']; ?>/post" method="post">
                <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">
                <input autocomplete="off" type="hidden" name="postImg" value="">
                <input autocomplete="off" type="hidden" name="rePostId" value="<?php echo $postId; ?>">
                <textarea style="width: 430px;resize: none;height: 100px;margin-bottom: 10px;" name="postText" maxlength="400" placeholder="<?php echo $LANG['label-share-add-comment']; ?>"></textarea>
                <div class="choice" style="height: 45px;">
                    <button class="primary_btn button blue" value="repost"><?php echo $LANG['action-share']; ?></button>
                </div>
            </form>
        </div>

    <?php
}
