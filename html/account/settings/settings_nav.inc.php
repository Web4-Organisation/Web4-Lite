<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/account/settings/profile" class="ls-menu-item <?php if ($page_id === 'settings_profile') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-profile-settings']; ?></span></a>
            <a href="/account/settings/privacy" class="ls-menu-item <?php if ($page_id === 'settings_privacy') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-privacy']; ?></span></a>
            <a href="/account/settings/services" class="ls-menu-item <?php if ($page_id === 'settings_services') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-services']; ?></span></a>
            <a href="/account/settings/otp" class="ls-menu-item <?php if ($page_id === 'settings_otp') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-otp']; ?></span></a>
            <a href="/account/settings/password" class="ls-menu-item <?php if ($page_id === 'settings_password') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-password']; ?></span></a>
            <a href="/account/settings/balance" class="ls-menu-item <?php if ($page_id === 'settings_balance') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-balance']; ?></span></a>
            <a href="/account/settings/referrals" class="ls-menu-item <?php if ($page_id === 'referrals') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-referrals']; ?></span></a>
            <a href="/account/settings/recently_deleted" class="ls-menu-item <?php if ($page_id === 'recently_deleted') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-menu-recently-deleted']; ?></span></a>
            <a href="/account/settings/blacklist" class="ls-menu-item <?php if ($page_id === 'settings_blacklist') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-blacklist']; ?></span></a>
            <a href="/account/settings/deactivation" class="ls-menu-item <?php if ($page_id === 'settings_deactivation') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['action-deactivation-profile']; ?></span></a>
        </div>

    </div>
</div>