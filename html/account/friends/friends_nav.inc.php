<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/account/friends" class="ls-menu-item <?php if ($page_id === 'friends') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-friends-all']; ?></span></a>
            <a href="/account/friends/online" class="ls-menu-item <?php if ($page_id === 'friends_online') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-friends-online']; ?></span></a>
            <a href="/account/friends/inbox" class="ls-menu-item <?php if ($page_id === 'friends_inbox_requests') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-friends-inbox-requests']; ?></span></a>
            <a href="/account/friends/outbox" class="ls-menu-item <?php if ($page_id === 'friends_outbox_requests') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['tab-friends-outbox-requests']; ?></span></a>
        </div>

    </div>
</div>