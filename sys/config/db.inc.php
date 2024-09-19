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

$C = array();
$B = array();

$B['APP_DEMO'] = false;                                     //true = enable demo version mode (only Admin panel)
$B['WEB_EXPLORE'] = true;                                   //true = allow see the stream of posts and use the search functions for unregistered users

$B['SITE_THEME'] = "my-network";                            //Color Styles look here: http://materializecss.com/color.html
$B['SITE_TEXT_COLOR'] = "red-text text-darken-1";           //For menu items, icons and etc.. Color Styles look here: http://materializecss.com/color.html

$B['FACEBOOK_AUTHORIZATION'] = true;                        //false = Do not show buttons Login/Signup with Facebook | true = allow display buttons Login/Signup with Facebook

$B['APP_MESSAGES_COUNTERS'] = true;                         //true = show new messages counters

$B['APP_USE_CLIENT_SECRET'] = true;                         //true = protection against bot registration using hash generation is enabled

// DO NOT CHANGE. It does not affect the work applications and website

$C['COMPANY_URL'] = "http://linkspreed.com/";
$B['APP_SUPPORT_EMAIL'] = "hello@web4.one";
$B['APP_AUTHOR_PAGE'] = "Linkspreed";
$B['APP_PATH'] = "app";
$B['APP_VERSION'] = "1";
$B['APP_AUTHOR'] = "Linkspreed UG";
$B['APP_VENDOR'] = "https://web4.one";

// Paths to folders for storing images and videos. Do not change!

$B['MY_PHOTOS_PATH'] = "galery/";                       //don`t edit this option
$B['PHOTO_PATH'] = "photo/";                            //don`t edit this option
$B['POST_PHOTO_PATH'] = "post/";                        //don`t edit this option
$B['COVER_PATH'] = "cover/";                            //don`t edit this option
$B['GIFTS_PATH'] = "gifts/";                            //don`t edit this option
$B['VIDEO_PATH'] = "video/";                            //don`t edit this option
$B['VIDEO_IMAGE_PATH'] = "video_images/";               //don`t edit this option
$B['TEMP_PATH'] = "tmp/";                               //don`t edit this option
$B['CHAT_IMAGE_PATH'] = "chat_images/";                 //don`t edit this option
$B['MARKET_IMAGE_PATH'] = "market/";                    //don`t edit this option
$B['GALLERY_PATH'] = "gallery/";                        //don`t edit this option

// Data for the title of the website and copyright

$B['APP_NAME'] = "My Web4 Network";                   //
$B['APP_TITLE'] = "My Web4 Network";                  //
$B['APP_YEAR'] = "2024";                              // Year in footer

// Your domain (host) and url! See comments! Carefully!

$B['APP_HOST'] = "localhost";                 //edit to your domain, example (WARNING - without http://, https:// and www): yourdomain.com
$B['APP_URL'] = "http://localhost";           //edit to your domain url, example (WARNING - with http:// or https://): https://yourdomain.com

// Top header custom link near logo

$B['TOP_HEADER_LINK_HREF'] = "/search/hashtag?src=stayhome"; // link url. set to "" empty and link will be deleted from header
$B['TOP_HEADER_LINK_TITLE'] = "#stayhome";                   // link title text when set cursor
$B['TOP_HEADER_LINK_TEXT'] = "#stayhome";                    // link visible text
$B['TOP_HEADER_LINK_NEW_WINDOW'] = false;                    // true = open link in new tab (window)

// Emoji support

$B['EMOJI_SUPPORT'] = false; // set to true if you mysql version > 5.5.3

// Link to GOOGLE Play App in main page

$B['GOOGLE_PLAY_LINK'] = "";

// Client ID. For more information, see the documentation, FAQ section
$B['CLIENT_ID'] = 1; //Client ID | For identify mobile application | Example: 12567 (see documentation. section: faq)

