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

?>

    <div class="modal modal-form fade" id="info-box" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title placeholder-title" id="info-box-title"><?php echo APP_TITLE; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div id="info-box-message" class="error-summary alert alert-danger"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="button primary" data-dismiss="modal"><?php echo $LANG['action-close']; ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="langModal" tabindex="-1" role="dialog" aria-labelledby="langModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="langModal"><?php echo $LANG['page-language']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php

                        foreach ($LANGS as $name => $val) {

                            echo "<a onclick=\"App.setLanguage('$val'); return false;\" class=\"box-menu-item \" href=\"javascript:void(0)\">$name</a>";
                        }

                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button primary" data-dismiss="modal"><?php echo $LANG['action-close']; ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade reactions-dlg" id="reactionsModal" data-id="" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-reactions" role="document">
            <div class="modal-content">

                <div class="modal-header">

                    <div class="" style="height: 37px">

                        <button class="button link active px-2 button-reactions reactions-100" data-value="100">
                            <?php echo $LANG['label-all']; ?>
                        </button>

                        <button class="button link px-2 button-reactions reactions-0" data-value="0">
                            <img alt="" width="18" src="/img/reactions/0.png"/>
                            <span class="mr-1">5</span>
                        </button>

                        <button class="button link px-2 button-reactions reactions-1" data-value="1">
                            <img alt="" width="18" src="/img/reactions/1.png"/>
                            <span class="mr-1">11</span>
                        </button>

                        <button class="button link px-2 button-reactions reactions-2" data-value="2">
                            <img alt="" width="18" src="/img/reactions/2.png"/>
                            <span class="mr-1">67</span>
                        </button>

                        <button class="button link px-2 button-reactions reactions-3" data-value="3">
                            <img alt="" width="18" src="/img/reactions/3.png"/>
                            <span class="mr-1">103</span>
                        </button>

                        <button class="button link px-2 button-reactions reactions-4" data-value="4">
                            <img alt="" width="18" src="/img/reactions/4.png"/>
                            <span class="mr-1">78</span>
                        </button>

                        <button class="button link px-2 button-reactions reactions-5" data-value="5">
                            <img alt="" width="18" src="/img/reactions/5.png"/>
                            <span class="mr-1">55</span>
                        </button>

                    </div>

