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


    $page_id = "update";

    include_once("../sys/core/initialize.inc.php");

    $update = new update($dbo);
    $update->addColumnToCommentsTable();
    $update->addColumnToUsersTable();
    $update->addColumnToPostsTable();
    $update->addColumnToPostsTable2();

    $update->addColumnToUsersTable2();

    $update->addColumnToPostsTable3();
    $update->addColumnToUsersTable3();
    $update->addColumnToUsersTable4();
    $update->addColumnToUsersTable5();
    $update->addColumnToUsersTable6();
    $update->addColumnToUsersTable7();
    $update->addColumnToUsersTable8();
    $update->addColumnToUsersTable9();
    $update->addColumnToUsersTable10();

    $update->addColumnToUsersTable11();

    $update->addColumnToUsersTable12();
    $update->addColumnToUsersTable14();

    $update->addColumnToPostsTable4();
    $update->addColumnToPostsTable5();
    $update->addColumnToPostsTable6();
    $update->addColumnToPostsTable7();

    $update->addColumnToChatsTable();
    $update->addColumnToChatsTable2();

    $update->addColumnToUsersTable15();

    $update->addColumnToUsersTable16();
    $update->addColumnToUsersTable17();

    $update->addColumnToUsersTable18();

    // for v3.4

    $update->addColumnToUsersTable19();
    $update->addColumnToUsersTable20();
    $update->addColumnToUsersTable21();
    $update->addColumnToUsersTable22();
    $update->addColumnToUsersTable23();
    $update->addColumnToUsersTable24();

    //$update->delete_all_followers_for_users();

    // for v3.5

//    $update->recalculate_friends_for_users();

