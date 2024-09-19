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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon.png">
    <title><?php echo $page_title; ?></title>

    <link href="/admin/css/bootstrap.css?x=1" rel="stylesheet">

<!--    <link href="/admin/css/chartist.css?x=1" rel="stylesheet">-->
<!--    <link href="/admin/css/chartist-init.css" rel="stylesheet">-->
<!--    <link href="/admin/css/chartist-plugin-tooltip.css?x=1" rel="stylesheet">-->

<!--    <link href="/admin/css/c3.min.css" rel="stylesheet">-->


    <link href="/admin/css/style.css?x=4" rel="stylesheet">

    <link href="/admin/css/colors/blue.css" id="theme" rel="stylesheet">

    <?php
        foreach($css_files as $css): ?>
            <link rel="stylesheet" href="/admin/css/<?php echo $css."?x=6"; ?>" type="text/css" media="screen">
            <?php
        endforeach;
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>