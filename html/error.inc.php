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

    $page_id = "error";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-error-404']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="remind-page has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">

        <div class="main-column">

            <div class="main-content page-title-content">

                <header class="top-banner">

                    <div class="info">
                        <h1><?php echo $LANG['label-error-404']; ?></h1>
                    </div>

                    <div class="prompt">
                        <a href="/" class="post-job-button button green">
                            <?php echo $LANG['action-back-to-main-page']; ?>
                        </a>
                    </div>

                </header>

            </div>
        </div>

    </div>

    <?php

    include_once("../html/common/footer.inc.php");
    ?>


</body
</html>