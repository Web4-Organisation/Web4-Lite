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

    $query = "";

    if (isset($_GET['src'])) {

        $query = isset($_GET['src']) ? $_GET['src'] : '';

        $query = str_replace('#', '', $query);

        $query = helper::clearText($query);
        $query = helper::escapeText($query);
    }


    $hashtags = new hashtag($dbo);

    $hashtags->setRequestFrom(auth::getCurrentUserId());
    $hashtags->setLanguage($LANG['lang-code']);

    $inbox_all = $hashtags->count($query);
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $postId = isset($_POST['postId']) ? $_POST['postId'] : '';
        $hashtag = isset($_POST['hashtag']) ? $_POST['hashtag'] : '';
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $postId = helper::clearInt($postId);

        $hashtag = helper::clearText($hashtag);
        $hashtag = helper::escapeText($hashtag);

        $loaded = helper::clearInt($loaded);

        $result = $hashtags->search($hashtag, $postId);

        $inbox_loaded = count($result['posts']);

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0) {

            ob_start();

            foreach ($result['posts'] as $key => $value) {

                draw::post($value, $LANG, $helper);
            }

            $result['html'] = ob_get_clean();


            if ($result['inbox_loaded'] < $inbox_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner">

                    <div class="prompt">
                        <button onclick="Hashtags.more('<?php echo $result['postId']; ?>', '<?php echo $result['query']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['banner'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "search_hashtags";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-hashtags']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="cards-page">


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

            <div class="row col-lg-7 col-md-12 sn-content d-block" id="content">

                <div class="card">

                    <div class="standard-page page-title-content shadow-none">
                        <div class="page-title-content-inner">
                            <?php echo $LANG['tab-search-hashtags']; ?>
                        </div>
                        <div class="page-title-content-bottom-inner">
                            <?php echo $LANG['tab-search-hashtags-description']; ?>
                        </div>
                    </div>

                    <div class="standard-page search-box">


                        <form class="search-container" method="get" action="/search/hashtag">

                            <div class="search-editbox-line">

                                <input class="search-field" name="src" id="query" placeholder="<?php echo $LANG['search-editbox-placeholder']; ?>" autocomplete="off" type="text" autocorrect="off" autocapitalize="off" style="outline: none;" value="<?php echo $query; ?>">

                                <button type="submit" class="btn btn-main blue"><i class="fa fa-search mr-2"></i><?php echo $LANG['search-filters-action-search']; ?></button>
                            </div>
                        </form>

                    </div>

                </div>

                <div class="content-list-page ">

                    <?php

                    if (strlen($query) > 0) {

                        $result = $hashtags->search($query, 0);

                        $inbox_loaded = count($result['posts']);

                        if (strlen($query) > 0) {

                            ?>

                            <div class="card">

                                <header class="top-banner">

                                    <div class="info">
                                        <h1><?php echo $LANG['label-search-result']; ?> (<?php echo $inbox_all; ?>)</h1>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }

                        if ($inbox_loaded != 0) {

                            ?>

                            <div class="items-list content-list mx-0 border-0">

                                <?php

                                foreach ($result['posts'] as $key => $value) {

                                    draw::post($value, $LANG, $helper);
                                }
                                ?>
                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card">

                                <header class="top-banner">

                                    <div class="info">
                                        <h1><?php echo $LANG['label-search-empty']; ?></h1>
                                    </div>

                                </header>
                            </div>

                            <?php
                        }

                        if ($inbox_all > 20) {

                            ?>

                            <header class="top-banner loading-banner border-0">

                                <div class="prompt">
                                    <button onclick="Hashtags.more('<?php echo $result['postId']; ?>', '<?php echo $query; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                </div>

                            </header>

                            <?php
                        }

                    } else {

                        ?>

                        <div class="card">

                            <header class="top-banner">

                                <div class="info">
                                    <h1><?php echo $LANG['label-search-hashtag-prompt']; ?></h1>
                                </div>

                            </header>
                        </div>

                        <?php
                    }
                    ?>


                </div>

            </div>

        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script type="text/javascript">

        var inbox_all = <?php echo $inbox_all; ?>;
        var inbox_loaded = <?php echo $inbox_loaded; ?>;
        var query = "<?php echo $query; ?>";

    </script>


</body
</html>