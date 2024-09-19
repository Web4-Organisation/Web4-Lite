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

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    $accountInfo = array();

    if (isset($_GET['id'])) {

        $accountId = isset($_GET['id']) ? $_GET['id'] : 0;

        $account = new account($dbo, $accountId);
        $accountInfo = $account->get();

        $messages = new messages($dbo);
        $messages->setRequestFrom($accountId);

    } else {

        header("Location: /admin/main");
        exit;
    }

    if ($accountInfo['error'] === true) {

        header("Location: /admin/main");
        exit;
    }

    $stats = new stats($dbo);

    $inbox_all = $messages->myActiveChatsCount();
    $inbox_loaded = 0;

    if (!empty($_POST)) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : '';
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $messages->getChats($itemId);

        $inbox_loaded = count($result['chats']);

        $result['inbox_loaded'] = $inbox_loaded + $loaded;
        $result['inbox_all'] = $inbox_all;

        if ($inbox_loaded != 0) {

            ob_start();

            foreach ($result['chats'] as $key => $value) {

                draw($value);
            }

            $result['html'] = ob_get_clean();

            if ($result['inbox_loaded'] < $inbox_all) {

                ob_start();

                ?>

                    <a href="javascript:void(0)" onclick="Messages.moreItems('<?php echo $result['messageCreateAt']; ?>'); return false;">
                        <button type="button" class="btn  btn-info footable-show">View More</button>
                    </a>

                <?php

                $result['html2'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    $page_id = "chats";

    $css_files = array("mytheme.css");
    $page_title = "User active chats | Admin Panel";

    include_once("../html/common/admin_header.inc.php");
?>

<body class="fix-header fix-sidebar card-no-border">

    <div id="main-wrapper">

        <?php

            include_once("../html/common/admin_topbar.inc.php");
        ?>

        <?php

            include_once("../html/common/admin_sidebar.inc.php");
        ?>

        <div class="page-wrapper">

            <div class="container-fluid">

                <div class="row page-titles">
                    <div class="col-md-5 col-8 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/main">Home</a></li>
                            <li class="breadcrumb-item active">User items</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <?php

                    $result = $messages->getChats(0);

                    $inbox_loaded = count($result['chats']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="card">

                                    <div class="card-block bg-info">
                                        <h4 class="text-white card-title">User Active Chats</h4>
                                        <h6 class="card-subtitle text-white m-b-0 op-5">Click Chat to view messages</h6>
                                    </div>

                                    <div class="card-block">
                                        <div class="message-box contact-box">
                                            <div class="message-widget contact-widget">

                                                <?php

                                                        foreach ($result['chats'] as $key => $value) {

                                                            draw($value);
                                                        }

                                                    ?>

                                            </div>
                                        </div>
                                    </div>

                                    <?php

                                        if ($inbox_all > 20) {

                                            ?>

                                            <div class="card-body more-items loading-more-container">
                                                <a href="javascript:void(0)" onclick="Messages.moreItems('<?php echo $result['messageCreateAt']; ?>'); return false;">
                                                    <button type="button" class="btn  btn-info footable-show">View More</button>
                                                </a>
                                            </div>

                                            <?php
                                        }
                                    ?>

                                </div>
                            </div>
                        </div>

                        <?php

                    } else {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">List is empty.</h4>
                                            <p class="card-text">This means that there is no data to display :)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>

            </div> <!-- End Container fluid  -->

            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

        <script type="text/javascript">

            var inbox_all = <?php echo $inbox_all; ?>;
            var inbox_loaded = <?php echo $inbox_loaded; ?>;

            window.Messages || ( window.Messages = {} );

            Messages.moreItems = function (offset) {

                $('div.loading-more-container').hide();

                $.ajax({
                    type: 'POST',
                    url: '/admin/profile_chats/?id=' + <?php echo $accountInfo['id'] ?>,
                    data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
                    dataType: 'json',
                    timeout: 30000,
                    success: function(response){

                        if (response.hasOwnProperty('html2')){

                            $("div.loading-more-container").html("").append(response.html2).show();
                        }

                        if (response.hasOwnProperty('html')){

                            $("div.message-widget").append(response.html);
                        }

                        inbox_loaded = response.inbox_loaded;
                        inbox_all = response.inbox_all;
                    },
                    error: function(xhr, type){

                        $('div.loading-more-container').show();
                    }
                });
            };

        </script>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>

<?php

    function draw($item)
    {
        ?>

            <a href="/admin/chat/?id=<?php echo $item['id']; ?>" class="data-item" data-id="<?php echo $item['id']; ?>">
                <div class="user-img">

                <?php

                        if (strlen($item['withUserPhotoUrl']) != 0) {

                            ?>
                                <img src="<?php echo $item['withUserPhotoUrl']; ?>" alt="user" class="img-circle">
                            <?php

                        } else {

                            ?>
                                <img src="/img/profile_default_photo.png" alt="user" class="img-circle">
                            <?php
                        }

                    if (strlen($item['withUserOnline'])) {

                        ?>
                            <span class="profile-status online pull-right"></span>
                        <?php

                    }
                ?>

                </div>
                <div class="mail-contnet">
                    <h5><?php echo $item['withUserFullname']; ?></h5>
                    <span class="mail-desc">@<?php echo $item['withUserUsername']; ?></span>
                </div>
            </a>

        <?php
    }