<?php

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $account = new account($dbo, auth::getCurrentUserId());
    $accountInfo = $account->get();
    unset($account);

    $page_id = "settings_otp";

    $css_files = array("main.css", "my.css");
    $page_title = $LANG['page-otp']." | ".APP_TITLE;

    include_once("../html/common/header.inc.php");

?>

<body class="settings-page">

<?php

    include_once("../html/common/topbar.inc.php");
?>

<div class="wrap content-page">

    <div class="main-column row">

        <?php

            include_once("../html/common/sidenav.inc.php");
        ?>

        <?php

            include_once("../html/account/settings/settings_nav.inc.php");
        ?>

        <div class="row col sn-content" id="content">

            <div class="main-content">

                <div class="profile-content standard-page">

                    <h1><?php echo $LANG['page-otp']; ?></h1>
                    <h3><?php echo $LANG['page-otp-desc']; ?></h3>

                    <div class="edit_user" id="settings-form">

                        <input autocomplete="off" type="hidden" name="authenticity_token" value="<?php echo auth::getAuthenticityToken(); ?>">

                        <div class="tabbed-content">

                            <?php

                                if ($accountInfo['otpVerified'] == 1) {

                                    ?>
                                        <div class="success-container mt-3">
                                            <?php echo $LANG['label-otp-verification-success']; ?>
                                        </div>

                                        <button disabled id="send-code" class="blue action-button mt-4 hidden" name="commit"><?php echo $LANG['action-send-code']; ?></button>
                                    <?php

                                } else {

                                    ?>
                                        <div class="tab-pane active form-table">

                                            <div class="profile-basics form-row">
                                                <div class="form-cell left">
                                                    <p class="info"><?php echo $LANG['label-otp-phone-number-msg']; ?></p>
                                                </div>

                                                <div class="form-cell">
                                                    <input type="text" id="inputRow" name="inputRow" placeholder="<?php echo $LANG['placeholder-otp-phone-number']; ?>" maxlength="15" pattern="/[0-9+]+/" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button disabled id="send-code" class="blue action-button mt-4" name="commit" onclick="start_authorize();"><?php echo $LANG['action-send-code']; ?></button>
                                        </div>
                                    <?php
                                }
                            ?>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<?php

    include_once("../html/common/footer.inc.php");
?>

<script type="text/javascript" src="/js/firebase/config.js"></script>