<!--                    <h5 class="modal-title placeholder-title">--><?php //echo $LANG['label-select-gift']; ?><!--</h5>-->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-0 mx-1 mb-1" style="height: 380px; overflow: auto">

                    <div class="loader-content d-block" style="height: 320px;">
                        <div class="loader">
                            <i class="ic icon-spin icon-spin"></i>
                        </div>
                    </div>

                    <div class="content-list-page reactions-content-list-page mt-3">
                        <div class="card cards-list extended-cards-list content-list reactions-content-list px-0 shadow-none mb-0">


                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls " style="display: none;">
        <div class="slides" style="width: 2048px;"></div>
        <h3 class="title hidden"></h3>
        <a class="prev text-light">‹</a>
        <a class="next text-light">›</a>
        <a class="close text-light">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>

    <div id="modal-section"></div>

    <div id="main-footer">

        <div class="wrap">

            <ul id="footer-nav">

                <li><a href="/about"><?php echo $LANG['footer-about']; ?></a></li>
                <li><a href="/terms"><?php echo $LANG['footer-terms']; ?></a></li>
                <li><a href="/privacy"><?php echo $LANG['footer-privacy']; ?></a></li>
                <li><a href="/gdpr"><?php echo $LANG['footer-gdpr']; ?></a></li>
                <li><a href="/support"><?php echo $LANG['footer-support']; ?></a></li>
                <li><a class="lang_link" href="javascript:void(0)"  data-toggle="modal" data-target="#langModal"><i class="fa fa-globe"></i> <?php echo $LANG['lang-name']; ?></a></li>


                <li id="footer-copyright">
                    © <?php echo APP_YEAR; ?> <?php echo APP_TITLE; ?>
                </li>

            </ul>

        </div>
    </div>

    <script type="text/javascript" src="/js/jquery-3.3.1.min.js?x=2"></script>

    <script type="text/javascript" src="/js/my.js?x=347"></script>

    <script type="text/javascript" src="/js/reactions.js?x=0"></script>

    <script type="text/javascript" src="/js/jquery.colorbox.js?x=30"></script>
    <script type="text/javascript" src="/js/jquery.autosize.js?x=30"></script>
    <script type="text/javascript" src="/js/jquery.cookie.js?x=30"></script>
    <script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="/js/load-image.all.min.js"></script>
    <script type="text/javascript" src="/js/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="/js/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload-process.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload-image.js"></script>
    <script type="text/javascript" src="/js/jquery.fileupload-validate.js"></script>
    <script type="text/javascript" src="/js/blueimp-gallery.js"></script>
    <script type="text/javascript" src="/js/sidenav.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-slider.min.js"></script>


    <script type="text/javascript">

        var options = {

            pageId: "<?php echo $page_id; ?>",
            api_version: "<?php echo API_VERSION; ?>",
            post_max_images: <?php echo POST_MAX_IMAGES_COUNT; ?>
        };

        var constants = {
            MAX_FILE_SIZE: <?php echo FILE_IMAGE_MAX_UPLOAD_SIZE; ?>,
            VIDEO_FILE_MAX_SIZE: <?php echo FILE_VIDEO_MAX_UPLOAD_SIZE; ?>,
            GOOGLE_CLIENT_ID: "<?php echo GOOGLE_CLIENT_ID; ?>",
            GOOGLE_RECAPTCHA_WEB: "<?php echo GOOGLE_RECAPTCHA_WEB; ?>"
        };

        var account = {

            id: "<?php echo auth::getCurrentUserId(); ?>",
            username: "<?php echo auth::getCurrentUserLogin(); ?>",
            accessToken: "<?php echo auth::getAccessToken(); ?>"
        };

        var strings = {

            sz_action_follow: "<?php echo $LANG['action-follow']; ?>",
            sz_action_unfollow: "<?php echo $LANG['action-unfollow']; ?>",
            sz_action_login: "<?php echo $LANG['action-login']; ?>",
            sz_action_signup: "<?php echo $LANG['action-signup']; ?>",
            sz_action_block: "<?php echo $LANG['action-block']; ?>",
            sz_action_unblock: "<?php echo $LANG['action-unblock']; ?>",
            sz_action_close: "<?php echo $LANG['action-close']; ?>",
            sz_action_report: "<?php echo $LANG['action-report']; ?>",
            sz_report_reason_1: "<?php echo $LANG['label-profile-report-reason-1']; ?>",
            sz_report_reason_2: "<?php echo $LANG['label-profile-report-reason-2']; ?>",
            sz_report_reason_3: "<?php echo $LANG['label-profile-report-reason-3']; ?>",
            sz_report_reason_4: "<?php echo $LANG['label-profile-report-reason-4']; ?>",
            sz_action_remove_from_friends: "<?php echo $LANG['action-remove-from-friends']; ?>",
            sz_action_cancel_friends_request: "<?php echo $LANG['action-cancel-friend-request']; ?>",
            sz_action_add_to_friends: "<?php echo $LANG['action-add-to-friends']; ?>",
            sz_message_prompt_like: "<?php echo $LANG['label-prompt-like']; ?>",
            sz_message_prompt_title: "<?php echo $LANG['page-prompt']; ?>",
            sz_message_empty_list: "<?php echo $LANG['label-empty-list']; ?>",
            sz_action_pin: "<?php echo $LANG['action-pin']; ?>",
            sz_action_unpin: "<?php echo $LANG['action-unpin']; ?>",
            sz_page_wall: "<?php echo $LANG['page-wall']; ?>",
            sz_page_wall_description: "<?php echo $LANG['page-news-description']; ?>",
            sz_page_stream: "<?php echo $LANG['page-stream']; ?>",
            sz_page_stream_description: "<?php echo $LANG['page-stream-description']; ?>",
            sz_reactions_0: "<?php echo $LANG['label-reaction-0']; ?>",
            sz_reactions_1: "<?php echo $LANG['label-reaction-1']; ?>",
            sz_reactions_2: "<?php echo $LANG['label-reaction-2']; ?>",
            sz_reactions_3: "<?php echo $LANG['label-reaction-3']; ?>",
            sz_reactions_4: "<?php echo $LANG['label-reaction-4']; ?>",
            sz_reactions_5: "<?php echo $LANG['label-reaction-5']; ?>"
        };

    </script>

    <script type="text/javascript">

        var lang_prompt_box = "<?php echo $LANG['page-prompt']; ?>";
    </script>

    <script type="text/javascript" src="/js/common.js?x44"></script>