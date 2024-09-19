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
	$stream = new stream($dbo);

	if (isset($_GET['itemId'])) {

		$act = isset($_GET['act']) ? $_GET['act'] : "";
		$itemId = isset($_GET['itemId']) ? $_GET['itemId'] : 0;
		$fromUserId = isset($_GET['fromUserId']) ? $_GET['fromUserId'] : 0;
		$accessToken = isset($_GET['access_token']) ? $_GET['access_token'] : 0;

		$itemId = helper::clearInt($itemId);
		$fromUserId = helper::clearInt($fromUserId);

		if ($accessToken === admin::getAccessToken() && !APP_DEMO) {

			switch ($act) {

				case "remove": {

					$post = new post($dbo);
					$post->setRequestFrom($fromUserId);
					$post->remove($itemId);
					unset($post);

					break;
				}

				case "removeByIp": {

					$post = new post($dbo);
					$post->setRequestFrom($fromUserId);

					$postInfo = $post->info($itemId);

					$post->removeByIp($postInfo['ip_addr']);

					unset($postInfo);
					unset($post);

					break;
				}

				default: {

					break;
				}
			}
		}

		exit;
	}

	$inbox_all = $stream->getAllCountByType();
	$inbox_loaded = 0;

	if (!empty($_POST)) {

		$itemId = isset($_POST['itemId']) ? $_POST['itemId'] : '';
		$loaded = isset($_POST['loaded']) ? $_POST['loaded'] : '';

		$itemId = helper::clearInt($itemId);
		$loaded = helper::clearInt($loaded);

		$result = $stream->get($itemId);

		$inbox_loaded = count($result['items']);

		$result['inbox_loaded'] = $inbox_loaded + $loaded;
		$result['inbox_all'] = $inbox_all;

		if ($inbox_loaded != 0) {

			ob_start();

			foreach ($result['items'] as $key => $value) {

				draw($value);
			}

			$result['html'] = ob_get_clean();

			if ($result['inbox_loaded'] < $inbox_all) {

				ob_start();

				?>

					<a href="javascript:void(0)" onclick="Profile.moreItems('<?php echo $result['itemId']; ?>'); return false;">
						<button type="button" class="btn  btn-info footable-show">View More</button>
					</a>

				<?php

				$result['html2'] = ob_get_clean();
			}
		}

		echo json_encode($result);
		exit;
	}

	$page_id = "stream";

	$css_files = array("mytheme.css");
	$page_title = "Stream | Admin Panel";

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
							<li class="breadcrumb-item active">Stream</li>
						</ol>
					</div>
				</div>

				<?php

					include_once("../html/common/admin_banner.inc.php");
				?>

				<?php

					$result = $stream->get(0);

					$inbox_loaded = count($result['items']);

					if ($inbox_loaded != 0) {

						?>

						<div class="col-lg-12 ">
							<div class="card">
                                <div class="card-block">
                                    <h3 class="card-title">Posts Stream</h3>
                                </div>

								<div class="tab-content">
									<div class="tab-pane active">
										<div class="card-block">
											<div class="profiletimeline items-content">

												<?php

													foreach ($result['items'] as $key => $value) {

														draw($value);
													}

												?>
											</div>
										</div>
									</div>
								</div>

								<?php

										if ($inbox_all > 20) {

											?>

											<div class="card-body more-items loading-more-container">
												<a href="javascript:void(0)" onclick="Profile.moreItems('<?php echo $result['itemId']; ?>'); return false;">
													<button type="button" class="btn  btn-info footable-show">View More</button>
												</a>
											</div>

											<?php
										}
									?>

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

			window.Post || ( window.Post = {} );

			Post.remove = function (offset, fromUserId, accessToken, action) {

				$.ajax({
					type: 'GET',
					url: '/admin/stream/?id=' + offset + '&fromUserId=' + fromUserId + '&access_token=' + accessToken + "&act=" + action,
					data: 'itemId=' + offset + '&fromUserId=' + fromUserId + "&access_token=" + accessToken,
					timeout: 30000,
					success: function(response){

						$('div.sl-item[data-id=' + offset + ']').remove();
						$('hr[data-id=' + offset + ']').remove();
					},
					error: function(xhr, type){

					}
				});
			};

			window.Stoplist || ( window.Stoplist = {} );

			Stoplist.action = function (offset, fromUserId, accessToken, action) {

				$.ajax({
					type: 'GET',
					url: '/admin/stoplist/?id=' + offset + '&fromUserId=' + fromUserId + '&access_token=' + accessToken + "&act=" + action,
					data: 'itemId=' + offset + '&fromUserId=' + fromUserId + "&access_token=" + accessToken,
					timeout: 30000,
					success: function(response){

						$('div.sl-item[data-id=' + offset + ']').remove();
						$('hr[data-id=' + offset + ']').remove();
					},
					error: function(xhr, type){

					}
				});
			};

			window.Profile || ( window.Profile = {} );

			Profile.moreItems = function (offset) {

				$('div.loading-more-container').hide();

				$.ajax({
					type: 'POST',
					url: '/admin/stream',
					data: 'itemId=' + offset + "&loaded=" + inbox_loaded,
					dataType: 'json',
					timeout: 30000,
					success: function(response){

						if (response.hasOwnProperty('html2')){

							$("div.loading-more-container").html("").append(response.html2).show();
						}

						if (response.hasOwnProperty('html')){

							$("div.items-content").append(response.html);
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

			<div class="sl-item" data-id="<?php echo $item['id']; ?>">
				<div class="sl-left">
					<?php

						if (strlen($item['fromUserPhoto']) != 0) {

							?>
							    <img src="<?php echo $item['fromUserPhoto']; ?>" alt="user" class="img-circle">
							<?php

						} else {

							?>
							    <img src="/img/profile_default_photo.png" alt="user" class="img-circle">
							<?php
						}
					?>
				</div>
				<div class="sl-right">
					<div>
				        <a href="/admin/profile?id=<?php echo $item['fromUserId']; ?>" class="link"><?php echo $item['fromUserFullname']; ?></a>
                        <span class="sl-date"><?php echo $item['timeAgo']; ?></span>
						<div class="m-t-20 row">

                                <?php

                                    if (strlen($item['imgUrl']) != 0) {

                                        ?>
                                        <div class="col-md-3 col-xs-12">
                                            <img src="<?php echo $item['imgUrl']; ?>" alt="user" class="img-responsive radius">
                                        </div>
                                        <?php

                                    }

                                    if ($item['imagesCount'] > 1) {

                                        $postimg = new postimg(null);
                                        $postimg->setRequestFrom($item['fromUserId']);

                                        $images = $postimg->get($item['id']);

                                        foreach ($images['items'] as $key => $value) {

                                            ?>

                                            <div class="col-md-3 col-xs-12">
                                                <img src="<?php echo $value['previewImgUrl']; ?>" alt="user" class="img-responsive radius">
                                            </div>

                                            <?php
                                        }
                                    }
                                ?>
                        </div>

                        <div class="m-t-20 row">

							<div class="col-md-9 col-xs-12">
							    <?php

                                    if (strlen($item['post']) != 0) {

                                        ?>
                                            <p><?php echo $item['post']; ?></p>
                                        <?php

                                    }
                                ?>

                                <?php

                                    if ($item['rePostId'] != 0) {

                                        $rePost = $item['rePost'];
                                        $rePost = $rePost[0];

                                        ?>
                                            <a target="_blank" class="btn btn-success" href="/<?php echo "{$rePost['fromUserUsername']}/post/{$rePost['id']}"; ?>">View Repost</a>
                                        <?php

                                    }
                                ?>
							</div>
						</div>
						<div class="like-comm m-t-20">
                            <a class="link m-r-10" href="javascript: void(0)" onclick="Post.remove('<?php echo $item['id']; ?>', '<?php echo $item['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>', 'remove'); return false;">Delete</a>
                            <a class="link m-r-10" href="javascript: void(0)" onclick="Post.remove('<?php echo $item['id']; ?>', '<?php echo $item['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>', 'removeByIp'); return false;">Delete All With this IP</a>
                            <a class="link m-r-10" href="javascript: void(0)" onclick="Stoplist.action('<?php echo $item['id']; ?>', '<?php echo $item['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>', 'add'); return false;">Add IP to Stoplist</a>
                            <a class="link m-r-10" href="javascript: void(0)" onclick="Stoplist.action('<?php echo $item['id']; ?>', '<?php echo $item['fromUserId']; ?>', '<?php echo admin::getAccessToken(); ?>', 'remove'); return false;">Remove IP from Stoplist</a>
						</div>
                    </div>
                </div>
            </div>
            <hr data-id="<?php echo $item['id']; ?>">

		<?php
	}