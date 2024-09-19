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

session_start();

error_reporting(E_ALL);

define("APP_SIGNATURE", "Web4_Lite"); // Add signature constant to protect include modules

include_once("../sys/core/init.inc.php");

$page_id = '';

// Auto authorize if installed cookie

if (!auth::isSession() && isset($_COOKIE['user_name']) && isset($_COOKIE['user_password'])) {

    $account = new account($dbo, $helper->getUserId($_COOKIE['user_name']));

    $accountInfo = $account->get();

    if ($accountInfo['error'] === false && $accountInfo['state'] == ACCOUNT_STATE_ENABLED) {

        $auth = new auth($dbo);

        if ($auth->authorize($accountInfo['id'], $_COOKIE['user_password'])) {

            auth::setSession($accountInfo['id'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $_COOKIE['user_password']);
            auth::setCurrentUserOtpVerified($accountInfo['otpVerified']);

            $_SESSION['lat'] = $accountInfo['lat'];
            $_SESSION['lng'] = $accountInfo['lng'];

            $account->setLastActive();

        } else {

            auth::clearCookie();
        }

    } else {

        auth::clearCookie();
    }
}

if (!empty($_GET)) {

    if (!isset($_GET['q'])) {

        include_once("../html/main.inc.php");
        exit;
    }

    $request = htmlentities($_GET['q'], ENT_QUOTES);
    $request = helper::escapeText($request);
    $request = explode('/', trim($request, '/'));

    $cnt = count($request);

	switch ($cnt) {

		case 0: {

			include_once("../html/main.inc.php");
			exit;
		}

		case 1: {

			if (file_exists("../html/page.".$request[0].".inc.php")) {

				include_once("../html/page.".$request[0].".inc.php");
				exit;

			}  else if ($helper->isLoginExists($request[0])) {

				include_once("../html/profile.inc.php");
				exit;

			} else {

				include_once("../html/error.inc.php");
				exit;
			}
		}

		case 2: {

			if (file_exists( "../html/".$request[0]."/page.".$request[1].".inc.php")) {

				include_once("../html/" . $request[0] . "/page." . $request[1] . ".inc.php");
				exit;

			} else if (file_exists("../html/app/".$request[1].".php")) {

                include_once("../html/app/" . $request[1] . ".php");
                exit;

            } else if ($helper->isLoginExists($request[0])) {

                if (file_exists("../html/profile/page." . $request[1] . ".inc.php")) {

                    include_once("../html/profile/page." . $request[1] . ".inc.php");
                    exit;

                } else {

                    include_once("../html/error.inc.php");
                    exit;
                }

			} else {

				include_once("../html/error.inc.php");
				exit;
			}
		}

		case 3: {

			switch ($request[1]) {

                case 'market': {

                    if ($helper->isLoginExists($request[0])) {

                        include_once("../html/market/page.show.inc.php");
                        exit;
                    }

                    break;
                }

				case 'post': {

                    if ($helper->isLoginExists($request[0])) {

                        include_once("../html/posts/page.show.inc.php");
                        exit;
                    }

                    break;
				}

                case 'image': {

                    if ($helper->isLoginExists($request[0])) {

                        include_once("../html/images/page.show.inc.php");
                        exit;
                    }

                    break;
                }

                default: {

                    if (file_exists("../html/".$request[0]."/".$request[1]."/page.".$request[2].".inc.php")) {

                        include_once("../html/".$request[0]."/".$request[1]."/page.".$request[2].".inc.php");
                        exit;

                    } else {

                        include_once("../html/error.inc.php");
                        exit;
                    }

                    break;
                }
			}
		}

		case 4: {

            switch ($request[0]) {

                case 'api': {

                    if (file_exists("../app/".$request[1]."/method/".$request[3].".inc.php")) {

                        include_once("../sys/config/api.inc.php");

                        include_once("../app/".$request[1]."/method/".$request[3].".inc.php");
                        exit;

                    } else if (file_exists("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php")) {

                        include_once("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php");
                        exit;

                    } else {

                        include_once("../html/error.inc.php");
                        exit;
                    }

                    break;
                }

                default: {

                    if ($helper->isLoginExists($request[0])) {

                        switch ($request[1]) {

                            case 'post' : {

                                if (file_exists("../html/posts/page.".$request[3].".inc.php")) {

                                    include_once("../html/posts/page.".$request[3].".inc.php");
                                    exit;

                                } else {

                                    include_once("../html/error.inc.php");
                                    exit;
                                }

                                break;
                            }

                            case 'image' : {

                                if (file_exists("../html/images/page.".$request[3].".inc.php")) {

                                    include_once("../html/images/page.".$request[3].".inc.php");
                                    exit;

                                } else {

                                    include_once("../html/error.inc.php");
                                    exit;
                                }

                                break;
                            }

                            case 'photo' : {

                                if (file_exists("../html/photo/page.".$request[3].".inc.php")) {

                                    include_once("../html/photo/page.".$request[3].".inc.php");
                                    exit;

                                } else {

                                    include_once("../html/error.inc.php");
                                    exit;
                                }

                                break;
                            }

                            default: {

                                include_once("../html/error.inc.php");
                                exit;
                            }
                        }

                    } else {

                        if ( file_exists("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php") ) {

                            include_once("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php");
                            exit;

                        } else {

                            include_once("../html/error.inc.php");
                            exit;
                        }
                    }

                    break;
                }
            }
		}

		default: {

			include_once("../html/error.inc.php");
			exit;
		}
	}

} else {

	$request = array();
	include_once("../html/main.inc.php");
	exit;
}
