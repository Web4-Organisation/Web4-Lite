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

?>

<?php

    $f_adsense_wide_block = "../html/common/adsense_wide.inc.php";
    $f_adsense_square_block = "../html/common/adsense_square.inc.php";

    if (file_exists($f_adsense_wide_block)) {

        if (isset($page_id)) {

            if (auth::getCurrentUserId() == 0 || auth::getCurrentUserAdmobFeature() == 0) {

                ?>
                    <div class="card ad-block border-0 shadow-none" id="ad-block" style="background: transparent">

                        <div class="card-header p-0 border-0">

                            <?php

                                require_once($f_adsense_wide_block);

                            ?>
                        </div>
                    </div>
                <?php
            }
        }
    }