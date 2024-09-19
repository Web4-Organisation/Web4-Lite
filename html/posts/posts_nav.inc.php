<div class="col sidebar-right-menu order-lg-1 px-0 px-sm-3 pl-lg-0 pr-lg-3">

    <div class="card">

        <div class="ls-menu">
            <a href="/<?php echo $postInfo['fromUserUsername']; ?>/post/<?php echo $postInfo['id']; ?>" class="ls-menu-item <?php if ($page_id === 'post') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-menu-post']; ?></span></a>
            <a href="/<?php echo $postInfo['fromUserUsername']; ?>/post/<?php echo $postInfo['id']; ?>/people" class="ls-menu-item <?php if ($page_id === 'people') echo 'ls-menu-item-selected'; ?>"><span><?php echo $LANG['label-menu-likes']; ?></a>
            <a href="/<?php echo $postInfo['fromUserUsername']; ?>" class="ls-menu-item"><span><?php echo sprintf($LANG['label-menu-profile'],  '<strong>'.$postInfo['fromUserFullname'].'</strong>');  ?></span></a>
        </div>

    </div>
</div>