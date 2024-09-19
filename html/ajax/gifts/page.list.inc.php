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

    if (!$auth::isSession()) {

        header('Location: /');
        exit;
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $balance = $account->getBalance();
    unset($account);

    $gifts = new gift($dbo);
    $gifts->setRequestFrom(auth::getCurrentUserId());

    $result = $gifts->db_get(0);

    ?>
    <div class="row" style="">
        <div class="gallery-intro-header col-12 p-3 py-4 m-0 shadow-none">

            <h1 class="gallery-title"><?php echo $LANG['label-you-balance']; ?>: <span class="account-balance" data-balance="<?php echo $balance; ?>"><?php echo $balance; ?></span> <?php echo $LANG['label-credits']; ?></h1>

            <a class="add-button button green" href="/account/settings/balance">
                <?php echo $LANG['action-buy']; ?>
            </a>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php

            foreach ($result['items'] as $key => $item) {

                ?>

                <div class="col-4 col-sm-4 col-md-4 col-lg-2 my-2 p-2 dlg-item" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['cost']; ?>">

                    <img src="<?php echo $item['imgUrl']; ?>" style="z-index: 2;">

                    <span class="item-price"><?php echo $item['cost']." ".$LANG['label-credits']; ?></span>

                </div>


                <?php
            }

            ?>
        </div>
    </div>
    <?php
