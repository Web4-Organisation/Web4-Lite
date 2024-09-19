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

    if (auth::isSession()) {

        header("Location: /account/wall");
        exit;
    }

    if (isset($_SESSION['oauth']) && $_SESSION['oauth'] === 'facebook') {

        header("Location: /signup");
        exit;
    }

    require_once '../sys/addons/vendor/autoload.php';

    $fb = new \Facebook\Facebook([
        'app_id' => FACEBOOK_APP_ID,
        'app_secret' => FACEBOOK_APP_SECRET,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);

    // login helper with redirect_uri
    $fb_helper = $fb->getRedirectLoginHelper();

    if (isset($_GET['state'])) {

        $fb_helper->getPersistentDataHandler()->set('state', $_GET['state']);
    }

    if (isset($_GET['code'])) {

        try {

            $accessToken = $fb_helper->getAccessToken();

            $response = $fb->get('/me', $accessToken);

            $me = $response->getGraphUser();

            $accountId = $helper->getUserIdByFacebook($me->getId());

            if ($accountId == 0) {

                // new user

                $oauth_name = "New Facebook User";
                $oauth_name = $me->getName();

                $oauth_email = "";

                if (!is_null($me->getEmail())) {

                    $oauth_email = $me->getEmail();
                }

                $account = new account($dbo);
                $account_info = $account->signupOauth(OAUTH_TYPE_FACEBOOK, $me->getId(), $oauth_name, $oauth_email);
                unset($account);

                if (!$account_info['error']) {

                    $accountId = $account_info['accountId'];
                }
            }

            if ($accountId != 0) {

                $account = new account($dbo, $accountId);
                $accountInfo = $account->get();

                if (!$accountInfo['error']) {

                    //user with fb id exists in db

                    if ($accountInfo['state'] == ACCOUNT_STATE_BLOCKED) {

                        header("Location: /");
                        exit;

                    } else if ($accountInfo['state'] == ACCOUNT_STATE_DISABLED) {

                        header("Location: /");
                        exit;

                    } else {

                        $account->setLastActive();

                        $clientId = 0; // Desktop version

                        $auth = new auth($dbo);
                        $access_data = $auth->create($accountId, $clientId, APP_TYPE_WEB, "", "");

                        if (!$access_data['error']) {

                            auth::setSession($access_data['accountId'], $accountInfo['username'], $accountInfo['fullname'], $accountInfo['lowPhotoUrl'], $accountInfo['verified'], $accountInfo['access_level'], $access_data['accessToken']);
                            auth::setCurrentUserAdmobFeature($accountInfo['admob']);
                            auth::setCurrentUserGhostFeature($accountInfo['ghost']);
                            auth::setCurrentUserOtpVerified($accountInfo['otpVerified']);
                            auth::updateCookie($accountInfo['username'], $access_data['accessToken']);

                            header("Location: /account/wall");
                        }
                    }
                }
            }

            exit;

        } catch (Facebook\Exceptions\FacebookClientException $e) {

            echo 'Client error: '.$e->getMessage();
            exit;

        } catch(Facebook\Exceptions\FacebookResponseException $e) {

            // When Graph returns an error
            echo 'Graph returned an error: '.$e->getMessage();
            exit;

        } catch(Facebook\Exceptions\FacebookSDKException $e) {

            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: '.$e->getMessage();
            exit;

        }  catch (Exception $e) {

            echo 'Generic error: '.$e->getMessage();
            exit;
        }

    } else {

        $loginUrl = $fb_helper->getLoginUrl(APP_URL."/facebook/login");
        header("Location: ".$loginUrl);
        exit;
    }