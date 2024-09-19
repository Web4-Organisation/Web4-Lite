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

	$profileId = $helper->getUserId($request[0]);

	$postExists = true;

	$profile = new profile($dbo, $profileId);

	$profile->setRequestFrom(auth::getCurrentUserId());
	$profileInfo = $profile->get();

	if ($profileInfo['error'] === true) {

		include_once("../html/error.inc.php");
		exit;
	}

	if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

		include_once("../html/stubs/profile.inc.php");
		exit;
	}

	$post = new post($dbo);
	$post->setRequestFrom(auth::getCurrentUserId());

	$postId = helper::clearInt($request[2]);

	$postInfo = $post->info($postId);

	if ($postInfo['error'] === true) {

        // Missing
        $postExists = false;
	}

	if ($postExists && $postInfo['removeAt'] != 0) {

		// Missing
		$postExists = false;
	}

	if ($postExists && $profileInfo['id'] != $postInfo['fromUserId']) {

        // Missing
        $postExists = false;
    }

	$page_id = "post";

	$css_files = array("main.css", "tipsy.css");

	if ($postExists) {

		$page_title = helper::clearText($postInfo['post']);
        $page_title = helper::escapeText($page_title);

	} else {

		$page_title = $profileInfo['fullname']." | ".APP_HOST."/".$profileInfo['username'];
	}

	include_once("../html/common/header.inc.php");

?>

<body class="">


	<?php
		include_once("../html/common/topbar.inc.php");
	?>


	<div class="wrap content-page">

        <div class="main-column row">

                <?php

                    include_once("../html/common/sidenav.inc.php");
                ?>

                <?php

                    include_once("../html/posts/posts_nav.inc.php");
                ?>

                <div class="row col sn-content" id="content">

                    <div class="content-list-page">

                        <?php

                        if ($postExists) {

                            ?>

                            <div class="items-list content-list m-0">

                                <?php

                                draw::post($postInfo, $LANG, $helper, true);

                                ?>

                                <?php

                                    require_once ("../html/common/adsense_banner.inc.php");
                                ?>

                            </div>

                            <?php

                        } else {

                            ?>

                            <div class="card information-banner">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="m-0"><?php echo $LANG['label-post-missing']; ?></h5>
                                    </div>
                                </div>
                            </div>

                            <?php
                        }
                        ?>

                    </div>
                </div>

		</div>

	</div>

	<?php

		include_once("../html/common/footer.inc.php");
	?>

	<script type="text/javascript">

		var replyToUserId = 0;

		<?php

            if (auth::getCurrentUserId() == $profileInfo['id']) {

                ?>
					var myPage = true;
				<?php
    		}
		?>

	</script>


</body
</html>