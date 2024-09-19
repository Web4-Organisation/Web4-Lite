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

        $messages = new messages($dbo);
        $messages->setRequestFrom($accountId);

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch ($act) {

                case "disconnect": {

                    $account->setFacebookId('');

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "showAdmob": {

                    $account->setAdmob(1);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "hideAdmob": {

                    $account->setAdmob(0);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "close": {

                    $auth->removeAll($accountId);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "block": {

                    $account->setState(ACCOUNT_STATE_BLOCKED);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "unblock": {

                    $account->setState(ACCOUNT_STATE_ENABLED);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "verify": {

                    $account->setVerify(1);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "unverify": {

                    $account->setVerify(0);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "ghost_set": {

                    $account->setGhost(1);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "ghost_unset": {

                    $account->setGhost(0);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "delete-cover": {

                    $data = array("originCoverUrl" => '',
                        "normalCoverUrl" => '');

                    $account->setCover($data);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                case "delete-photo": {

                    $data = array("originPhotoUrl" => '',
                        "normalPhotoUrl" => '',
                        "lowPhotoUrl" => '');

                    $account->setPhoto($data);

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
                    break;
                }

                default: {

                    if (!empty($_POST)) {

                        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
                        $username = isset($_POST['username']) ? $_POST['username'] : '';
                        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
                        $location = isset($_POST['location']) ? $_POST['location'] : '';
                        $balance = isset($_POST['balance']) ? $_POST['balance'] : 0;
                        $fb_page = isset($_POST['fb_page']) ? $_POST['fb_page'] : '';
                        $instagram_page = isset($_POST['instagram_page']) ? $_POST['instagram_page'] : '';
                        $email = isset($_POST['email']) ? $_POST['email'] : '';

                        $username = helper::clearText($username);
                        $username = helper::escapeText($username);

                        $fullname = helper::clearText($fullname);
                        $fullname = helper::escapeText($fullname);

                        $location = helper::clearText($location);
                        $location = helper::escapeText($location);

                        $balance = helper::clearInt($balance);

                        $fb_page = helper::clearText($fb_page);
                        $fb_page = helper::escapeText($fb_page);

                        $instagram_page = helper::clearText($instagram_page);
                        $instagram_page = helper::escapeText($instagram_page);

                        $email = helper::clearText($email);
                        $email = helper::escapeText($email);

                        if ($authToken === helper::getAuthenticityToken()) {

                            $account->setUsername($username);
                            $account->setFullname($fullname);
                            $account->setLocation($location);
                            $account->setBalance($balance);
                            $account->setFacebookPage($fb_page);
                            $account->setInstagramPage($instagram_page);
                            $account->setEmail($email);
                        }
                    }

                    header("Location: /admin/profile/?id=".$accountInfo['id']);
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

    $page_id = "account";


    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Account Info | Admin Panel";

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
                            <li class="breadcrumb-item active">Account Info</li>
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
                                <h4 class="card-title">Account Info</h4>
                                <h6 class="card-subtitle">
                                    <a href="/admin/personal_gcm/?id=<?php echo $accountInfo['id']; ?>">
                                        <button class="btn waves-effect waves-light btn-info">Send Personal FCM Message</button>
                                    </a>
                                </h6>
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
                                            <th class="text-left">Name</th>
                                            <th>Value/Count</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Username:</td>
                                            <td><?php echo $accountInfo['username']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Fullname:</td>
                                            <td><?php echo $accountInfo['fullname']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Email:</td>
                                            <td><?php echo $accountInfo['email']; ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Facebook account:</td>
                                            <td><?php if (strlen($accountInfo['fb_id']) == 0) {echo "Not connected to facebook.";} else {echo "<a target=\"_blank\" href=\"https://www.facebook.com/app_scoped_user_id/{$accountInfo['fb_id']}\">Facebook account link</a>";} ?></td>
                                            <td><?php if (strlen($accountInfo['fb_id']) == 0) {echo "";} else {echo "<a href=\"/admin/profile/?id={$accountInfo['id']}&access_token=".admin::getAccessToken()."&act=disconnect\">Remove connection</a>";} ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">SignUp Ip address:</td>
                                            <td><?php if (!APP_DEMO) {echo $accountInfo['ip_addr'];} else {echo "It is not available in the demo version";} ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">SignUp Date:</td>
                                            <td><?php echo date("Y-m-d H:i:s", $accountInfo['regtime']); ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Account state:</td>
                                            <td>
                                                <?php

                                                if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                    echo "<span>Account is active</span>";

                                                } else if ($accountInfo['state'] == ACCOUNT_STATE_DISABLED) {

                                                    echo "<span>Account deactivated (deleted) by user</span>";

                                                } else {

                                                    echo "<span>Account is blocked</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                if ($accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

                                                    ?>
                                                        <a class="" href="/admin/profile?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=block">Block account</a>
                                                    <?php

                                                } else if ($accountInfo['state'] == ACCOUNT_STATE_BLOCKED) {

                                                    ?>
                                                        <a class="" href="/admin/profile?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unblock">Unblock account</a>
                                                    <?php

                                                } else {

                                                    ?>
                                                        <span class="">Account cannot be restored.</span>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Account verified:</td>
                                            <td>
                                                <?php

                                                if ($accountInfo['verify'] == 1) {

                                                    echo "<span>Account is verified.</span>";

                                                } else {

                                                    echo "<span>Account is not verified.</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                if ($accountInfo['verify'] == 1) {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=unverify">Unset verified</a>
                                                    <?php

                                                } else {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=verify">Set account as verified</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Disable Ads feature (on/off ads for account):</td>
                                            <td>
                                                <?php

                                                if ($accountInfo['admob'] == 1) {

                                                    echo "<span>On (Feature Disable Ads is active in account)</span>";

                                                } else {

                                                    echo "<span>Off (Feature Disable Ads is not active in account)</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                if ($accountInfo['admob'] == 1) {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=hideAdmob">Deactivate Disable Ads feature for this account</a>
                                                    <?php

                                                } else {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=showAdmob">Activate (for 30 days) Disable Ads feature for this account</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Ghost Mode:</td>
                                            <td>
                                                <?php

                                                if ($accountInfo['ghost'] == 1) {

                                                    echo "<span>Ghost Mode Activated.</span>";

                                                } else {

                                                    echo "<span>Ghost Mode Not Active.</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php

                                                if ($accountInfo['ghost'] == 1) {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=ghost_unset">Off Ghost Mode</a>
                                                    <?php

                                                } else {

                                                    ?>
                                                    <a class="" href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=ghost_set">On Ghost Mode</a>
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Total user posts:</td>
                                            <td><?php echo $stats->getUserItemsTotal($accountInfo['id']); ?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">User active posts (not removed):</td>
                                            <td>
                                                <?php
                                                $active_items = $stats->getUserItemsCount($accountInfo['id']);
                                                echo $active_items;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($active_items > 0) {

                                                ?>
                                                <a href="/admin/profile_items/?id=<?php echo $accountInfo['id']; ?>" >View</a></td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                        <tr>
                                            <td class="text-left">User active chats (not removed):</td>
                                            <td>
                                                <?php
                                                $active_chats = $messages->myActiveChatsCount();

                                                echo $active_chats;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($active_chats > 0) {

                                                ?>
                                                <a href="/admin/profile_chats/?id=<?php echo $accountInfo['id']; ?>" >View</a></td>
                                            <?php
                                            }
                                            ?>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit Profile</h4>

                                <form class="form-material m-t-40" method="post" action="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>">

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
                                        <label class="col-md-12">Email</label>
                                        <div class="col-md-12">
                                            <input placeholder="Email" id="email" type="text" name="email" maxlength="255" value="<?php echo $accountInfo['email']; ?>" class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-12">Balance</label>
                                        <div class="col-md-12">
                                            <input placeholder="Balance" id="balance" type="text" name="balance" maxlength="255" value="<?php echo $accountInfo['balance']; ?>" class="form-control form-control-line">
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

                            <?php

                                if (strlen($accountInfo['coverUrl']) != 0) {

                                    ?>
                                        <img src="<?php echo $accountInfo['coverUrl'] ?>" alt="user" />
                                    <?php

                                } else {

                                    ?>
                                        <img class="card-img-top" src="/img/cover_add.png" alt="Card image cap">
                                    <?php
                                }
                            ?>

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

                                    if (strlen($accountInfo['coverUrl']) != 0) {

                                        ?>
                                            <p><a href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete-cover">Delete Cover</a></p>
                                        <?php

                                    }

                                    if (strlen($accountInfo['lowPhotoUrl']) != 0) {

                                        ?>
                                            <p><a href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=delete-photo">Delete Photo</a></p>
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

                <?php
                    $result = $stats->getAuthData($accountInfo['id'], 0);

                    $inbox_loaded = count($result['data']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Authorizations</h4>
                                        <h6 class="card-subtitle">
                                            <a href="/admin/profile/?id=<?php echo $accountInfo['id']; ?>&access_token=<?php echo admin::getAccessToken(); ?>&act=close">
                                                <button class="btn waves-effect waves-light btn-info">Close all authorizations</button>
                                            </a>
                                        </h6>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Id</th>
                                                        <th>Access token</th>
                                                        <th>Client Id</th>
                                                        <th>Create At</th>
                                                        <th>Close At</th>
                                                        <th>User agent</th>
                                                        <th>Ip address</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <?php

                                                        foreach ($result['data'] as $key => $value) {

                                                            draw($value);
                                                        }

                                                    ?>
                                                </tbody>

                                            </table>

                                        </div>
                                    </div>
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

        </div> <!-- End Page wrapper  -->
    </div> <!-- End Wrapper -->

</body>

</html>

<?php

    function draw($authObj)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $authObj['id']; ?></td>
            <td><?php echo $authObj['accessToken']; ?></td>
            <td><?php echo $authObj['clientId']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $authObj['createAt']); ?></td>
            <td><?php if ($authObj['removeAt'] == 0) {echo "-";} else {echo date("Y-m-d H:i:s", $authObj['removeAt']);} ?></td>
            <td><?php echo $authObj['u_agent']; ?></td>
            <td><?php if (!APP_DEMO) {echo $authObj['ip_addr'];} else {echo "It is not available in the demo version";} ?></td>
        </tr>

        <?php
    }
