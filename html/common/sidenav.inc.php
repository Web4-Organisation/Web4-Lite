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

<div class="col sidebar-menu">

    <div class="sidebar-container">

        <div class="main-menu">

            <?php

            if (auth::getCurrentUserId() == 0) {

                ?>
                    <a class="button secondary d-block" href="/"><?php echo $LANG['topbar-signin']; ?></a>
                    <a class="button primary d-block mt-2" href="/signup"><?php echo $LANG['topbar-signup']; ?></a>

                    <div class="item-list transparent my-3">

                        <ul>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'explore') echo 'item-selected'; ?>">
                                <a href="/explore" class="custom-item-link" target="">
                                    <div class="item-counter hidden">
                                        <span class="counter"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-stream"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-explore']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'search') echo 'item-selected'; ?>">
                                <a href="/search/name" class="custom-item-link" target="">
                                    <div class="item-counter hidden search-badge">
                                        <span class="counter search-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-search"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-search']; ?></div>
                                </a>
                            </li>

                        </ul>

                    </div>
                <?php

            } else {

                ?>
                    <div class="item-list transparent mb-3">

                        <ul>

                            <li class="item-li item-li-main <?php if (isset($page_id) && $page_id === 'my-profile') echo 'item-selected'; ?>">
                                <div class="d-flex">
                                    <div class="custom-item-link-container">
                                        <a href="/<?php echo auth::getCurrentUserLogin(); ?>" class="custom-item-link" target="">
                                        <span class="item-icon">
                                            <span class="avatar profile-photo-avatar" alt="" draggable="false" style="background-image: url('<?php echo auth::getCurrentUserPhotoUrl(); ?>')"></span>
                                                <?php

                                                    if (auth::getCurrentUserVerified() != 0) {

                                                        ?>
                                                            <span class="user-badge user-verified-badge" rel="tooltip" title="Verified account"><i class="iconfont icofont-check-alt"></i></span>
                                                        <?php
                                                    }
                                                ?>
                                        </span>
                                            <div class="link-container">
                                                <div class="item-title"><?php echo auth::getCurrentUserFullname(); ?></div>
                                                <div class="item-sub-title">@<?php echo auth::getCurrentUserLogin(); ?></div>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="">
                                        <a href="/account/settings/profile" class="main-menu-action-button"><i class="icon icofont icofont-gear-alt"></i></a>
                                    </div>
                                </div>
                            </li>

                        </ul>

                    </div>

                    <div class="item-list transparent mb-2">

                        <ul>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'wall') echo 'item-selected'; ?>">
                                <a href="/account/wall" class="custom-item-link" target="">
                                    <div class="item-counter hidden">
                                        <span class="counter"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-newspaper"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-wall']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'favorites') echo 'item-selected'; ?>">
                                <a href="/account/favorites" class="custom-item-link" target="">
                                    <div class="item-counter hidden favorites-badge">
                                        <span class="counter favorites-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-heart"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-favorites']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'popular') echo 'item-selected'; ?>">
                                <a href="/account/popular" class="custom-item-link" target="">
                                    <div class="item-counter hidden popular-badge">
                                        <span class="counter popular-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-fire"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-popular']; ?></div>
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div class="separator"></div>

                    <div class="item-list transparent">

                        <ul>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'messages') echo 'item-selected'; ?>">
                                <a href="/account/messages" class="custom-item-link" target="">
                                    <div class="item-counter hidden messages-badge">
                                        <span class="counter messages-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-comments"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-messages']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'friends') echo 'item-selected'; ?>">
                                <a href="/account/friends" class="custom-item-link" target="">
                                    <div class="item-counter hidden friends-badge">
                                        <span class="counter friends-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-user-friends"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-friends']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'my_groups') echo 'item-selected'; ?>">
                                <a href="/account/groups" class="custom-item-link" target="">
                                    <div class="item-counter hidden groups-badge">
                                        <span class="counter groups-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-users"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-communities']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'my-gallery') echo 'item-selected'; ?>">
                                <a href="/account/gallery" class="custom-item-link" target="">
                                    <div class="item-counter hidden">
                                        <span class="counter"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-photo-video"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-gallery']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'guests') echo 'item-selected'; ?>">
                                <a href="/account/guests" class="custom-item-link" target="">
                                    <div class="item-counter hidden guests-badge">
                                        <span class="counter guests-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-shoe-prints"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-guests']; ?></div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="separator"></div>

                    <div class="item-list transparent">
                        <ul>
                            <li class="item-li <?php if (isset($page_id) && $page_id === 'upgrades') echo 'item-selected'; ?>">
                                <a href="/account/upgrades" class="custom-item-link" target="">
                                    <div class="item-counter hidden upgrades-badge">
                                        <span class="counter upgrades-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-star"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-upgrades']; ?></div>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="separator"></div>

                    <div class="item-list transparent">
                        <ul>
                            <li class="item-li <?php if (isset($page_id) && $page_id === 'search_market') echo 'item-selected'; ?>">
                                <a href="/search/market" class="custom-item-link" target="">
                                    <div class="item-counter hidden market-badge">
                                        <span class="counter market-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-shopping-cart"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-market']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'products') echo 'item-selected'; ?>">
                                <a href="/account/products" class="custom-item-link" target="">
                                    <div class="item-counter hidden products-badge">
                                        <span class="counter products-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-shopping-basket"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-products']; ?></div>
                                </a>
                            </li>
                        </ul>

                    </div>

                    <div class="separator"></div>

                    <div class="item-list transparent">

                        <ul>
                            <li class="item-li <?php if (isset($page_id) && $page_id === 'nearby') echo 'item-selected'; ?>">
                                <a href="/account/nearby" class="custom-item-link" target="">
                                    <div class="item-counter hidden nearby-badge">
                                        <span class="counter nearby-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-map-marker-alt"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-nearby']; ?></div>
                                </a>
                            </li>

                            <li class="item-li <?php if (isset($page_id) && $page_id === 'search') echo 'item-selected'; ?>">
                                <a href="/search/name" class="custom-item-link" target="">
                                    <div class="item-counter hidden search-badge">
                                        <span class="counter search-count"></span>
                                    </div>
                                    <span class="item-icon iconfont"><i class="icon fa fa-search"></i></span>
                                    <div class="item-title"><?php echo $LANG['page-search']; ?></div>
                                </a>
                            </li>
                        </ul>

                    </div>

                <?php
            }

            ?>

            <div class="d-block theme-block">

                <div class="separator"></div>

                <div class="item-list transparent">

                    <ul>
                        <li class="item-li mt-1">
                            <span class="custom-item-link">

                                <div class="item-counter">
                                    <label class="switch">
                                        <input id="switch-theme-button" name="switch-theme-button" type="checkbox"
                                            <?php
                                            if (isset($_COOKIE['theme-night'])) {

                                                if ($_COOKIE['theme-night'] === "true") {

                                                   echo "checked=\"checked\"";
                                                }
                                            }
                                            ?>
                                        >
                                        <span class="sw-slider round"></span>
                                    </label>
                                </div>

                                <div class="item-title noselect"><?php echo $LANG['label-day-night-theme']; ?></div>

                            </span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="footer-list">

                <a class="menu-nav" href="/about"><?php echo $LANG['footer-about']; ?></a>
                <a class="menu-nav" href="/terms"><?php echo $LANG['footer-terms']; ?></a>
                <a class="menu-nav" href="/privacy"><?php echo $LANG['footer-privacy']; ?></a>
                <a class="menu-nav" href="/gdpr"><?php echo $LANG['footer-gdpr']; ?></a>
                <a class="menu-nav" href="/support"><?php echo $LANG['footer-support']; ?></a>
                <a class="menu-nav lang_link" href="javascript:void(0)"  data-toggle="modal" data-target="#langModal"><i class="fa fa-globe"></i> <?php echo $LANG['lang-name']; ?></a>

                <span class="copyright" href="/support"><?php echo APP_TITLE; ?> © <?php echo APP_YEAR; ?></span>
            </div>

        </div>
    </div>

</div>