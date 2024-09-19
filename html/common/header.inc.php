<!DOCTYPE html>
<html lang="<?php echo $LANG['lang-code']; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $page_title; ?></title>
    <meta name="google-site-verification" content="" />
    <meta name='yandex-verification' content='' />
    <meta name="msvalidate.01" content="" />
    <meta property="og:site_name" content="<?php echo APP_TITLE; ?>">
    <meta property="og:title" content="<?php echo $page_title; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <?php

        if (isset($page_id)) {

            switch ($page_id) {

                case "profile": {

                    ?>

                    <meta property="og:url" content="<?php echo APP_URL."/".$profileInfo['username']; ?>" />
                    <meta property="og:image" content="<?php echo $profilePhotoUrl; ?>" />
                    <meta property="og:title" content="<?php echo $profileInfo['fullname']; ?>" />
                    <meta property="og:description" content="@<?php echo $profileInfo['username']; ?>" />

                    <?php

                    break;
                }

                case "main": {

                    ?>

                    <meta property="og:url" content="<?php echo APP_URL; ?>" />
                    <meta property="og:image" content="<?php echo APP_URL."/img/panel_logo.png"; ?>" />
                    <meta property="og:title" content="<?php echo APP_TITLE; ?>" />
                    <meta property="og:description" content="Create your own <?php echo APP_NAME; ?> App now!" />

                    <?php

                    break;
                }

                default: {

                    break;
                }
            }
        }
    ?>
    <meta charset="utf-8">
    <meta name="description" content="">
    <link href="/img/favicon.png" rel="shortcut icon" type="image/x-icon">
    <?php
        foreach($css_files as $css): ?>
        <link rel="stylesheet" href="/css/<?php echo $css."?x=5"; ?>" type="text/css" media="screen">
    <?php
        endforeach;
    ?>
    <link rel="stylesheet" href="/css/blueimp-gallery.min.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/colorbox.css?x1" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/drawer.css?x1" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/icofont.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/fontawesome.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/bootstrap-grid.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/bootstrap.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/bootstrap-slider.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/css/main.css?x66" type="text/css" media="screen">

    <?php

        $primary_theme = "/css/root.css";

        if (isset($_COOKIE['theme-night'])) {

            if ($_COOKIE['theme-night'] === "true") {

                $primary_theme = "/css/root-night.css";
            }
        }
    ?>

    <link rel="stylesheet" href="<?php echo $primary_theme; ?>" type="text/css" media="screen" id="theme-css">

        <?php

            if (isset($page_id) && $page_id === "signup" || isset($page_id) && $page_id === "main" || isset($page_id) && $page_id === "support" || isset($page_id) && $page_id === "restore") {

                ?>
                    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY; ?>"></script>
                    <script src="https://apis.google.com/js/platform.js" async defer></script>
                <?php
            }

            if (isset($page_id) && $page_id === "settings_otp" || isset($page_id) && $page_id === "settings_services" || isset($page_id) && $page_id === "settings_deactivation") {

                ?>
                    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo RECAPTCHA_SITE_KEY; ?>"></script>

                    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
                    <script src="https://www.gstatic.com/firebasejs/8.8.1/firebase-app.js"></script>

                    <!-- Add Firebase products that you want to use -->
                    <script src="https://www.gstatic.com/firebasejs/8.8.1/firebase-auth.js"></script>

                <?php
            }
        ?>
</head>