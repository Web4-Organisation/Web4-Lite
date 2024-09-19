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

require_once '../sys/addons/vendor/autoload.php';

use Google\Cloud\Vision\V1\Feature\Type;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Likelihood;

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $imgType = isset($_POST['imgType']) ? $_POST['imgType'] : 0;
    $groupId = isset($_POST['groupId']) ? $_POST['groupId'] : 0;

    $groupId = helper::clearInt($groupId);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "error_description" => '');

    $error = false;
    $error_code = ERROR_UNKNOWN;
    $error_description = "";

    if (!empty($_FILES['uploaded_file']['name'])) {

        switch ($_FILES['uploaded_file']['error']) {

            case UPLOAD_ERR_OK:

                break;

            case UPLOAD_ERR_NO_FILE:

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'No file sent.'; // No file sent.

                break;

            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = "Exceeded file size limit.";

                break;

            default:

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'Unknown error.';
        }

        $imglib = new imglib($dbo);

        if (!$error && !$imglib->isImageFile($_FILES['uploaded_file']['tmp_name'], true, false)) {

            $error = true;
            $error_code = ERROR_UNKNOWN;
            $error_description = 'Error file format';
        }

        if (!$error) {

            $settings = new settings($dbo);
            $settings_result = $settings->get();
            unset($settings);

            if ($settings_result['gcv_adult']['intValue'] != 0 || $settings_result['gcv_violence']['intValue'] != 0 || $settings_result['gcv_racy']['intValue'] != 0) {

                try {

                    $jsonFileName = "";

                    if ($files = glob("js/firebase/*.json")) {

                        $jsonFileName = $files[0];
                    }

                    $client = new ImageAnnotatorClient([

                        'credentials' => $jsonFileName
                    ]);

                    $image = file_get_contents($_FILES['uploaded_file']['tmp_name']);

                    $response = $client->safeSearchDetection($image);

                    $safe = $response->getSafeSearchAnnotation();

                    //$adult = $safe->getAdult();
                    //$medical = $safe->getMedical();
                    //$spoof = $safe->getSpoof();
                    //$violence = $safe->getViolence();
                    //$racy = $safe->getRacy();

                    if ($settings_result['gcv_adult']['intValue'] != 0) {

                        if ($safe->getAdult() >= $settings_result['gcv_adult']['intValue']) {

                            $error = true;
                            $error_code = ERROR_IMAGE_FILE_ADULT;
                            $error_description = 'Adult image detected. Choose another picture.';
                        }
                    }

                    if (!$error && $settings_result['gcv_violence']['intValue'] != 0) {

                        if ($safe->getViolence() >= $settings_result['gcv_violence']['intValue']) {

                            $error = true;
                            $error_code = ERROR_IMAGE_FILE_VIOLENCE;
                            $error_description = 'Violence image detected. Choose another picture.';
                        }
                    }

                    if (!$error && $settings_result['gcv_racy']['intValue'] != 0) {

                        if ($safe->getRacy() >= $settings_result['gcv_racy']['intValue']) {

                            $error = true;
                            $error_code = ERROR_IMAGE_FILE_RACY;
                            $error_description = 'Racy image detected. Choose another picture.';
                        }
                    }

                    $client->close();

                } catch (\Google\ApiCore\ValidationException $e) {

                    $error = false;

                } catch (Exception $e) {

                    $error = false;
                }
            }
        }

        if (!$error) {

            $imgFilename_ext = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
            $imgNewName = helper::generateHash(7);

//            $imglib->img_resize($_FILES['uploaded_file']['tmp_name'], TEMP_PATH.$imgNewName.".".$imgFilename_ext, 800, 0);

            move_uploaded_file($_FILES['uploaded_file']['tmp_name'], TEMP_PATH.$imgNewName.".".$imgFilename_ext);

            switch ($imgType) {

                case IMAGE_TYPE_PROFILE_PHOTO: {

                    $result = $imglib->newProfilePhoto(TEMP_PATH.$imgNewName.".".$imgFilename_ext);

                    if (!$result['error']) {

                        if ($groupId != 0) {

                            // community

                            $account = new account($dbo, $groupId);
                            $account->setPhoto($result);
                            unset($account);

                            $moderator = new moderator($dbo);
                            $moderator->postPhoto($groupId, $result['originPhotoUrl']);
                            unset($moderator);

                        } else {

                            // user profile

                            $account = new account($dbo, $accountId);
                            $account->setPhoto($result);
                            unset($account);

                            $moderator = new moderator($dbo);
                            $moderator->postPhoto($accountId, $result['originPhotoUrl']);
                            unset($moderator);

                            if (auth::isSession()) {

                                auth::setCurrentUserPhotoUrl($result['normalPhotoUrl']);
                            }
                        }
                    }

                    break;
                }

                case IMAGE_TYPE_PROFILE_COVER: {

                    $result = $imglib->newProfileCover(TEMP_PATH.$imgNewName.".".$imgFilename_ext);

                    if (!$result['error']) {

                        $account = new account($dbo, $accountId);
                        $account->setCover($result);
                        unset($account);

                        $moderator = new moderator($dbo);
                        $moderator->postCover($accountId, $result['originCoverUrl']);
                        unset($moderator);
                    }

                    break;
                }

                default: {

                    break;
                }
            }

            $result['error'] = false;
            $result['error_code'] = ERROR_SUCCESS;
            $result['error_description'] = "ok.";

        } else {

            $result['error'] = $error;
            $result['error_code'] = $error_code;
            $result['error_description'] = $error_description;
        }

        unset($imglib);
    }

    echo json_encode($result);
}
