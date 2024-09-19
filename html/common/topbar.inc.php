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


    if (auth::isSession()) {

        ?>

        <div id="backdrop" class="sn-backdrop" style="opacity: 0;"></div>

        <div id="sidenav" class="sn-sidenav" style="transform: translate3d(-380px, 0px, 0px);">

            <div class="top-header" id="sn-topbar">
                <div class="container">

                    <div class="d-flex">

                        <div class="burger-icon d-block menu-toggle">
                            <div class="burger-container">
                                <span class="burger-bun-top"></span>
                                <span class="burger-filling"></span>
                                <span class="burger-bun-bot"></span>
                            </div>
                        </div>

                        <a class="logo" href="/">
                            <span class="header-brand-img"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sidenav-content">

            </div>

        </div>

        <div class="top-header">
			<div class="container">

				<div class="d-flex">

                    <div class="burger-icon d-block d-lg-none menu-toggle">
                        <div class="burger-container">
                            <span class="burger-bun-top"></span>
                            <span class="burger-filling"></span>
                            <span class="burger-bun-bot"></span>
                        </div>
                    </div>

                    <a class="logo" href="/">
                        <span class="header-brand-img"></span>
                    </a>

                    <?php

                        if (defined("TOP_HEADER_LINK_HREF")) {

                            if (strlen(TOP_HEADER_LINK_HREF) != 0) {

                                ?>
                                    <a class="header-brand-title" href="<?php echo TOP_HEADER_LINK_HREF; ?>" title="<?php echo TOP_HEADER_LINK_TITLE; ?>" target="<?php if (TOP_HEADER_LINK_NEW_WINDOW) echo "_blank"; ?>"><?php echo TOP_HEADER_LINK_TEXT; ?></a>
                                <?php
                            }
                        }
                    ?>

                    <?php

                        if (isset($page_id) && $page_id !== "search" && $page_id !== "search_groups" && $page_id !== "search_hashtags" && $page_id !== "search_market") {

                            ?>
                            <form class="navbar-form navbar-left d-none d-md-block" action="/search/name">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="<?php echo $LANG['nav-search']; ?>" name="query">
                                </div>

                            </form>
                            <?php
                        }
                    ?>


							<div class="d-flex align-items-center order-lg-2 ml-auto">

                                <a class="nav-link py-2 icon" href="/account/notifications">
                                    <i class="icofont icofont-notification"></i>
                                    <span class="nav-unread hidden notifications-badge"></span>
                                </a>

								<div class="dropdown">
									<a href="/<?php echo auth::getCurrentUserUsername(); ?>" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                        <span class="avatar" style="background-image: url(<?php echo auth::getCurrentUserPhotoUrl(); ?>); background-position: center; background-size: cover;"></span>
										<span class="ml-2 d-none d-lg-block profile-menu-nav-link">
											<span class="text-default"><?php echo auth::getCurrentUserFullname(); ?></span>
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

										<a class="dropdown-item" href="/<?php echo auth::getCurrentUserLogin(); ?>"><i class="dropdown-icon icofont icofont-ui-user"></i><?php echo $LANG['nav-profile']; ?></a>

										<a class="dropdown-item d-block d-md-none" href="/account/notifications">
											<span class="float-right">
												<span class="badge badge-primary notifications-badge notifications-primary-badge"></span>
											</span>
											<i class="dropdown-icon icofont icofont-notification"></i><?php echo $LANG['nav-notifications']; ?>
                                        </a>
                                        <a class="dropdown-item d-block d-md-none" href="/search/name"><i class="dropdown-icon icofont icofont-ui-search"></i><?php echo $LANG['nav-search']; ?></a>
										<a class="dropdown-item" href="/account/settings/profile"><i class="dropdown-icon icofont icofont-gear-alt"></i><?php echo $LANG['nav-settings']; ?></a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="/support"><i class="dropdown-icon icofont icofont-support"></i><?php echo $LANG['topbar-support']; ?></a>
										<a class="dropdown-item" href="/logout?access_token=<?php echo auth::getAccessToken(); ?>&amp;continue=/"><i class="dropdown-icon icofont icofont-logout"></i><?php echo $LANG['topbar-logout']; ?></a>
									</div>
								</div>

							</div>

                </div>
			</div>
		</div>

        <?php

    } else {

        ?>

        <div id="backdrop" class="sn-backdrop" style="opacity: 0;"></div>

        <div id="sidenav" class="sn-sidenav" style="transform: translate3d(-380px, 0px, 0px);">

            <div class="top-header" id="sn-topbar">
                <div class="container">

                    <div class="d-flex">

                        <div class="burger-icon d-block menu-toggle">
                            <div class="burger-container">
                                <span class="burger-bun-top"></span>
                                <span class="burger-filling"></span>
                                <span class="burger-bun-bot"></span>
                            </div>
                        </div>

                        <a class="logo" href="/">
                            <span class="header-brand-img"></span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="sidenav-content">

            </div>

        </div>

        <div class="top-header not-authorized">
            <div class="container">

                <div class="d-flex">

                    <div class="burger-icon d-block d-lg-none menu-toggle hidden">
                        <div class="burger-container">
                            <span class="burger-bun-top"></span>
                            <span class="burger-filling"></span>
                            <span class="burger-bun-bot"></span>
                        </div>
                    </div>

                    <a class="logo" href="/">
                        <span class="header-brand-img"></span>
                    </a>


                    <div class="d-flex align-items-center order-lg-2 ml-auto">

                        <?php

                        if (isset($page_id) && ($page_id === "main" || $page_id === "signup" || $page_id == "")) {

                            ?>
                                <div class="theme-block">
                                    <div class="nav-item">
                                        <span class="topbar-button" title="">
                                            <span class="new-item d-sm-inline-block"><?php echo $LANG['label-day-night-theme']; ?></span>
                                        </span>
                                        <label class="switch">
                                        <input id="switch-theme-button" name="switch-theme-button" type="checkbox">
                                        <span class="sw-slider round"></span>
                                    </label>
                                    </div>
                                </div>
                            <?php
                        }

                        if (isset($page_id) && $page_id === "main") {

                            ?>

                            <div class="nav-item">
                                <a href="/signup" class="topbar-button" title="">
                                    <span class="new-item d-sm-inline-block"><?php echo $LANG['topbar-signup']; ?></span>
                                </a>
                            </div>

                            <?php

                        } else if (isset($page_id) && $page_id === "signup") {

                            ?>

                            <div class="nav-item">
                                <a href="/" class="topbar-button" title="">
                                    <span class="new-item d-sm-inline-block"><?php echo $LANG['topbar-signin']; ?></span>
                                </a>
                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="nav-item p-0">
                                <a href="/" class="topbar-button" title="">
                                    <span class="new-item d-sm-inline-block"><?php echo $LANG['topbar-signin']; ?></span>
                                </a>
                            </div>

                            <div class="nav-item p-0">
                                <a href="/signup" class="topbar-button" title="">
                                    <span class="new-item d-sm-inline-block"><?php echo $LANG['topbar-signup']; ?></span>
                                </a>
                            </div>

                            <?php
                        }
                        ?>

                        <?php

                            if (WEB_EXPLORE && isset($page_id) && $page_id !== 'explore') {

                                ?>
                                    <div class="nav-item p-0 d-none d-md-block">
                                        <a href="/explore" class="topbar-button" title="">
                                            <span class="new-item d-sm-inline-block"><?php echo $LANG['action-explore']; ?></span>
                                        </a>
                                    </div>
                                <?php
                            }
                        ?>

                    </div>

                </div>
            </div>
        </div>

        <?php
    }

    if (!isset($_COOKIE['privacy'])) {

        if (isset($page_id) && $page_id != 'main1') {

            ?>
            <div class="header-message gone">
                <div class="wrap">
                    <p class="message"><?php echo $LANG['label-cookie-message']; ?> <a href="/terms"><?php echo $LANG['page-terms']; ?></a></p>
                </div>

                <button class="close-message-button close-privacy-message">×</button>
            </div>
            <?php
        }
    }
?>