// Client Secret. For generate hashes
$B['CLIENT_SECRET'] = "f*Hk86&_Hrfv7cjnf-I=yT"; // Characters. Must be the same in the app config (Constants.java or/and Constants.swift) and in this config (db.inc.php) | Example: f*Hk86&_Hrfv7cjnf-I=yT

// Stripe settings | Settings from Cross-Platform Mobile Payments | See documentation

$B['STRIPE_PUBLISHABLE_KEY'] = "";
$B['STRIPE_SECRET_KEY'] = "";

// Facebook settings | For login/signup with facebook | https://docs.web4.one/

$B['FACEBOOK_APP_ID'] = "";
$B['FACEBOOK_APP_SECRET'] = "";

// Google OAuth client |

$B['GOOGLE_CLIENT_ID'] = "";
$B['GOOGLE_CLIENT_SECRET'] = "";

// Google settings | For sending FCM (Firebase Cloud Messages) | https://docs.web4.one/
$B['GOOGLE_API_KEY'] = "";
$B['GOOGLE_SENDER_ID'] = "";

$B['FIREBASE_API_KEY'] = $B['GOOGLE_API_KEY'];
$B['FIREBASE_SENDER_ID'] = $B['GOOGLE_SENDER_ID'];
$B['FIREBASE_PROJECT_ID'] = "";

// Recaptcha settings | Create you keys for reCAPTCHA v3 | https://www.google.com/recaptcha/admin/create

$B['RECAPTCHA_SITE_KEY'] = "";
$B['RECAPTCHA_SECRET_KEY'] = "";

// SMTP Settings | For password recovery | Data for SMTP can ask your hosting provider |

$B['SMTP_HOST'] = '';                                       //SMTP host | Specify main and backup SMTP servers
$B['SMTP_AUTH'] = true;                                     //SMTP auth (Enable SMTP authentication)
$B['SMTP_SECURE'] = 'ssl';                                  //SMTP secure (Enable TLS encryption, `ssl` also accepted)
$B['SMTP_PORT'] = 465;                                      //SMTP port (TCP port to connect to)
$B['SMTP_EMAIL'] = '';                                      //SMTP email
$B['SMTP_USERNAME'] = '';                                   //SMTP username
$B['SMTP_PASSWORD'] = '';                                   //SMTP password

//Please edit database data

$C['DB_HOST'] = "";                                         //localhost or your db host
$C['DB_USER'] = "";                                         //your db user
$C['DB_PASS'] = "";                                         //your db password
$C['DB_NAME'] = "";                                         //your db name



$C['ERROR_SUCCESS'] = 0;

$C['ERROR_UNKNOWN'] = 100;
$C['ERROR_ACCESS_TOKEN'] = 101;

$C['ERROR_LOGIN_TAKEN'] = 300;
$C['ERROR_EMAIL_TAKEN'] = 301;
$C['ERROR_FACEBOOK_ID_TAKEN'] = 302;

$C['ERROR_ACCOUNT_ID'] = 400;

$C['DISABLE_LIKES_GCM'] = 0;
$C['ENABLE_LIKES_GCM'] = 1;

$C['DISABLE_COMMENTS_GCM'] = 0;
$C['ENABLE_COMMENTS_GCM'] = 1;

$C['DISABLE_FOLLOWERS_GCM'] = 0;
$C['ENABLE_FOLLOWERS_GCM'] = 1;

$C['DISABLE_MESSAGES_GCM'] = 0;
$C['ENABLE_MESSAGES_GCM'] = 1;

$C['DISABLE_GIFTS_GCM'] = 0;
$C['ENABLE_GIFTS_GCM'] = 1;

$C['SEX_UNKNOWN'] = 0;
$C['SEX_MALE'] = 1;
$C['SEX_FEMALE'] = 2;

