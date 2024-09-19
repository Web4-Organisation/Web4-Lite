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

    $page_id = "support";

    $error = false;
    $send_status = false;
    $email = "";
    $subject = "";
    $about = "";

    if (auth::isSession()) {

        $ticket_email = "";
    }

    if (!empty($_POST)) {

        $token = isset($_POST['authenticity_token']) ? $_POST['authenticity_token'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
        $about = isset($_POST['about']) ? $_POST['about'] : '';
        $recaptcha_token = isset($_POST['recaptcha_token']) ? $_POST['recaptcha_token'] : '';

        $subject = helper::clearText($subject);
        $about = helper::clearText($about);
        $email = helper::clearText($email);

        $subject = helper::escapeText($subject);
        $about = helper::escapeText($about);
        $email = helper::escapeText($email);

        // Google Recaptcha

        $recaptcha = new \ReCaptcha\ReCaptcha(RECAPTCHA_SECRET_KEY);
        $resp = $recaptcha->verify($recaptcha_token, $_SERVER['REMOTE_ADDR']);

        if (!$resp->isSuccess()){

            $error = true;
            $error_message[] = "Google Recaptcha error";
        }

        if (auth::getAuthenticityToken() !== $token) {

            $error = true;
        }

        if (!helper::isCorrectEmail($email)) {

            $error = true;
        }

        if (empty($about)) {

            $error = true;
        }

        if (empty($subject)) {

            $error = true;
        }

        if (!$error) {

            $accountId = auth::getCurrentUserId();
            $clientId = 0; //Desktop version;

            $support = new support($dbo);
            $support->createTicket($accountId, $email, $subject, $about, $clientId);

            $send_status = true;
        }
    }

    auth::newAuthenticityToken();

    $css_files = array();
    $page_title = $LANG['page-support']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="remind-page has-bottom-footer">

    <?php

        include_once("../html/common/topbar.inc.php");
    ?>

    <div class="wrap content-page">
        <div class="main-column">
            <div class="main-content page-title-content p-0">

                <div class="standard-page">

                    <?php

                    if ($send_status) {

                        ?>

                        <h1><?php echo $LANG['page-support']; ?></h1>

                        <div class="alert alert-success mt-5">
                            <b><?php echo $LANG['ticket-send-success']; ?></b>
                        </div>

                        <?php

                    } else {

                        ?>

                        <h1><?php echo $LANG['page-support']; ?></h1>
                        <p><?php echo $LANG['label-support-sub-title']; ?></p>

                        <form accept-charset="UTF-8" action="/support" class="custom-form" id="support-form" method="post">

                            <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo helper::getAuthenticityToken(); ?>">

                            <div class="alert alert-danger" style="<?php if (!$error) echo "display: none"; ?>">
                                <h3"><?php echo $LANG['ticket-send-error']; ?></h3>
                            </div>

                            <p><label for="email"><?php echo $LANG['label-email']; ?></label></p>
                            <input id="email" name="email" placeholder="" required="required" size="30" type="text" value="<?php echo $email; ?>">

                            <p><label for="subject"><?php echo $LANG['label-subject']; ?></label></p>
                            <input id="subject" name="subject" maxlength="164" placeholder="" required="required" size="30" type="text" value="<?php echo $subject; ?>">

                            <p><label for="about"><?php echo $LANG['label-support-message']; ?></label></p>
                            <textarea id="about" name="about" required="required" maxlength="800"><?php echo $about; ?></textarea>

                            <div class="login-button">
                                <input name="commit" class="button primary" type="submit" value="<?php echo $LANG['action-send']; ?>">
                            </div>

                        </form>

                        <?php

                    }
                    ?>
                </div>

            </div>
        </div>

    </div>

    <?php

        include_once("../html/common/footer.inc.php");
    ?>

    <script>

        $('#support-form').submit(function(event) {

            event.preventDefault();

            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo RECAPTCHA_SITE_KEY; ?>', {action: 'submit'}).then(function(token) {

                    $('#support-form').prepend('<input type="hidden" name="recaptcha_token" value="'+ token + '">');
                    $('#support-form').unbind('submit').submit();
                });
            });
        });
    </script>

</body>
</html>