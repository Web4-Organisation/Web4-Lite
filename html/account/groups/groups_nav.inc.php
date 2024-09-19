<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/account/groups" class="ls-menu-item <?php if ($page_id === 'my_groups') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-communities']; ?></span></a>
            <a href="/account/groups/managed" class="ls-menu-item <?php if ($page_id === 'managed_groups') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-managed-communities']; ?></a>
            <a href="/search/groups" class="ls-menu-item <?php if ($page_id === 'search_groups') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-search-communities']; ?></span></a>
        </div>

    </div>
</div>