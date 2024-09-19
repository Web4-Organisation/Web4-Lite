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

    if (!auth::isSession() && !WEB_EXPLORE) {

        header('Location: /');
        exit;
    }

    $query = '';

    $market = new market($dbo);
    $market->setRequestFrom(auth::getCurrentUserId());

    $items_all = 0;
    $items_loaded = 0;

    if (isset($_GET['query'])) {

        $query = isset($_GET['query']) ? $_GET['query'] : '';

        $query = helper::clearText($query);
        $query = helper::escapeText($query);
    }

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;
        $query = isset($_POST['query']) ? $_POST['query'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);

        $result = $market->query($query, $itemId);

        $items_loaded = count($result['items']);
        $items_all = $result['itemCount'];


        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::marketItemPreview($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Search.marketMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search_market";

    $css_files = array();
    $page_title = $LANG['page-market']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="page-search-groups">


    <?php
        include_once("../html/common/topbar.inc.php");
    ?>


    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("../html/common/sidenav.inc.php");
            ?>

            <?php

                include_once("../html/search/search_nav.inc.php");
            ?>

            <div class="row col-lg-7 col-md-12 sn-content" id="content">

                <div class="main-content">

                    <div class="card">

                        <div class="standard-page page-title-content shadow-none">
                            <div class="page-title-content-inner">
                                <?php echo $LANG['page-market']; ?>
                            </div>
                            <div class="page-title-content-bottom-inner">
                                <?php echo $LANG['page-market-sub-title']; ?>
                            </div>
                        </div>

                        <div class="standard-page search-box ">
                            <form class="search-container" method="get" action="/search/market">
                                <div class="search-editbox-line">
                                    <input class="search-field" name="query" id="query" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" autocomplete="off" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">
                                    <button type="submit" class="button primary"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php

                        if (auth::isSession()) {

                            ?>
                                <div class="card border-0">
                                    <div class="card-header row mx-0 d-flex">
                                        <div class="col-12 col-sm-9 col-md-9 col-lg-9 p-0">
                                            <div class="upgrades-feature-container">
                                                <h3 class="card-title">
                                                    <?php echo $LANG['market-new-item-promo-title']; ?>
                                                </h3>
                                                <h5 class="card-description"><?php echo $LANG['market-new-item-promo-desc']; ?></h5>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 px-0 pt-2 pt-sm-0 text-center text-sm-right">

                                            <button type="button" data-toggle="modal" data-target="#newItemModal" class="action-button button green p-2"><i class="icofont icofont-plus"></i> <?php echo $LANG['market-new-item-button-title']; ?></button>

                                        </div>

                                    </div>
                                </div>
                            <?php
                        }
                    ?>

                    <div class="content-list-page">

                        <?php

                        if (strlen($query) > 0) {

                            $result = $market->query($query, 0);

                        } else {

                            $result = $market->query("");
                        }

                        $items_all = $result['itemCount'];
                        $items_loaded = count($result['items']);

                        if (strlen($query) > 0) {

                            ?>

                            <div class="card">

                                <header class="top-banner">

                                    <div class="info">
                                        <h1><?php echo $LANG['label-search-result']; ?> (<?php echo $items_all; ?>)</h1>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }

                        if ($items_loaded != 0) {

                            ?>

                            <div class="card cards-list extended-cards-list content-list">

                                <?php

                                foreach ($result['items'] as $key => $value) {

                                    draw::marketItemPreview($value, $LANG, $helper);
                                }

                                ?>
                            </div>

                            <?php
                        }
                        ?>

                        <?php

                        if ($items_all > 20) {

                            ?>

                            <header class="top-banner loading-banner">

                                <div class="prompt">
                                    <button onclick="Search.marketMore('<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                </div>

                            </header>

                            <?php
                        }
                        ?>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <div class="modal" id="newItemModal" tabindex="-1" role="dialog" aria-labelledby="newItemModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newItemModalTitle"><?php echo $LANG['market-new-item-dlg-title']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" class="new-market-item">
                        <input autocomplete="off" type="hidden" name="accessToken" value="<?php echo auth::getAccessToken(); ?>">
                        <input autocomplete="off" type="hidden" name="accountId" value="<?php echo auth::getCurrentUserId(); ?>">
                        <div class="form-group">
                            <label for="market-item-title" class="col-form-label"><?php echo $LANG['market-new-item-ad-title']; ?>:</label>
                            <input placeholder="<?php echo $LANG['market-new-item-ad-title-placeholder']; ?>" type="text" class="" id="market-item-title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="market-item-desc" class="col-form-label"><?php echo $LANG['market-new-item-ad-desc']; ?>:</label>
                            <textarea placeholder="<?php echo $LANG['market-new-item-ad-desc-placeholder']; ?>" style="min-height: 100px" class="" id="market-item-desc" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="market-item-price" class="col-form-label"><?php echo $LANG['market-new-item-ad-price']; ?>:</label>
                            <input type="number" size="8" value="0" class="" id="market-item-price" name="price">
                        </div>

                        <div class="form-group">

                            <div class="item-image-progress hidden">
                                <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="market-upload-button-container" style="height: 35px">
                                <div class="btn btn-secondary item-image-action-button item-add-image" style="right: auto; position: relative;">
                                    <input type="file" id="market-item-image-upload" name="uploaded_file" style="width: 100%">
                                    <i class="iconfont icofont-ui-image mr-1"></i>
                                    <?php echo $LANG['action-add-img']; ?>
                                </div>
                            </div>

                            <div class="img_container">

                                <div class="img-items-list-page">

                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button link" data-dismiss="modal"><?php echo $LANG['action-cancel']; ?></button>
                    <button onclick="Market.newItem()" type="button" class="button primary"><?php echo $LANG['action-post']; ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        var items_all = <?php echo $items_all; ?>;
        var items_loaded = <?php echo $items_loaded; ?>;
        var query = "<?php echo $query; ?>";

        $(document).ready(function() {

            $('#newItemModal').on('show.bs.modal', function (event) {

                var modal = $(this)
                modal.find('#market-item-desc').val('');
                modal.find('#market-item-title').val('');
                modal.find('#market-item-price').val(0);
                modal.find('div.img-items-list-page').html("");
                modal.find('div.market-upload-button-container').removeClass('hidden');
            })
        });

    </script>


</body
</html>
