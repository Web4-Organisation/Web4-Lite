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

    $imgFileUrl = "";
    $videoFileUrl = "";

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN,
        "error_description" => '');

    $error = false;
    $error_code = ERROR_UNKNOWN;
    $error_description = "";

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : '';
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    if (!empty($_FILES['uploaded_video_file']['name'])) {

        switch ($_FILES['uploaded_video_file']['error']) {

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

        if ($_FILES['uploaded_video_file']['size'] > FILE_VIDEO_MAX_UPLOAD_SIZE) {

            $error = true;
            $error_code = ERROR_UNKNOWN;
            $error_description = 'File size is big.';
        }

        if (!$error) {

            $currentTime = time();
            $uploaded_file_ext = @pathinfo($_FILES['uploaded_video_file']['name'], PATHINFO_EXTENSION);
            $uploaded_file_ext = strtolower($uploaded_file_ext);

            if ($uploaded_file_ext === "mp4" || $uploaded_file_ext === "mov") {

                if (@move_uploaded_file($_FILES['uploaded_video_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                    $cdn = new cdn($dbo);

                    $response = $cdn->uploadVideo(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                    if (!$response['error']) {

                        $error = false;
                        $error_code = ERROR_SUCCESS;
                        $error_description = $response['error_description'];

                        $videoFileUrl = $response['fileUrl'];

                        if (isset($_FILES['uploaded_file']['name'])) {

                            $currentTime = time();
                            $uploaded_file_ext = @pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
                            $uploaded_file_ext = strtolower($uploaded_file_ext);

                            if ($uploaded_file_ext === "jpg") {

                                if (@move_uploaded_file($_FILES['uploaded_file']['tmp_name'], TEMP_PATH."{$currentTime}.".$uploaded_file_ext)) {

                                    $response = $cdn->uploadVideoImg(TEMP_PATH."{$currentTime}.".$uploaded_file_ext);

                                    if (!$response['error']) {

                                        $imgFileUrl = $response['fileUrl'];
                                    }
                                }
                            }
                        }
                    }

                    unset($cdn);

                } else {

                    $error = true;
                    $error_code = ERROR_UNKNOWN;
                    $error_description = 'Cannot save file on server.';
                }

            } else {

                $error = true;
                $error_code = ERROR_UNKNOWN;
                $error_description = 'Error file format.';
            }
        }
    }

    $result = array(
        "error" => $error,
        "error_code" => $error_code,
        "error_description" => $error_description,
        "imgFileUrl" => $imgFileUrl,
        "videoFileUrl" => $videoFileUrl);

    echo json_encode($result);
    exit;
}
