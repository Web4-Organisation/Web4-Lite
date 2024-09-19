<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card card-sidebar-menu">
        <div class="ls-menu">
            <a href="/search/name" class="ls-menu-item <?php if ($page_id === 'search') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-search-users']; ?></span></a>
            <a href="/search/groups" class="ls-menu-item <?php if ($page_id === 'search_groups') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-search-communities']; ?></span></a>
            <a href="/search/hashtag" class="ls-menu-item <?php if ($page_id === 'search_hashtags') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-search-hashtags']; ?></span></a>
            <a href="/search/market" class="ls-menu-item <?php if ($page_id === 'search_market') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-market']; ?></span></a>
        </div>
    </div>

    <?php

        if ($page_id === 'search') {

            ?>
            <div class="card preview-block card-search-filters">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="icofont icofont-ui-user mr-2"></i>
                        <span class="counter-button-title"><?php echo $LANG['label-search-filters']; ?></span>
                    </h3>
                </div>

                <div class="card-body p-2 ">
                    <div class="search-filters">

                        <div class="filter-container">
                            <h5><?php echo $LANG['search-filters-online']; ?></h5>
                            <div class="switch-container">
                                <label class="switch m-0">
                                    <input id="switch-online-button" type="checkbox" <?php if ($u_online != -1) echo "checked" ?>>
                                    <span class="sw-slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="filter-container">
                            <h5><?php echo $LANG['search-filters-photo-filter']; ?></h5>
                            <div class="switch-container">
                                <label class="switch m-0">
                                    <input id="switch-photo-button" type="checkbox" <?php if ($u_photo != -1) echo "checked" ?>>
                                    <span class="sw-slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="filter-container">
                            <h5><?php echo $LANG['search-filters-gender']; ?></h5>
                            <select id="gender" name="gender" class="col-6 col-sm-4 col-lg-12">
                                <option value="-1" <?php if ($u_gender != SEX_FEMALE && $u_gender != SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['search-filters-all']; ?></option>
                                <option value="1" <?php if ($u_gender == SEX_MALE) echo "selected=\"selected\""; ?>><?php echo $LANG['search-filters-male']; ?></option>
                                <option value="2" <?php if ($u_gender == SEX_FEMALE) echo "selected=\"selected\""; ?>><?php echo $LANG['search-filters-female']; ?></option>
                            </select>
                        </div>

                    </div>
                </div>

            </div>

            <?php
        }
    ?>


</div>