<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/<?php echo $profileInfo['username']; ?>" class="ls-menu-item"><span><?php echo sprintf($LANG['label-menu-profile'],  '<strong>'.$profileInfo['fullname'].'</strong>');  ?></span></a>
            <a href="/<?php echo $profileInfo['username']; ?>/friends" class="ls-menu-item <?php if ($page_id === 'friends') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-friends']; ?></span></a>
            <a href="/<?php echo $profileInfo['username']; ?>/gallery" class="ls-menu-item <?php if ($page_id === 'gallery') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-gallery']; ?></span></a>
            <a href="/<?php echo $profileInfo['username']; ?>/gifts" class="ls-menu-item <?php if ($page_id === 'gifts') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['page-gifts']; ?></span></a>
        </div>

    </div>
</div>