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

$B['GOOGLE_RECAPTCHA_WEB'] = true; //false = Do not use Google reCaptcha V3 to Login and Register on site / set it for "false" for localhost (xampp) testing without internet connection

$B['GOOGLE_AUTHORIZATION'] = true; //false = Do not show buttons Login/Signup with Google | true = allow display buttons Login/Signup with Google
$B['APPLE_AUTHORIZATION'] = false; //false = Do not show buttons Login/Signup with Apple | true = allow display buttons Login/Signup with Apple

$C['PAGE_ANY'] = 0;
$C['PAGE_PROFILE'] = 1;

$C['SECTION_ANY'] = $C['PAGE_ANY'];
$C['SECTION_PROFILE'] = $C['PAGE_PROFILE'];

//

$C['FILE_IMAGE_MAX_UPLOAD_SIZE'] = 3145728;
$C['FILE_VIDEO_MAX_UPLOAD_SIZE'] = 97340035;

$C['BONUS_SIGNUP'] = 10; // bonus for registration in credits
$C['BONUS_REFERRAL'] = 5; // bonus for registration referral in credits
$C['BONUS_OTP_VERIFICATION'] = 100; // bonus for linking mobile phone number

$C['UPGRADES_GHOST_MODE_COST'] = 150;
$C['UPGRADES_VERIFIED_BADGE_COST'] = 100;
$C['UPGRADES_DISABLE_ADS_COST'] = 200;

$C['POST_MAX_IMAGES_COUNT'] = 7;
$C['API_VERSION'] = "v2";

$C['GCM_NOTIFY_PROFILE_PHOTO_APPROVE'] = 1003;
$C['GCM_NOTIFY_PROFILE_PHOTO_REJECT'] = 1004;
$C['GCM_NOTIFY_PROFILE_COVER_APPROVE'] = 1007;
$C['GCM_NOTIFY_PROFILE_COVER_REJECT'] = 1008;

$C['GCM_NOTIFY_AGORA_VIDEO_CALL'] = 10001;

$C['NOTIFY_TYPE_PROFILE_PHOTO_APPROVE'] = 2003;
$C['NOTIFY_TYPE_PROFILE_PHOTO_REJECT'] = 2004;
$C['NOTIFY_TYPE_PROFILE_COVER_APPROVE'] = 2007;
$C['NOTIFY_TYPE_PROFILE_COVER_REJECT'] = 2008;

$C['IMAGE_TYPE_PROFILE_PHOTO'] = 0;
$C['IMAGE_TYPE_PROFILE_COVER'] = 1;

$C['APP_TYPE_ALL'] = -1;
$C['APP_TYPE_MANAGER'] = 0;
$C['APP_TYPE_WEB'] = 1;
$C['APP_TYPE_ANDROID'] = 2;
$C['APP_TYPE_IOS'] = 3;

$C['GALLERY_ITEM_TYPE_IMAGE'] = 0;
$C['GALLERY_ITEM_TYPE_VIDEO'] = 1;

$C['REPORT_TYPE_ITEM'] = 0;
$C['REPORT_TYPE_PROFILE'] = 1;
$C['REPORT_TYPE_MESSAGE'] = 2;
$C['REPORT_TYPE_COMMENT'] = 3;
$C['REPORT_TYPE_GALLERY_ITEM'] = 4;
$C['REPORT_TYPE_MARKET_ITEM'] = 5;
$C['REPORT_TYPE_COMMUNITY'] = 6;

$C['ITEM_TYPE_IMAGE'] = 0;
$C['ITEM_TYPE_VIDEO'] = 1;
$C['ITEM_TYPE_POST'] = 2;
$C['ITEM_TYPE_COMMENT'] = 3;
$C['ITEM_TYPE_GALLERY'] = 4;

$C['POST_TYPE_DEFAULT'] = 0;
$C['POST_TYPE_PHOTO_UPDATE'] = 1;
$C['POST_TYPE_COVER_UPDATE'] = 2;
$C['POST_TYPE_ALERT'] = 3;

// Payments

