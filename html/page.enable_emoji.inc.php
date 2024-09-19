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

    $page_id = "emoji";

    include_once("../sys/core/initialize.inc.php");

    $update = new update($dbo);
    $update->setChatEmojiSupport();
    $update->setCommentsEmojiSupport();
    $update->setPostsEmojiSupport();

    $update->setGiftsEmojiSupport();

    $update->setDialogsEmojiSupport();

    $update->setGalleryEmojiSupport();
    $update->setGalleryCommentsEmojiSupport();

    $css_files = array();
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");
?>

<body class="remind-page">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <div class="alert alert-success mt-5">
                        <b>Success!</b>
                        <br>
                        Your MySQL version:
                        <?php

                        if (function_exists('mysql_get_client_info')) {

                            print mysql_get_client_info();

                        } else {

                            echo $dbo->query('select version()')->fetchColumn();
                        }
                        ?>
                        <br>
                        Database refactoring success!
                    </div>

                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

</body>
</html>