//    $update->recalculate();

    // for v3.7

    $update->addColumnToUsersTable25();
    $update->addColumnToUsersTable26();
    $update->addColumnToUsersTable27();
    $update->addColumnToUsersTable28();

    // for v3.9

    $update->addColumnToMessagesTable1();
    $update->addColumnToMessagesTable2();

    //

    $update->addColumnToUsersTable29();
    $update->addColumnToUsersTable30();

    // for v4.1

    $update->addColumnToMessagesTable3();
    $update->addColumnToMessagesTable4();
    $update->addColumnToMessagesTable5();

    // for v4.3

    $update->addColumnToUsersTable31();
    $update->addColumnToUsersTable32();

    // for v4.5

    $update->addColumnToPostsTable8();
    $update->addColumnToPostsTable9();
    $update->addColumnToPostsTable10();
    $update->addColumnToPostsTable11();

    // for v4.6

    $update->addColumnToUsersTable33();
    $update->addColumnToPostsTable12();
    $update->addColumnToPostsTable14();

    // for v4.9

    $update->addColumnToUsersTable34();
    $update->addColumnToUsersTable35();

    $update->addColumnToUsersTable36();
    $update->addColumnToUsersTable37();
    $update->addColumnToUsersTable38();

    $update->addColumnToUsersTable39();
    $update->addColumnToUsersTable40();
    $update->addColumnToUsersTable41();

    $update->addColumnToPostsTable15();

    // for v5.0

    $update->addColumnToAccessDataTable1();
    $update->addColumnToAccessDataTable2();
    $update->addColumnToAccessDataTable3();
    $update->addColumnToUsersTable42();
    $update->addColumnToUsersTable43();
    $update->addColumnToUsersTable44();

    $update->renameRowInLikesTable();
    $update->renameRowInCommentsTable();

    $settings = new settings($dbo);
    $settings->createValue("allowMultiAccountsFunction", 1); //Default allow create multi-accounts
    $settings->createValue("allowFacebookAuthorization", 1); //Default allow facebook authorization
    $settings->createValue("photoModeration", 1); //Default on
    $settings->createValue("coverModeration", 1); //Default on
    unset($settings);

    // for 5.3

    //$update->updateUsersTable1();

    // for 5.7

    $update->addColumnToPostsTable16();
    $update->addColumnToPostsTable17();

    // for 5.9

    $update->addColumnToPostsTable18();

    // for 6.0

    $update->addColumnToUsersTable45();
    $update->addColumnToUsersTable46();

    // for 6.1

    $update->updateUsersTable47();
    $update->modifyUsersTable1();

    //

    $settings = new settings($dbo);
    $settings->createValue("defaultAllowMessages", 0); //Default off
    $settings->createValue("interstitialAdAfterNewItem", 1);
    $settings->createValue("interstitialAdAfterNewGalleryItem", 1);
    $settings->createValue("interstitialAdAfterNewMarketItem", 1);
    $settings->createValue("interstitialAdAfterNewLike", 5);
    unset($settings);

    //

    $settings = new settings($dbo);
    $settings->createValue("S3_AMAZON", 0);
    $settings->createValue("S3_REGION", 0, "eu-north-1");
    $settings->createValue("S3_KEY", 0, "");
    $settings->createValue("S3_SECRET", 0, "");

    $settings->createValue("RECAPTCHA_SIGNUP_APP", 0);

    unset($settings);

    $update->modifySettingsTable1();

    //

    //$update->updateUsersTable48();

    // for 6.4

    $settings = new settings($dbo);
    $settings->createValue("admin_account", 0, "");
    $settings->createValue("admin_account_id", 0, "");
    $settings->createValue("admin_account_allow_alerts", 0);
    unset($settings);

    // for 6.5

    $update->addColumnToUsersTable47();
    $update->addColumnToUsersTable48();

    // For version 6.6

    $settings = new settings($dbo);

    $settings->createValue("android_admob_app_id", 0, 'ca-app-pub-3940256099942544~3347511713');
    $settings->createValue("android_admob_banner_ad_unit_id", 0, 'ca-app-pub-3940256099942544/6300978111');
    $settings->createValue("android_admob_rewarded_ad_unit_id", 0, 'ca-app-pub-3940256099942544/5224354917');
    $settings->createValue("android_admob_interstitial_ad_unit_id", 0, 'ca-app-pub-3940256099942544/1033173712');
    $settings->createValue("android_admob_banner_native_ad_unit_id", 0, 'ca-app-pub-3940256099942544/2247696110');

    $settings->createValue("ios_admob_app_id", 0, 'ca-app-pub-3940256099942544~1458002511');
    $settings->createValue("ios_admob_banner_ad_unit_id", 0, 'ca-app-pub-3940256099942544/2934735716');
    $settings->createValue("ios_admob_rewarded_ad_unit_id", 0, 'ca-app-pub-3940256099942544/1712485313');
    $settings->createValue("ios_admob_interstitial_ad_unit_id", 0, 'ca-app-pub-3940256099942544/4411468910');
    $settings->createValue("ios_admob_banner_native_ad_unit_id", 0, 'ca-app-pub-3940256099942544/2247696110');

    unset($settings);

    // For version 6.7

    $settings = new settings($dbo);

    $settings->createValue("gcv_adult", 0);
    $settings->createValue("gcv_violence", 0);
    $settings->createValue("gcv_racy", 0);
    $settings->createValue("gcv_spoof", 0);
    $settings->createValue("gcv_medical", 0);

    unset($settings);

    // For version 6.9

    $settings = new settings($dbo);
    $settings->createValue("gcs_photo", 0); // Disabled by default
    $settings->createValue("gcs_cover", 0); // Disabled by default
    $settings->createValue("gcs_gallery", 0); // Disabled by default
    $settings->createValue("gcs_video", 0); // Disabled by default
    $settings->createValue("gcs_item", 0); // Disabled by default
    $settings->createValue("gcs_market", 0); // Disabled by default
    $settings->createValue("gcs_chat", 0); // Disabled by default
    $settings->createValue("gcs_auto_delete", 0);
    $settings->createValue("gcs_photo_bucket", 0, "");
    $settings->createValue("gcs_cover_bucket", 0, "");
    $settings->createValue("gcs_gallery_bucket", 0, "");
    $settings->createValue("gcs_video_bucket", 0, "");
    $settings->createValue("gcs_item_bucket", 0, "");
    $settings->createValue("gcs_market_bucket", 0, "");
    $settings->createValue("gcs_chat_bucket", 0, "");
    unset($settings);

    // For version 7.0

    $update->addColumnToLikesTable1();
    $update->addColumnToGalleryLikesTable1();
    $update->addColumnToNotificationsTable1();

    // For version 7.1

    $update->addColumnToUsersTable49();

    // For version 7.2

    $update->addColumnToUsersTable50();

    // For version 7.4

    $update->addColumnToUsersTable51();

    $settings = new settings($dbo);
    $settings->createValue("agora_app_enabled", 1, "");
    $settings->createValue("agora_app_id", 0, "");
    $settings->createValue("agora_app_certificate", 0, "");
    unset($settings);

    // Add standard feelings

    $feelings = new feelings($dbo);

    if ($feelings->db_getMaxId() < 1) {

        for ($i = 1; $i <= 12; $i++) {

            $feelings->db_add(APP_URL."/feelings/".$i.".png");

        }
    }

    // Add standard stickers

    $stickers = new sticker($dbo);

    if ($stickers->db_getMaxId() < 1) {

        for ($i = 1; $i < 28; $i++) {

            $stickers->db_add(APP_URL."/stickers/".$i.".png");
        }
    }

    unset($stickers);

    $css_files = array();
    $page_title = APP_TITLE;

    include_once("../html/common/header.inc.php");
?>

<body class="remind-page has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content">

                <div class="standard-page">

                    <div class="alert alert-success mt-5">
                        <ul>
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
                        </ul>
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