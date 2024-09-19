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

// If timezone is not installed on the server

if (!defined("APP_SIGNATURE")) {

    header("Location: /");
    exit;
}

if (!ini_get('date.timezone')) {

    date_default_timezone_set('Europe/London'); // Please set you timezone identifier, see here: http://php.net/manual/en/timezones.php
}

include_once("../sys/config/db.inc.php");
include_once("../sys/config/constants.inc.php");
include_once("../sys/config/payments.inc.php");
include_once("../sys/config/lang.inc.php");

foreach ($C as $name => $val) {

    define($name, $val);
}

foreach ($B as $name => $val) {

    define($name, $val);
}

$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;

if (EMOJI_SUPPORT) {

    $dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));

} else {

    $dbo = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}

spl_autoload_register(function($class)
{
    $filename = "../sys/class/class.".$class.".inc.php";

    if (file_exists($filename)) {

        include_once($filename);
    }
});

if(!isset($_SESSION)) {

    ini_set('session.cookie_domain', '.'.APP_HOST);
    session_set_cookie_params(0, '/', '.'.APP_HOST);
}

$helper = new helper($dbo);
$auth = new auth($dbo);