<script>

    // Strings

    var strings = {

        sz_action_sent_code: "<?php echo $LANG['action-send-code']; ?>",
        sz_action_check_code: "<?php echo $LANG['action-check-code']; ?>",
        sz_placeholder_phone_number: "<?php echo $LANG['placeholder-otp-phone-number']; ?>",
        sz_placeholder_sms_code: "<?php echo $LANG['placeholder-otp-code']; ?>",
        sz_label_phone_number: "<?php echo $LANG['label-otp-phone-number-msg']; ?>",
        sz_label_sms_code: "<?php echo $LANG['label-otp-code-msg']; ?>",
        sz_label_sms_code_error: "<?php echo $LANG['label-otp-verification-code-error']; ?>",
        sz_label_sms_code_sent: "<?php echo $LANG['label-otp-verification-code-sent']; ?>",
        sz_label_verification_success: "<?php echo $LANG['label-otp-verification-success']; ?>",
            sz_label_verification_error: "<?php echo $LANG['label-otp-verification-error']; ?>",
        sz_label_phone_format_error: "<?php echo $LANG['label-otp-phone-format-error']; ?>",
        sz_label_many_requests_error: "<?php echo $LANG['label-otp-many-requests-error']; ?>",
        sz_label_captcha_error: "<?php echo $LANG['label-otp-captcha-error']; ?>",
        sz_label_phone_number_taken_error: "<?php echo $LANG['label-otp-phone-number-taken-error']; ?>"
    };

    // Save Phone Number

    var phoneNumber = "";
    var codeSent = false;

    // Html elements

    $inputRow = $('input[name=inputRow]');
    $actionButton = $('button[name=commit]');
    $infoLabel = $('p.info');
    $formTable = $('div.form-table');
    $contentBlock = $('div.tabbed-content');

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    function start_authorize() {

        console.log("auth");

        if (!codeSent) {

            submitPhoneNumberAuth();

        } else {

            submitPhoneNumberAuthCode();
        }
    }

    // Create a Recaptcha verifier instance globally
    // Calls submitPhoneNumberAuth() when the captcha is verified

    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('send-code', {
        'size': 'invisible',
        'callback': function(response) {

            // reCAPTCHA solved, allow signInWithPhoneNumber.

            console.log('window.recaptchaVerifier' + " " + response.toString());
        }
    });

    //

    // This function runs when the 'sign-in-button' is clicked
    // Takes the value from the 'phoneNumber' input and sends SMS to that phone number

    function submitPhoneNumberAuth() {

        $actionButton.attr('disabled', 'disabled');

        console.log('submitPhoneNumberAuth start');

        phoneNumber = $inputRow.val();

        var appVerifier = window.recaptchaVerifier;

        firebase
            .auth()
            .signInWithPhoneNumber(phoneNumber, appVerifier)
            .then(function(confirmationResult) {

                window.confirmationResult = confirmationResult;

                codeSent = true;

                console.log('submitPhoneNumberAuth success');

                $inputRow.attr("placeholder", strings.sz_placeholder_sms_code);
                $inputRow.val('');
                $actionButton.text(strings.sz_action_check_code);
                $actionButton.attr("disabled", "disabled");
                $infoLabel.text(strings.sz_label_sms_code);

                $contentBlock.prepend("<div class=\"success-container mt-3\">" + strings.sz_label_sms_code_sent + "</div>");
            })
            .catch(function(error) {

                console.log('submitPhoneNumberAuth error');

                console.log(error.message);
                console.log(error.code);

                $('div.errors-container').fadeOut( "slow", function() {

                    $(this).remove();
                });

                // error codes
                // auth/invalid-phone-number
                // auth/too-many-requests
                // auth/captcha-check-failed

                if (error.code === "auth/invalid-phone-number") {

                    $contentBlock.prepend("<div class=\"errors-container mt-3\">" + strings.sz_label_phone_format_error + "</div>");

                } else if (error.code === "auth/too-many-requests") {

                    $contentBlock.prepend("<div class=\"errors-container mt-3\">" + strings.sz_label_many_requests_error + "</div>");
                    $actionButton.remove();
                    $formTable.remove();

                } else if (error.code === "auth/captcha-check-failed") {


                }
            });
    }

    // This function runs when the 'confirm-code' button is clicked
    // Takes the value from the 'code' input and submits the code to verify the phone number
    // Return a user object if the authentication was successful, and auth is complete

    function submitPhoneNumberAuthCode() {

        $actionButton.attr('disabled', 'disabled');

        console.log('submitPhoneNumberAuthCode start');

        var code = $inputRow.val();

        confirmationResult
            .confirm(code)
            .then(function(result) {

                var user = result.user;
                console.log(user);
                console.log(user.getIdToken(true).token);

                firebase.auth().currentUser.getIdToken(/* forceRefresh */ true).then(function(idToken) {

                    // Send token to your backend via HTTPS
                    console.log(idToken);

                    $.ajax({
                        type: 'POST',
                        url: "/api/" + options.api_version + "/method/account.otp",
                        data: 'accountId=' + account.id + '&accessToken=' + account.accessToken + '&token=' + idToken + "&phoneNumber=" + phoneNumber,
                        dataType: 'json',
                        timeout: 30000,
                        success: function(response) {

                            if (response.hasOwnProperty('verified')) {

                                if (response.verified) {

                                    console.log(response.token);
                                    console.log(response.verified);
                                    console.log(response.payload);
                                    console.log(response.phone_number);

                                    $contentBlock.prepend("<div class=\"success-container mt-3\">" + strings.sz_label_verification_success + "</div>");
                                    $actionButton.remove();

                                } else {

                                    if (response.hasOwnProperty('error_code')) {

                                        switch (response.error_code) {

                                            case 507: {

                                                $contentBlock.prepend("<div class=\"errors-container mt-3\">" + strings.sz_label_phone_number_taken_error + "</div>");
                                                $actionButton.remove();

                                                break;
                                            }

                                            default: {

                                                $contentBlock.prepend("<div class=\"errors-container mt-3\">" + strings.sz_label_verification_error + "</div>");
                                                $actionButton.remove();

                                                break;
                                            }
                                        }
                                    }

                                    console.log(response.token);
                                }

                                $formTable.remove();
                            }
                        },
                        error: function(xhr, type){

                            alert("error");
                        }
                    });

                }).catch(function(error) {

                    // Handle error

                    //console.log(error.message);
                    //console.log(error.code);
                });
            })
            .catch(function(error) {

                // Handle error

                console.log(error.message);
                console.log(error.code);

                // error codes
                // auth/invalid-verification-code

                if (error.code === "auth/invalid-verification-code") {

                    $inputRow.val("");

                    $contentBlock.prepend("<div class=\"errors-container mt-3\"><ul>" + strings.sz_label_sms_code_error + "</ul></div>");
                }
            });
    }

    //This function runs everytime the auth state changes. Use to verify if the user is logged in

    firebase.auth().onAuthStateChanged(function(user) {

        if (user) {

            console.log("USER LOGGED IN");

        } else {

            // No user is signed in.
            console.log("USER NOT LOGGED IN");
        }
    });

    $(document).ready(function() {

        if (!firebase.auth().currentUser) {

            firebase.auth().signOut().then(function() {

                console.log('Signed Out');
            });
        }

        $inputRow.focus(function() {

            $('div.errors-container').fadeOut( "slow", function() {

                $(this).remove();
            });

            $('div.success-container').fadeOut( "slow", function() {

                $(this).remove();
            });
        });

        $inputRow.keyup(function(event) {

            if ($inputRow.val().length > 5) {

                $actionButton.removeAttr("disabled");

            } else {

                $actionButton.attr('disabled', 'disabled');
            }
        });
    });

</script>

</body>
</html>