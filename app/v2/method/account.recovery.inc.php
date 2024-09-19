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

if (!empty($_POST)) {

    $clientId = isset($_POST['clientId']) ? $_POST['clientId'] : 0;
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $clientId = helper::clearInt($clientId);
    $email = helper::escapeText($email);

    if ($clientId != CLIENT_ID) {

        api::printError(ERROR_UNKNOWN, "Error client Id.");
    }

    $result = array("error" => true,
                    "error_code" => ERROR_UNKNOWN);

    if (helper::isCorrectEmail($email)) {

        $accountId = $helper->getUserIdByEmail($email);

        if ($accountId != 0) {

            $account = new account($dbo, $accountId);

            $accountInfo = $account->get();

            if ($accountInfo['error'] === false && $accountInfo['state'] != ACCOUNT_STATE_BLOCKED) {

                $restorePointInfo = $account->restorePointCreate($email, $clientId);

                ob_start();

                ?>

                    <html>
                        <body>
                            This is link <a href="<?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?>"><?php echo APP_URL;  ?>/restore/?hash=<?php echo $restorePointInfo['hash']; ?></a> to reset your password.
                        </body>
                    </html>

                <?php

                $from = SMTP_EMAIL;

                $to = $email;

                $html_text = ob_get_clean();

                $subject = APP_TITLE." | Password reset";

                $mail = new phpmailer();

                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = SMTP_HOST;                               // Specify main and backup SMTP servers
                $mail->SMTPAuth = SMTP_AUTH;                               // Enable SMTP authentication
                $mail->Username = SMTP_USERNAME;                      // SMTP username
                $mail->Password = SMTP_PASSWORD;                      // SMTP password
                $mail->SMTPSecure = SMTP_SECURE;                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = SMTP_PORT;                                    // TCP port to connect to

                $mail->From = $from;
                $mail->FromName = APP_TITLE;
                $mail->addAddress($to);               // Name is optional

                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = $subject;
                $mail->Body    = $html_text;

                $mail->send();

                $result = array("error" => false,
                                "error_code" => ERROR_SUCCESS);
            }
        }
    }

    echo json_encode($result);
    exit;
}
