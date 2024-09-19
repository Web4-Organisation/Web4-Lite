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

    $stats = new stats($dbo);
    $admin = new admin($dbo);

    $postId = 0;
    $postInfo = array();

    if (isset($_GET['id'])) {

        $postId = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        $postId = helper::clearInt($postId);

        $post = new post($dbo);
        $postInfo = $post->info($postId);

        if ($postInfo['error'] === true) {

            header("Location: /admin/main");
            exit;
        }

        if ($postInfo['removeAt'] != 0) {

            header("Location: /admin/post_reports");
            exit;
        }

    } else {

        header("Location: /admin/main");
        exit;
    }

    $page_id = "post";

    $css_files = array("mytheme.css");
    $page_title = "Post Info | Admin Panel";

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
                            <li class="breadcrumb-item active">Post Info</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <?php

                    if ($postInfo['removeAt'] == 0) {

                        ?>

                            <div class="col-lg-6 col-md-12">
                                <!-- Card -->
                                <div class="card">
                                    <?php

                                        if (strlen($postInfo['imgUrl'])) {

                                            ?>

                                            <img class="card-img-top img-responsive" src="<?php echo $postInfo['imgUrl']; ?>" alt="Card image">

                                            <?php
                                        }
                                    ?>

                                    <div class="card-body">
                                        <h4 class="card-title"><a href="/admin/profile?id=<?php echo $postInfo['fromUserId']; ?>"><?php echo $postInfo['fromUserFullname']; ?></a></h4>

                                        <?php

                                            if (strlen($postInfo['post'])) {

                                                ?>
                                                    <p class="card-text"><?php echo $postInfo['post']; ?></p>
                                                <?php
                                            }
                                        ?>

                                        <?php

                                            if ($postInfo['rePostId'] != 0) {

                                                $rePost = $postInfo['rePost'];
                                                $rePost = $rePost[0];

                                                ?>
                                                    <p class="card-text">Re-post: <a target="_blank" href="<?php echo "/{$rePost['fromUserUsername']}/post/{$rePost['id']}"; ?>">See Re-post -></a></p>
                                                <?php
                                            }

                                        ?>


                                        <a class="btn btn-danger" href="javascript: void(0)" onclick="Post.remove('<?php echo $postInfo['id']; ?>', '<?php echo $postInfo['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>'); return false;">Delete</a>

                                    </div>
                                </div>
                                <!-- Card -->
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

                window.Post || ( window.Post = {} );

                Post.remove = function (offset, fromUserId, accessToken) {

                    $.ajax({
                        type: 'GET',
                        url: '/admin/stream/?id=' + offset + '&fromUserId=' + fromUserId + '&access_token=' + accessToken + "&act=remove",
                        data: 'itemId=' + offset + '&fromUserId=' + fromUserId + "&access_token=" + accessToken,
                        timeout: 30000,
                        success: function(response){

                            $('div.item[data-id=' + offset + ']').remove();

                            window.location.href = "/admin/post_reports";
                        },
                        error: function(xhr, type){

                        }
                    });
                };

            </script>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>