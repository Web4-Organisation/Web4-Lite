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

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $reactionId = isset($_POST['reactionId']) ? $_POST['reactionId'] : 0;
        $reaction = isset($_POST['reaction']) ? $_POST['reaction'] : 100;

        $itemId = helper::clearInt($itemId);
        $reactionId = helper::clearInt($reactionId);
        $reaction = helper::clearInt($reaction);

        $reactions = new reactions($dbo);
        $reactions->setRequestFrom(auth::getCurrentUserId());
        $result = $reactions->get($itemId, $reactionId, $reaction);
        unset($reactions);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                $profilePhotoUrl = "/img/profile_default_photo.png";

                if (strlen($value['lowPhotoUrl']) != 0) {

                    $profilePhotoUrl = $value['lowPhotoUrl'];
                }

                ?>
                    <li class="card-item classic-item default-item extended-list-item" data-id="<?php echo $value['id']; ?>">
                        <div class="card-body">
                                <span class="card-header px-0 pt-0 border-0">
                                    <a href="/<?php echo $value['username']; ?>"><img class="card-icon" src="<?php echo $profilePhotoUrl; ?>"/></a>

                                    <?php

                                    echo "<span title=\"\" class=\"card-notify-icon reaction-{$value['reaction']}\"></span>";
                                    ?>

                                    <?php if ($value['online']) echo "<span title=\"Online\" class=\"card-online-icon\"></span>"; ?>
                                    <div class="card-content">
                                        <span class="card-title">
                                            <a href="/<?php echo $value['username']; ?>"><?php echo  $value['fullname']; ?></a>
                                            <?php

                                                if ($value['verified'] == 1) {

                                                    ?>
                                                    <span class="user-badge user-verified-badge ml-1" rel="tooltip" title="<?php echo $LANG['label-account-verified']; ?>"><i class="iconfont icofont-check-alt"></i></span>
                                                    <?php
                                                }
                                            ?>
                                        </span>
                                        <span class="card-username">@<?php echo  $value['username']; ?></span>
                                    </div>
                                    <span class="card-controls">
                                        <a href="/<?php echo $value['username']; ?>" class="button secondary"><?php echo $LANG['action-go']; ?></a>
                                    </span>
                                </span>
                        </div>
                    </li>
                <?php
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] >= 20) {

                ob_start();

                ?>

                <header class="more-card top-banner loading-banner border-0 reactions-more-card">

                    <div class="prompt">
                        <button onclick="Reactions.more('<?php echo $itemId; ?>', '<?php echo $result['reactionId']; ?>', '<?php echo $reaction; ?>'); return false;" class="button more more-button reactions-more-button loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