$C['USER_CREATED_SUCCESSFULLY'] = 0;
$C['USER_CREATE_FAILED'] = 1;
$C['USER_ALREADY_EXISTED'] = 2;
$C['USER_BLOCKED'] = 3;
$C['USER_NOT_FOUND'] = 4;
$C['USER_LOGIN_SUCCESSFULLY'] = 5;
$C['EMPTY_DATA'] = 6;
$C['ERROR_API_KEY'] = 7;

$C['NOTIFY_TYPE_LIKE'] = 0;
$C['NOTIFY_TYPE_FOLLOWER'] = 1;
$C['NOTIFY_TYPE_MESSAGE'] = 2;
$C['NOTIFY_TYPE_COMMENT'] = 3;
$C['NOTIFY_TYPE_COMMENT_REPLY'] = 4;
$C['NOTIFY_TYPE_FRIEND_REQUEST_ACCEPTED'] = 5;
$C['NOTIFY_TYPE_GIFT'] = 6;

$C['NOTIFY_TYPE_IMAGE_COMMENT'] = 7;
$C['NOTIFY_TYPE_IMAGE_COMMENT_REPLY'] = 8;
$C['NOTIFY_TYPE_IMAGE_LIKE'] = 9;

$C['NOTIFY_TYPE_VIDEO_COMMENT'] = 10;
$C['NOTIFY_TYPE_VIDEO_COMMENT_REPLY'] = 11;
$C['NOTIFY_TYPE_VIDEO_LIKE'] = 12;

$C['NOTIFY_TYPE_REFERRAL'] = 14;

$C['GCM_NOTIFY_CONFIG'] = 0;
$C['GCM_NOTIFY_SYSTEM'] = 1;
$C['GCM_NOTIFY_CUSTOM'] = 2;
$C['GCM_NOTIFY_LIKE'] = 3;
$C['GCM_NOTIFY_ANSWER'] = 4;
$C['GCM_NOTIFY_QUESTION'] = 5;
$C['GCM_NOTIFY_COMMENT'] = 6;
$C['GCM_NOTIFY_FOLLOWER'] = 7;
$C['GCM_NOTIFY_PERSONAL'] = 8;
$C['GCM_NOTIFY_MESSAGE'] = 9;
$C['GCM_NOTIFY_COMMENT_REPLY'] = 10;
$C['GCM_FRIEND_REQUEST_INBOX'] = 11;
$C['GCM_FRIEND_REQUEST_ACCEPTED'] = 12;
$C['GCM_NOTIFY_GIFT'] = 14;
$C['GCM_NOTIFY_SEEN'] = 15;
$C['GCM_NOTIFY_TYPING'] = 16;
$C['GCM_NOTIFY_URL'] = 17;

$C['GCM_NOTIFY_IMAGE_COMMENT_REPLY'] = 18;
$C['GCM_NOTIFY_IMAGE_COMMENT'] = 19;
$C['GCM_NOTIFY_IMAGE_LIKE'] = 20;

$C['GCM_NOTIFY_VIDEO_COMMENT_REPLY'] = 21;
$C['GCM_NOTIFY_VIDEO_COMMENT'] = 22;
$C['GCM_NOTIFY_VIDEO_LIKE'] = 23;

$C['GCM_NOTIFY_REFERRAL'] = 24;

$C['GCM_NOTIFY_MATCH'] = 25;

$C['ACCOUNT_STATE_ENABLED'] = 0;
$C['ACCOUNT_STATE_DISABLED'] = 1;
$C['ACCOUNT_STATE_BLOCKED'] = 2;
$C['ACCOUNT_STATE_DEACTIVATED'] = 3;

$C['ACCOUNT_TYPE_USER'] = 0;
$C['ACCOUNT_TYPE_GROUP'] = 1;
$C['ACCOUNT_TYPE_PAGE'] = 2;

$C['POST_TYPE_DEFAULT'] = 0;
$C['POST_TYPE_PHOTO_UPDATE'] = 1;
$C['POST_TYPE_COVER_UPDATE'] = 2;

// Languages

$LANGS = array();
$LANGS['English'] = "en";

