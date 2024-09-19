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

    $error = false;
    $error_message = '';

    $stats = new stats($dbo);
    $admin = new admin($dbo);

    $accountInfo = array();

    if (isset($_GET['id'])) {

        $accountId = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;
        $act = isset($_GET['act']) ? $_GET['act'] : '';

        $accountId = helper::clearInt($accountId);

        $account = new account($dbo, $accountId);
        $accountInfo = $account->get();

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch ($act) {

                case "block": {

                    $account->setState(ACCOUNT_STATE_BLOCKED);

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    break;
                }

                case "unblock": {

                    $account->setState(ACCOUNT_STATE_ENABLED);

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    break;
                }

                case "verify": {

                    $account->setVerify(1);

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    break;
                }

                case "unverify": {

                    $account->setVerify(0);

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    break;
                }

                case "delete-photo": {

                    $data = array("originPhotoUrl" => '',
                                  "normalPhotoUrl" => '',
                                  "lowPhotoUrl" => '');

                    $account->setPhoto($data);

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    break;
                }

                default: {

                    if (!empty($_POST)) {

                        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
                        $username = isset($_POST['username']) ? $_POST['username'] : '';
                        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
                        $location = isset($_POST['location']) ? $_POST['location'] : '';
                        $fb_page = isset($_POST['fb_page']) ? $_POST['fb_page'] : '';
                        $instagram_page = isset($_POST['instagram_page']) ? $_POST['instagram_page'] : '';

                        $username = helper::clearText($username);
                        $username = helper::escapeText($username);

                        $fullname = helper::clearText($fullname);
                        $fullname = helper::escapeText($fullname);

                        $location = helper::clearText($location);
                        $location = helper::escapeText($location);

                        $fb_page = helper::clearText($fb_page);
                        $fb_page = helper::escapeText($fb_page);

                        $instagram_page = helper::clearText($instagram_page);
                        $instagram_page = helper::escapeText($instagram_page);

                         if ($authToken === helper::getAuthenticityToken()) {

                            $account->setUsername($username);
                            $account->setFullname($fullname);
                            $account->setLocation($location);
                            $account->setFacebookPage($fb_page);
                            $account->setInstagramPage($instagram_page);
                         }
                    }

                    header("Location: /admin/group/?id=".$accountInfo['id']);
                    exit;
                }
            }
        }

    } else {

        header("Location: /admin/main");
        exit;
    }

    if ($accountInfo['error'] === true) {

        header("Location: /admin/main");
        exit;
    }

    $page_id = "group";


    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Community info | Admin Panel";

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
                            <li class="breadcrumb-item active">Community info</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>


                <div class="row">

                    <div class="col-lg-8">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Community Info</h4>
                                <div class="table-responsive">
                                    <table class="table color-table info-table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Value/Count</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Username:</td>
                                                <td>@<?php echo $accountInfo['username']; ?></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Fullname:</td>
                                                <td><?php echo $accountInfo['fullname']; ?></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>Created by Ip address:</td>
                                                <td><?php if (!APP_DEMO) {echo $accountInfo['ip_addr'];} else {echo "It is not available in the demo version";} ?></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td>Date Created:</td>
                                                <td><?php echo date("Y-m-d H:i:s", $accountInfo['regtime']); ?></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td>Community state:</td>
                                                <td>
                                                    <?php

                                                    if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                        echo "<span>Community is active</span>";

                                                    } else {

                                                        echo "<span>Community is blocked</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                        ?>
                                                        <a class="" href="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=block">Block community</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                        <a class="" href="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unblock">Unblock community</a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Community verified:</td>
                                                <td>
                                                    <?php

                                                    if ($accountInfo['verify'] == 1) {

                                                        echo "<span>Community is verified.</span>";

                                                    } else {

                                                        echo "<span>Community is not verified.</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php

                                                    if ($accountInfo['verify'] == 1) {

                                                        ?>
                                                        <a class="" href="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Unset verified</a>
                                                        <?php

                                                    } else {

                                                        ?>
                                                        <a class="" href="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set community as verified</a>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Total community posts:</td>
                                                <td><?php echo $stats->getCommunityItemsTotal($accountInfo['id']); ?></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td>Community active posts (not removed):</td>
                                                <td>
                                                    <?php
                                                    $active_items = $stats->getCommunityItemsCount($accountInfo['id']);
                                                    echo $active_items;
                                                    ?>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Community</h4>

                                <form class="form-material m-t-40" method="post" action="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label class="col-md-12">Username</label>
                                        <div class="col-md-12">
                                            <input placeholder="Username" id="username" type="text" name="username" maxlength="255" value="<?php echo $accountInfo['username']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Fullname</label>
                                        <div class="col-md-12">
                                            <input placeholder="Fullname" id="fullname" type="text" name="fullname" maxlength="255" value="<?php echo $accountInfo['fullname']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Location</label>
                                        <div class="col-md-12">
                                            <input placeholder="Location" id="location" type="text" name="location" maxlength="255" value="<?php echo $accountInfo['location']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Facebook page</label>
                                        <div class="col-md-12">
                                            <input placeholder="Facebook page" id="fb_page" type="text" name="fb_page" maxlength="255" value="<?php echo $accountInfo['fb_page']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Instagram page</label>
                                        <div class="col-md-12">
                                            <input placeholder="Instagram page" id="instagram_page" type="text" name="instagram_page" maxlength="255" value="<?php echo $accountInfo['instagram_page']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <!-- Column -->
                        <div class="card">
                            <img class="card-img-top" src="/img/cover_add.png" alt="Card image cap">
                            <div class="card-block little-profile text-center">

                                <div class="pro-img">

                                    <?php

                                        if (strlen($accountInfo['lowPhotoUrl']) != 0) {

                                            ?>
                                                <img src="<?php echo $accountInfo['normalPhotoUrl'] ?>" alt="user" />
                                            <?php

                                        } else {

                                            ?>
                                                <img src="/img/profile_default_photo.png" alt="user" />
                                            <?php
                                        }
                                    ?>

                                </div>

                                <?php

                                    if (strlen($accountInfo['lowPhotoUrl']) != 0) {

                                        ?>
                                            <a href="/admin/group/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete-photo">Delete Photo</a>
                                        <?php

                                    }
                                ?>

                                <h3 class="m-b-0"><?php echo $accountInfo['fullname']; ?></h3>
                                <p>@<?php echo $accountInfo['username']; ?></p>
                                <div class="row text-center m-t-20">
                                    <div class="col-lg-6 col-md-6 m-t-20">
                                        <h3 class="m-b-0 font-light"><?php echo $stats->getCommunityItemsTotal($accountInfo['id']); ?></h3><small>Total posts</small></div>
                                    <div class="col-lg-6 col-md-6 m-t-20">
                                        <h3 class="m-b-0 font-light"><?php echo $stats->getCommunityItemsCount($accountInfo['id']); ?></h3><small>Active posts</small></div>
                                </div>
                            </div>
                        </div>
                        <!-- Column -->
                    </div>

                </div>


            </div> <!-- End Container fluid  -->

            <?php

                include_once("../html/common/admin_footer.inc.php");
            ?>

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>
