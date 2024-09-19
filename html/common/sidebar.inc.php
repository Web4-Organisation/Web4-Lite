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

<div class="col sidebar-block pl-lg-0 pr-lg-3" style="">

    <?php

        if (auth::getCurrentUserId() != 0) {

            ?>

            <div class="card preview-block" id="preview-people-block">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="icofont icofont-ui-user mr-2"></i><span class="counter-button-title"><?php echo $LANG['tab-search-users']; ?></span></h3>
                    <span class="action-link">
                        <a href="/search/name"><?php echo $LANG['action-show-all']; ?></a>
                    </span>
                </div>

                <div class="card-body p-2">
                    <div class="grid-list row">

                        <?php

                        $search = new search($dbo);

                        $result = $search->preload(0, -1, -1, 1, 6);

                        foreach ($result['items'] as $key => $value) {

                            draw::previewPeopleItem($value, $LANG, $helper);
                        }

                        unset($search);
                        ?>

                    </div>
                </div>
            </div>

            <?php

                if (isset($page_id) && $page_id != "search_groups" && $page_id != "my_groups" && $page_id != "managed_groups") {

                    ?>
                        <div class="card preview-block" id="preview-groups-block">
                            <div class="card-header border-0">
                                <h3 class="card-title"><i class="icofont icofont-group mr-2"></i><span class="counter-button-title"><?php echo $LANG['page-groups']; ?></span></h3>
                                <span class="action-link">
                                    <a href="/search/groups"><?php echo $LANG['action-show-all']; ?></a>
                                </span>
                            </div>

                            <div class="card-body p-2">
                                <div class="grid-list row">

                                    <?php

                                    $search = new search($dbo);

                                    $result = $search->communitiesPreload(0, 4);

                                    foreach ($result['items'] as $key => $value) {

                                        draw::communityItemPreview($value, $LANG, $helper);
                                    }

                                    unset($search);
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>

            <?php

                if (auth::getCurrentUserId() == 0 || auth::getCurrentUserAdmobFeature() == 0) {

                    $f_adsense_vertical_block = "../html/common/adsense_vertical.inc.php";

                    if (file_exists($f_adsense_vertical_block)) {

                        ?>
                        <div class="card ad-block border-0 shadow-none" id="ad-block" style="background: transparent">

                            <div class="card-header p-0 border-0">

                                <?php
                                    require_once($f_adsense_vertical_block);
                                ?>

                            </div>

                        </div>
                        <?php

                    }
                }
            ?>

            <?php

        } else {

            ?>

            <div class="card preview-block" id="preview-people-block">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="icofont icofont-ui-user mr-2"></i><span class="counter-button-title"><?php echo $LANG['tab-search-users']; ?></span></h3>
                    <span class="action-link">
                        <a href="/search/name"><?php echo $LANG['action-show-all']; ?></a>
                    </span>
                </div>

                <div class="card-body p-2">
                    <div class="grid-list row">

                        <?php

                        $search = new search($dbo);

                        $result = $search->preload(0, -1, -1, 1, 6);

                        foreach ($result['items'] as $key => $value) {

                            draw::previewPeopleItem($value, $LANG, $helper);
                        }

                        unset($search);
                        ?>

                    </div>
                </div>
            </div>

            <div class="card preview-block border-0 shadow-none" id="ad-block" style="background: transparent">

                <div class="card-header p-0 border-0">

                    <?php

                        if (auth::getCurrentUserId() == 0 || auth::getCurrentUserAdmobFeature() == 0) {

                            $f_adsense_vertical_block = "../html/common/adsense_vertical.inc.php";

                            if (file_exists($f_adsense_vertical_block)) {

                                ?>
                                <div class="card ad-block border-0 shadow-none" id="ad-block"
                                     style="background: transparent">

                                    <div class="card-header p-0 border-0">

                                        <?php
                                        require_once($f_adsense_vertical_block);
                                        ?>

                                    </div>

                                </div>
                                <?php

                            }
                        }
                    ?>

                </div>

            </div>


            <?php
        }
    ?>

</div>