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

    if (isset($_GET['action'])) {

        $act = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($act) {

            case "get-box": {

                ?>

                    <div class="box-body">
                        <div class="msg" style="margin-top: 0">
                            <?php echo $LANG['label-image-upload-description']; ?>
                        </div>

                        <div class="file_loader_block" style=""></div>

                        <div class="file_select_block" style="">
                            <div style="" class="file_select_btn cover_input button green"><?php echo $LANG['action-select-file-and-upload']; ?></div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="controls">
                            <button onclick="$.colorbox.close(); return false;" class="primary_btn blue"><?php echo $LANG['action-cancel']; ?></button>
                        </div>
                    </div>

                <?php

                exit;
            }

            default: {

                break;
            }
        }
    }