// PA - PAYMENT ACTION
$C['PA_BUY_CREDITS'] = 0;
$C['PA_BUY_GIFT'] = 1;
$C['PA_BUY_VERIFIED_BADGE'] = 2;
$C['PA_BUY_GHOST_MODE'] = 3;
$C['PA_BUY_DISABLE_ADS'] = 4;
$C['PA_BUY_REGISTRATION_BONUS'] = 5;
$C['PA_BUY_REFERRAL_BONUS'] = 6;
$C['PA_BUY_MANUAL_BONUS'] = 7;
$C['PA_BUY_PRO_MODE'] = 8;
$C['PA_BUY_SPOTLIGHT'] = 9;
$C['PA_BUY_MESSAGE_PACKAGE'] = 10;
$C['PA_BUY_OTP_VERIFICATION'] = 11;

// PT - PAYMENT TYPE
$C['PT_UNKNOWN'] = 0;
$C['PT_CREDITS'] = 1;
$C['PT_CARD'] = 2;
$C['PT_GOOGLE_PURCHASE'] = 3;
$C['PT_APPLE_PURCHASE'] = 4;
$C['PT_ADMOB_REWARDED_ADS'] = 5;
$C['PT_BONUS'] = 6;
$C['PT_STRIPE_PURCHASE'] = 7;

$C['CURRENCY_USD'] = 0;
$C['CURRENCY_EUR'] = 1;

// ERROR CODES

$C['ERROR_LOGIN_TAKEN'] = 300;
$C['ERROR_EMAIL_TAKEN'] = 301;
$C['ERROR_FACEBOOK_ID_TAKEN'] = 302;
$C['ERROR_PHONE_TAKEN'] = 303;
$C['ERROR_OAUTH_ID_TAKEN'] = 304;

$C['ERROR_ACCOUNT_ID'] = 400;

$C['ERROR_FILE_SIZE_BIG'] = 501;
$C['ERROR_FILE_SIZE_SMALL'] = 502;

$C['ERROR_IMAGE_FILE_FORMAT'] = 503;
$C['ERROR_IMAGE_FILE_WIDTH_HEIGHT'] = 504;
$C['ERROR_IMAGE_FILE_ADULT'] = 555;
$C['ERROR_IMAGE_FILE_VIOLENCE'] = 556;
$C['ERROR_IMAGE_FILE_RACY'] = 557;

$C['ERROR_CLIENT_ID'] = 19100;
$C['ERROR_CLIENT_SECRET'] = 19101;
$C['ERROR_RECAPTCHA'] = 19102;

$C['ERROR_OTP_VERIFICATION'] = 506;
$C['ERROR_OTP_PHONE_NUMBER_TAKEN'] = 507;

// Signin methods

$C['SIGNIN_EMAIL'] = 0;
$C['SIGNIN_OTP'] = 1;
$C['SIGNIN_FACEBOOK'] = 2;
$C['SIGNIN_GOOGLE'] = 3;
$C['SIGNIN_APPLE'] = 4;
$C['SIGNIN_TWITTER'] = 5;

$C['OAUTH_TYPE_FACEBOOK'] = 0;
$C['OAUTH_TYPE_GOOGLE'] = 1;
$C['OAUTH_TYPE_APPLE'] = 2;

// Verification

$C['ENVATO_ITEM_ID'] = 13965025; // My Social Network Android = 13965025

$C['ENVATO_ERROR_PCODE_UNKNOWN'] = 90000;
$C['ENVATO_ERROR_PCODE_INVALID'] = 90001;
$C['ENVATO_ERROR_PCODE_VERIFIED'] = 90002;
$C['ENVATO_ERROR_PCODE_REGISTERED'] = 90003;
$C['ENVATO_ERROR_PCODE_ILLEGAL'] = 90004;

// Video calls

$C['VIDEO_CALL_ACTIVE'] = 0;
$C['VIDEO_CALL_CANCELED'] = 10001;
$C['VIDEO_CALL_DECLINED'] = 10002;
$C['VIDEO_CALL_ENDED'] = 10003;