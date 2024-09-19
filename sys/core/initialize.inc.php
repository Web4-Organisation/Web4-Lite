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

	try {

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS users (
								  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								  gcm_regid TEXT,
								  ios_fcm_regid TEXT,
								  android_msg_fcm_regid TEXT,
								  ios_msg_fcm_regid TEXT,
								  referrer INT(10) UNSIGNED DEFAULT 0,
								  purchases_count INT(10) UNSIGNED DEFAULT 0,
								  referrals_count INT(10) UNSIGNED DEFAULT 0,
								  mood INT(10) UNSIGNED DEFAULT 0,
								  admob INT(10) UNSIGNED DEFAULT 1,
                                  admob_create_at INT(10) UNSIGNED DEFAULT 0,
								  ghost INT(10) UNSIGNED DEFAULT 0,
                                  ghost_create_at INT(10) UNSIGNED DEFAULT 0,
								  pro INT(10) UNSIGNED DEFAULT 0,
								  pro_create_at INT(10) UNSIGNED DEFAULT 0,
								  gcm INT(10) UNSIGNED DEFAULT 1,
								  state INT(10) UNSIGNED DEFAULT 0,
                          account_free INT(10) UNSIGNED DEFAULT 0,
								  access_level INT(10) UNSIGNED DEFAULT 0,
								  account_type INT(10) UNSIGNED DEFAULT 0,
								  account_category INT(10) UNSIGNED DEFAULT 0,
								  account_author INT(10) UNSIGNED DEFAULT 0,
								  surname VARCHAR(75) NOT NULL DEFAULT '',
								  fullname VARCHAR(150) NOT NULL DEFAULT '',
								  salt CHAR(3) NOT NULL DEFAULT '',
								  passw VARCHAR(32) NOT NULL DEFAULT '',
								  login VARCHAR(50) NOT NULL DEFAULT '',
								  email VARCHAR(64) NOT NULL DEFAULT '',
								  lang CHAR(10) DEFAULT 'en',
								  language CHAR(10) DEFAULT 'en',
								  bYear SMALLINT(6) UNSIGNED DEFAULT 2000,
								  bMonth SMALLINT(6) UNSIGNED DEFAULT 0,
								  bDay SMALLINT(6) UNSIGNED DEFAULT 1,
								  status VARCHAR(500) NOT NULL DEFAULT '',
								  country VARCHAR(30) NOT NULL DEFAULT '',
								  country_id INT(10) UNSIGNED DEFAULT 0,
								  city VARCHAR(50) NOT NULL DEFAULT '',
								  city_id INT(10) UNSIGNED DEFAULT 0,
								  lat float(10,6) DEFAULT 0,
								  lng float(10,6) DEFAULT 0,
								  vk_page VARCHAR(150) NOT NULL DEFAULT '',
								  fb_page VARCHAR(150) NOT NULL DEFAULT '',
								  tw_page VARCHAR(150) NOT NULL DEFAULT '',
								  my_page VARCHAR(150) NOT NULL DEFAULT '',
								  phone VARCHAR(30) NOT NULL DEFAULT '',
								  verify SMALLINT(6) UNSIGNED DEFAULT 0,
								  removed SMALLINT(6) UNSIGNED DEFAULT 0,
								  otpPhone VARCHAR(30) NOT NULL DEFAULT '',
								  otpVerified SMALLINT(6) UNSIGNED DEFAULT 0,
								  vk_id varchar(40) DEFAULT '',
								  fb_id varchar(40)	DEFAULT 0,
								  gl_id varchar(40) NOT NULL DEFAULT '',
								  tw_id varchar(40) DEFAULT '',
                                  ap_id varchar(50) DEFAULT '',
								  regtime INT(10) UNSIGNED DEFAULT 0,
								  lasttime INT(10) UNSIGNED DEFAULT 0,
								  posts_count INT(11) UNSIGNED DEFAULT 0,
								  likes_count INT(11) UNSIGNED DEFAULT 0,
								  photos_count INT(11) UNSIGNED DEFAULT 0,
								  gallery_items_count INT(11) UNSIGNED DEFAULT 0,
								  videos_count INT(11) UNSIGNED DEFAULT 0,
								  gifts_count INT(11) UNSIGNED DEFAULT 0,
								  followers_count INT(11) UNSIGNED DEFAULT 0,
								  following_count INT(11) UNSIGNED DEFAULT 0,
								  friends_count INT(11) UNSIGNED DEFAULT 0,
								  rating INT(11) UNSIGNED DEFAULT 0,
								  balance INT(11) UNSIGNED DEFAULT 0,
								  sex SMALLINT(6) UNSIGNED DEFAULT 0,
								  age SMALLINT(6) UNSIGNED DEFAULT 18,
								  last_notify_view INT(10) UNSIGNED DEFAULT 0,
								  last_friends_view INT(10) UNSIGNED DEFAULT 0,
								  last_feed_view INT(10) UNSIGNED DEFAULT 0,
								  last_guests_view INT(10) UNSIGNED DEFAULT 0,
								  last_authorize INT(10) UNSIGNED DEFAULT 0,
								  ip_addr CHAR(32) NOT NULL DEFAULT '',
								  allowComments SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowPhotosComments SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowVideoComments SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowGalleryComments SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowMessages SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowPosts SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowShowOnline SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowLikesGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowCommentsGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowFollowersGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowMessagesGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowCommentReplyGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowGiftsGCM SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowShowMyInfo SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowShowMyPhotos SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowShowMyVideos SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowShowMyGallery SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowShowMyFriends SMALLINT(6) UNSIGNED DEFAULT 0,
								  allowShowMyGifts SMALLINT(6) UNSIGNED DEFAULT 0,
                                  allowVideoCalls SMALLINT(6) UNSIGNED DEFAULT 1,
								  allowShowMyAgeAndGender SMALLINT(6) UNSIGNED DEFAULT 0,
								  lowPhotoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  originPhotoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  normalPhotoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  bigPhotoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  originCoverUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  normalCoverUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  coverPosition VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '0px 0px',
								  photoModerateAt int(11) UNSIGNED DEFAULT 0,
								  photoModerateUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  photoPostModerateAt int(11) UNSIGNED DEFAULT 0,
								  coverModerateAt int(11) UNSIGNED DEFAULT 0,
								  coverModerateUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								  coverPostModerateAt int(11) UNSIGNED DEFAULT 0,
  								PRIMARY KEY  (id), UNIQUE KEY (login)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS settings (
								  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								  name VARCHAR(150) NOT NULL DEFAULT '',
								  intValue INT(10) UNSIGNED DEFAULT 0,
								  textValue CHAR(64) NOT NULL DEFAULT '',
  								PRIMARY KEY  (id), UNIQUE KEY (name)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS admins (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								username VARCHAR(50) NOT NULL DEFAULT '',
                                salt CHAR(3) NOT NULL DEFAULT '',
                                password VARCHAR(32) NOT NULL DEFAULT '',
                                fullname VARCHAR(150) NOT NULL DEFAULT '',
                                createAt int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS chats (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId INT(11) UNSIGNED DEFAULT 0,
								toUserId INT(11) UNSIGNED DEFAULT 0,
								fromUserId_lastView INT(11) UNSIGNED DEFAULT 0,
								toUserId_lastView INT(11) UNSIGNED DEFAULT 0,
								message varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								messageCreateAt INT(11) UNSIGNED DEFAULT 0,
								updateAt INT(11) UNSIGNED DEFAULT 0,
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS messages (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								chatId int(11) UNSIGNED DEFAULT 0,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								toUserId int(11) UNSIGNED DEFAULT 0,
								message varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								stickerId int(11) UNSIGNED DEFAULT 0,
								stickerImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								seenAt INT(11) UNSIGNED DEFAULT 0,
								seenFromUserId int(11) UNSIGNED DEFAULT 0,
								seenToUserId int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS posts (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								pinned INT(10) UNSIGNED DEFAULT 0,
								feeling INT(10) UNSIGNED DEFAULT 0,
								postType int(10) UNSIGNED DEFAULT 0,
								groupId int(11) UNSIGNED DEFAULT 0,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								rePostId int(11) UNSIGNED DEFAULT 0,
								accessMode int(11) UNSIGNED DEFAULT 0,
								likesCount int(11) UNSIGNED DEFAULT 0,
								rePostsCount int(11) UNSIGNED DEFAULT 0,
								commentsCount int(11) UNSIGNED DEFAULT 0,
								viewsCount int(11) UNSIGNED DEFAULT 0,
								imagesCount int(11) UNSIGNED DEFAULT 0,
								deviceType int(11) UNSIGNED DEFAULT 0,
								rating int(11) UNSIGNED DEFAULT 0,
								post varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								urlPreviewTitle VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								urlPreviewImage VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								urlPreviewDescription VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								urlPreviewLink VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								moderatedId int(11) UNSIGNED DEFAULT 0,
								moderatedAt int(11) UNSIGNED DEFAULT 0,
								previewImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewGifUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								gifUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewVideoImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								videoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								area VARCHAR(150) NOT NULL DEFAULT '',
								country VARCHAR(150) NOT NULL DEFAULT '',
								city VARCHAR(150) NOT NULL DEFAULT '',
								lat float(10,6),
								lng float(10,6),
								u_agent varchar(300) DEFAULT '',
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								moderateAt int(11) UNSIGNED DEFAULT 0,
								needToModerate int(11) UNSIGNED DEFAULT 0,
								restoreAt int(11) UNSIGNED DEFAULT 0,
								allowRestore int(6) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS post_images (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								toItemId int(11) UNSIGNED DEFAULT 0,
								moderatedId int(11) UNSIGNED DEFAULT 0,
								moderatedAt int(11) UNSIGNED DEFAULT 0,
								originImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								u_agent varchar(300) DEFAULT '',
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
        $sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS comments (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								replyToUserId int(11) UNSIGNED DEFAULT 0,
								likesCount int(11) UNSIGNED DEFAULT 0,
								itemId int(11) UNSIGNED DEFAULT 0,
                                comment varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                commentOriginImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                commentNormalImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                createAt int(11) UNSIGNED DEFAULT 0,
                                removeAt int(11) UNSIGNED DEFAULT 0,
                                notifyId int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS support (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								clientId int(11) UNSIGNED DEFAULT 0,
                                accountId int(11) UNSIGNED DEFAULT 0,
                                email varchar(64) DEFAULT '',
                                subject varchar(180) DEFAULT '',
                                text varchar(400) DEFAULT '',
                                reply varchar(400) DEFAULT '',
                                replyAt int(11) UNSIGNED DEFAULT 0,
                                replyFrom int(11) UNSIGNED DEFAULT 0,
                                removeAt int(11) UNSIGNED DEFAULT 0,
                                createAt int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS go_links (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                accountId int(11) UNSIGNED DEFAULT 0,
                                link varchar(400) DEFAULT '',
                                createAt int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS likes (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                toUserId int(11) UNSIGNED DEFAULT 0,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								itemId int(11) UNSIGNED DEFAULT 0,
                                likeType int(11) UNSIGNED NOT NULL DEFAULT 0,
                                notifyId int(11) UNSIGNED DEFAULT 0,
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS comments_likes (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                toUserId int(11) UNSIGNED DEFAULT 0,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								itemId int(11) UNSIGNED DEFAULT 0,
                                notifyId int(11) UNSIGNED DEFAULT 0,
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS notifications (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								notifyToId int(11) UNSIGNED NOT NULL DEFAULT 0,
								notifyFromId int(11) UNSIGNED NOT NULL DEFAULT 0,
								notifyType int(11) UNSIGNED NOT NULL DEFAULT 0,
                                notifySubType int(11) UNSIGNED NOT NULL DEFAULT 0,
								postId int(11) UNSIGNED NOT NULL DEFAULT 0,
								createAt int(10) UNSIGNED DEFAULT 0,
								removeAt int(10) UNSIGNED DEFAULT 0,
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS access_data (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								accountId int(11) UNSIGNED NOT NULL,
								accessToken varchar(32) DEFAULT '',
								fcm_regId varchar(255) DEFAULT '',
								appType int(10) UNSIGNED DEFAULT 0,
								clientId int(11) UNSIGNED DEFAULT 0,
								lang CHAR(10) DEFAULT 'en',
								createAt int(10) UNSIGNED DEFAULT 0,
								removeAt int(10) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS restore_data (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								accountId int(11) UNSIGNED NOT NULL,
								hash varchar(32) DEFAULT '',
								email VARCHAR(64) NOT NULL DEFAULT '',
								clientId int(11) UNSIGNED DEFAULT 0,
								lang CHAR(10) DEFAULT 'en',
								createAt int(10) UNSIGNED DEFAULT 0,
								removeAt int(10) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS profile_followers (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								follower INT(11) UNSIGNED DEFAULT 0,
								follow_to INT(11) UNSIGNED DEFAULT 0,
								follow_type INT(11) UNSIGNED DEFAULT 0,
								create_at INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS friends_requests (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								follower INT(11) UNSIGNED DEFAULT 0,
								follow_to INT(11) UNSIGNED DEFAULT 0,
								follow_type INT(11) UNSIGNED DEFAULT 0,
								create_at INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS friends (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								friend INT(11) UNSIGNED DEFAULT 0,
								friendTo INT(11) UNSIGNED DEFAULT 0,
								friendType INT(11) UNSIGNED DEFAULT 0,
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS profile_blacklist (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								blockedByUserId INT(11) UNSIGNED DEFAULT 0,
								blockedUserId INT(11) UNSIGNED DEFAULT 0,
								reason varchar(400) DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gcm_history (
								  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								  msg VARCHAR(150) NOT NULL DEFAULT '',
								  msgType INT(10) UNSIGNED DEFAULT 0,
								  accountId int(11) UNSIGNED DEFAULT 0,
								  status INT(10) UNSIGNED DEFAULT 0,
								  success INT(10) UNSIGNED DEFAULT 0,
								  createAt int(10) UNSIGNED DEFAULT 0,
  								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS hashtags (
								  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								  hashtag VARCHAR(150) NOT NULL DEFAULT '',
								  numberOfClicks int(11) UNSIGNED DEFAULT 0,
								  createAt int(10) UNSIGNED DEFAULT 0,
  								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gallery (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								itemType int(11) UNSIGNED DEFAULT 0,
								accessMode int(11) UNSIGNED DEFAULT 0,
								itemShowInStream int(11) UNSIGNED DEFAULT 1,
								likesCount int(11) UNSIGNED DEFAULT 0,
								commentsCount int(11) UNSIGNED DEFAULT 0,
								rating int(11) UNSIGNED DEFAULT 0,
								comment varchar(400) DEFAULT '',
								moderatedId int(11) UNSIGNED DEFAULT 0,
								moderatedAt int(11) UNSIGNED DEFAULT 0,
								originImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewVideoImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								videoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								area VARCHAR(150) NOT NULL DEFAULT '',
								country VARCHAR(150) NOT NULL DEFAULT '',
								city VARCHAR(150) NOT NULL DEFAULT '',
								lat float(10,6),
								lng float(10,6),
								u_agent varchar(300) DEFAULT '',
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								moderateAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gallery_comments (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								replyToUserId int(11) UNSIGNED DEFAULT 0,
								likesCount int(11) UNSIGNED DEFAULT 0,
								itemId int(11) UNSIGNED DEFAULT 0,
                                comment varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                commentOriginImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                commentNormalImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
                                createAt int(11) UNSIGNED DEFAULT 0,
                                removeAt int(11) UNSIGNED DEFAULT 0,
                                notifyId int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gallery_likes (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                toUserId int(11) UNSIGNED DEFAULT 0,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								itemId int(11) UNSIGNED DEFAULT 0,
                                likeType int(11) UNSIGNED NOT NULL DEFAULT 0,
                                notifyId int(11) UNSIGNED DEFAULT 0,
								createAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS reports (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								itemType INT(11) UNSIGNED DEFAULT 0,
								fromUserId INT(11) UNSIGNED DEFAULT 0,
								toUserId INT(11) UNSIGNED DEFAULT 0,
								itemId INT(11) UNSIGNED DEFAULT 0,
								abuseId INT(11) UNSIGNED DEFAULT 0,
								description varchar(300) DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt int(10) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();


        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS feelings_data (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								title varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS stickers_data (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								cost INT(11) UNSIGNED DEFAULT 0,
								category INT(11) UNSIGNED DEFAULT 0,
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gifts_data (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								cost INT(11) UNSIGNED DEFAULT 0,
								category INT(11) UNSIGNED DEFAULT 0,
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gifts (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								giftId INT(11) UNSIGNED DEFAULT 0,
								giftTo INT(11) UNSIGNED DEFAULT 0,
								giftFrom INT(11) UNSIGNED DEFAULT 0,
								giftAnonymous INT(11) UNSIGNED DEFAULT 0,
								message varchar(400) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS guests (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								guestId INT(11) UNSIGNED DEFAULT 0,
								guestTo INT(11) UNSIGNED DEFAULT 0,
                                times INT(11) UNSIGNED DEFAULT 0,
                                lastVisitAt INT(11) UNSIGNED DEFAULT 0,
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS stoplist (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								byAdminId INT(11) UNSIGNED DEFAULT 0,
								reason varchar(400) DEFAULT '',
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS market_items (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								allowComments int(11) UNSIGNED DEFAULT 1,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								category int(11) UNSIGNED DEFAULT 0,
								categoryTitle varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								price int(11) UNSIGNED DEFAULT 0,
								likesCount int(11) UNSIGNED DEFAULT 0,
								imagesCount int(11) UNSIGNED DEFAULT 0,
								viewsCount int(11) UNSIGNED DEFAULT 0,
								reviewsCount int(11) UNSIGNED DEFAULT 0,
								reportsCount int(11) UNSIGNED DEFAULT 0,
								commentsCount int(11) UNSIGNED DEFAULT 0,
								rating int(11) UNSIGNED DEFAULT 0,
								itemType int(11) UNSIGNED DEFAULT 0,
								itemTitle varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								itemDesc varchar(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								itemContent VARCHAR(800) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								imgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewGifUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								gifUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								previewVideoImgUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								videoUrl VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT '',
								area VARCHAR(150) NOT NULL DEFAULT '',
								country VARCHAR(150) NOT NULL DEFAULT '',
								city VARCHAR(150) NOT NULL DEFAULT '',
								lat float(10,6),
								lng float(10,6),
								moderatedId int(11) UNSIGNED DEFAULT 0,
								moderatedAt int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								createAt int(11) UNSIGNED DEFAULT 0,
								modifyAt int(11) UNSIGNED DEFAULT 0,
								removeAt int(11) UNSIGNED DEFAULT 0,
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS payments (
								id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId int(11) UNSIGNED DEFAULT 0,
								paymentAction INT(11) UNSIGNED DEFAULT 0,
								paymentType INT(11) UNSIGNED DEFAULT 0, 
								credits INT(11) UNSIGNED DEFAULT 0,
								amount INT(11) UNSIGNED DEFAULT 0,
								currency INT(11) UNSIGNED DEFAULT 0,
								createAt INT(11) UNSIGNED DEFAULT 0,
								removeAt INT(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS promo_messages (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								mode INT(10) UNSIGNED DEFAULT 0,
                                title VARCHAR(255) NOT NULL DEFAULT '',
                                url VARCHAR(255) NOT NULL DEFAULT '',    
								message VARCHAR(1800) NOT NULL DEFAULT '',
                                link VARCHAR(255) NOT NULL DEFAULT '',
                                link_clicks INT(10) UNSIGNED DEFAULT 0,
                                createAt int(11) UNSIGNED DEFAULT 0,
                                removeAt int(11) UNSIGNED DEFAULT 0,
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
        $sth->execute();

        $sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS agora (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								fromUserId INT(11) UNSIGNED DEFAULT 0,
								toUserId INT(11) UNSIGNED DEFAULT 0,
                                channel VARCHAR(64) NOT NULL DEFAULT '',
                                callType INT(6) UNSIGNED DEFAULT 0,
								callStatus int(6) UNSIGNED DEFAULT 0,
                                callTime int(6) UNSIGNED DEFAULT 0,
								createAt int(10) UNSIGNED DEFAULT 0,
								removeAt int(10) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci");
        $sth->execute();

	} catch (Exception $e) {

		die ($e->getMessage());
	}
