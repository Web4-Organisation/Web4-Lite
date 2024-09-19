<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/<?php echo $profileInfo['username']; ?>/image/<?php echo $itemId; ?>" class="ls-menu-item <?php if ($page_id === 'image') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-menu-item']; ?></span></a>
            <a href="/<?php echo $profileInfo['username']; ?>/image/<?php echo $itemId; ?>/people" class="ls-menu-item <?php if ($page_id === 'people') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-menu-likes']; ?></a>
            <a href="/<?php echo $profileInfo['username']; ?>" class="ls-menu-item"><span><?php echo sprintf($LANG['label-menu-profile'],  '<strong>'.$profileInfo['fullname'].'</strong>');  ?></span></a>
            <a href="/<?php echo $profileInfo['username']; ?>/gallery" class="ls-menu-item"><span><?php echo sprintf($LANG['label-menu-gallery'],  '<strong>'.$profileInfo['fullname'].'</strong>');  ?></span></a>
        </div>

    </div>
</div>