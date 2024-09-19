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

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $message = isset($_POST['message']) ? $_POST['message'] : '';
        $type = isset($_POST['type']) ? $_POST['type'] : 1;

        $message = helper::clearText($message);
        $message = helper::escapeText($message);

        $type = helper::clearInt($type);

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if (strlen($message) != 0) {

                $gcm = new gcm($dbo, 0);
                $gcm->setData($type, $message, 0);
                $gcm->forAll();
                $gcm->sendToAll();
            }
        }

        header("Location: /admin/gcm");
        exit;
    }

    $page_id = "gcm";

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Firebase Cloud Messages | Admin Panel";

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
                            <li class="breadcrumb-item active">Send message (FCM) for all users</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <?php
                    if (APP_DEMO) {

                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">Warning!</h4>
                                            <p class="card-text">Sending push notifications (FCM) is not available in the demo version mode. That we turned off the sending push notifications (FCM) in the demo version mode to protect users from spam and of foul language.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                ?>


                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Send Push Notification</h4>

                                <form class="form-material m-t-40"  method="post" action="/admin/gcm">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label>Message type</label>
                                        <select class="form-control" name="type">
                                            <option selected="selected" value="<?php echo GCM_NOTIFY_SYSTEM; ?>">For all users</option>
                                            <option value="<?php echo GCM_NOTIFY_CUSTOM; ?>">Only for authorized users</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label >Message text</label>
                                        <input placeholder="Message text" id="message" type="text" name="message" maxlength="100" class="form-control form-control-line">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>

                <?php
                    $result = $stats->getGcmHistory();

                    $inbox_loaded = count($result['data']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Recently sent messages</h4>
                                        <div class="table-responsive">

                                            <table class="table color-table info-table">

                                                <thead>
                                                    <tr>
                                                        <th class="text-left">Id</th>
                                                        <th>Message</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Delivered</th>
                                                        <th>Create At</th>
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
                                            <h4 class="card-title">History is empty.</h4>
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

    function draw($itemObj)
    {
        ?>

        <tr>
            <td class="text-left"><?php echo $itemObj['id']; ?></td>
            <td><?php echo $itemObj['msg']; ?></td>
            <td>
                <?php

                    switch ($itemObj['msgType']) {

                        case GCM_NOTIFY_SYSTEM: {

                            echo "For all users";
                            break;
                        }

                        case GCM_NOTIFY_CUSTOM: {

                            echo "Only for authorized users";
                            break;
                        }

                        default: {

                            break;
                        }
                    }
                ?>
            </td>
            <td><?php if ($itemObj['status'] == 1) {echo "success";} else {echo "failure";} ?></td>
            <td><?php echo $itemObj['success']; ?></td>
            <td><?php echo date("Y-m-d H:i:s", $itemObj['createAt']); ?></td>
        </tr>

        <?php
    }
