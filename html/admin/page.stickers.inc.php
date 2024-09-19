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

    if (!admin::isSession()) {

        header("Location: /admin/login");
        exit;
    }

    //

    $stats = new stats($dbo);
    $sticker = new sticker($dbo);

    $error = false;
    $error_message = '';

    if (isset($_GET['action'])) {

        $action = isset($_GET['action']) ? $_GET['action'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : '';

        $action = helper::clearText($action);
        $action = helper::escapeText($action);

        $id = helper::clearInt($id);

        if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

            switch($action) {

                case 'remove': {

                    $sticker->db_remove($id);

                    header("Location: /admin/stickers");
                    exit;

                    break;
                }

                default: {

                    header("Location: /admin/stickers");
                    exit;

                    break;
                }
            }
        }
    }

    if (!empty($_POST)) {

        $authToken = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';

        if ($authToken === helper::getAuthenticityToken() && !APP_DEMO) {

            if (isset($_FILES['uploaded_file']['name'])) {

                $uploaded_file = $_FILES['uploaded_file']['tmp_name'];
                $uploaded_file_name = basename($_FILES['uploaded_file']['name']);
                $uploaded_file_ext = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);

                $sticker_next_id = $sticker->db_getMaxId();
                $sticker_next_id++;

                if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], "stickers/".$sticker_next_id.".".$uploaded_file_ext)) {

                    $sticker->db_add(APP_URL."/"."stickers/".$sticker_next_id.".".$uploaded_file_ext, 0, 0);
                }
            }
        }

        header("Location: /admin/stickers");
        exit;
    }

    $page_id = "stickers";

    helper::newAuthenticityToken();

    $css_files = array("mytheme.css");
    $page_title = "Stickers | Admin Panel";

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
                            <li class="breadcrumb-item active">Stickers</li>
                        </ol>
                    </div>
                </div>

                <?php

                    include_once("../html/common/admin_banner.inc.php");
                ?>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Add New Sticker</h4>

                                <form class="form-material m-t-40"  method="post" action="/admin/stickers" enctype="multipart/form-data">

                                    <input type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                                    <div class="form-group">
                                        <label>Sticker Image File</label>
                                        <input name="uploaded_file" type="file" class="form-control" id="exampleInputFile" aria-describedby="fileHelp" placeholder="Image File (Attention! To view images correctly - we recommend using the image size of 256x256 pixels. Formats: JPG and PNG.)">
                                    </div>

                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <button class="btn btn-info text-uppercase waves-effect waves-light" type="submit">Add</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>


                </div>

                <?php
                    $result = $sticker->db_get(0, 100);

                    $inbox_loaded = count($result['items']);

                    if ($inbox_loaded != 0) {

                        ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title m-b-0">Stickers</h4>
                                    </div>
                                    <div class="card-body collapse show">
                                        <div class="table-responsive">
                                            <table class="table product-overview">
                                                <thead>
                                                <tr>
                                                    <th class="text-left">Id</th>
                                                    <th>Sticker Image</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody class="data-table">
                                                    <?php

                                                        foreach ($result['items'] as $key => $value) {

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

    function draw($itemObj)
    {
        ?>

        <tr data-id="<?php echo $itemObj['id']; ?>">
            <td class="text-left"><?php echo $itemObj['id']; ?></td>
            <td style="text-align: left;"><img width="80" src="<?php echo $itemObj['imgUrl']; ?>"></td>
            <td><?php echo $itemObj['date']; ?></td>
            <td><a href="/admin/stickers?id=<?php echo $itemObj['id']; ?>&action=remove&access_token=<?php echo admin::getAccessToken(); ?>">Remove</a></td>
        </tr>

        <?php
    }
