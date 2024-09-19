// Initialize Firebase
firebase.initializeApp(firebaseConfig);

function onSignIn(googleUser) {

  var profile = googleUser.getBasicProfile();
  var idToken = profile.id_token;
  // googleUser.disconnect()

  console.log("uid", profile.uid);

  console.log('Google Auth Response', googleUser);

  if (firebase.auth().currentUser !== null) console.log("user id: " + firebase.auth().currentUser.uid);

  // We need to register an Observer on Firebase Auth to make sure auth is initialized.
  var unsubscribe = firebase.auth().onAuthStateChanged((firebaseUser) => {

    unsubscribe();

    // Check if we are already signed-in Firebase with the correct user.
    if (!isUserEqual(googleUser, firebaseUser)) {

      console.log('!isUserEqual');

      // Build Firebase credential with the Google ID token.
      var credential = firebase.auth.GoogleAuthProvider.credential(googleUser.getAuthResponse().id_token);

      // Sign in with credential from the Google user.
      // [START auth_google_signin_credential]
      firebase.auth().signInWithCredential(credential).catch((error) => {

        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
        // ...
      });

    } else {

      console.log('User already signed-in Firebase.');

      console.log('getUID', firebase.auth().currentUser.uid);

      var action = "";

      if (options.pageId === "settings_services") {

          action = "connect";
      }

      $.ajax({
        type: 'POST',
        url: "/api/" + options.api_version + "/method/account.google",
        data: 'account_id=' + account.id + '&access_token=' + account.accessToken + '&app_type=1&uid=' + firebase.auth().currentUser.uid + "&action=" + action,
        dataType: 'json',
        timeout: 30000,
        success: function(response) {

          if (response.hasOwnProperty('error')) {

            if (options.pageId === "settings_services") {

              if (!response.error) {

                window.location = "/account/settings/services?status=g_connected";

              } else {

                window.location = "/account/settings/services?status=g_error";
              }

            } else {

              if (!response.error) {

                window.location = '/';

              } else {

                window.location = '/signup';
              }
            }
          }
        },
        error: function(xhr, type){

        }
      });

      // Logout

      if (!firebase.auth().currentUser) {

        firebase.auth().signOut().then(function() {

          console.log('Signed Out');
        });
      }
    }
  });
}

function isUserEqual(googleUser, firebaseUser) {

  if (firebaseUser) {

    var providerData = firebaseUser.providerData;

    for (var i = 0; i < providerData.length; i++) {

      if (providerData[i].providerId === firebase.auth.GoogleAuthProvider.PROVIDER_ID && providerData[i].uid === googleUser.getBasicProfile().getId()) {

        // We don't need to reauth the Firebase connection.
        return true;
      }
    }
  }
  return false;
}

gapi.load('auth2', function(){

  // Retrieve the singleton for the GoogleAuth library and set up the client.
  auth2 = gapi.auth2.init({

    client_id: constants.GOOGLE_CLIENT_ID,
    cookiepolicy: 'single_host_origin',

    // Request scopes in addition to 'profile' and 'email'
    //scope: 'additional_scope'
  });

  if ($('#g_custom_btn').length != 0) {

    attachSignin(document.getElementById('g_custom_btn'));
  }
});

function attachSignin(element) {

  auth2.attachClickHandler(element, {},

      function(googleUser) {

        onSignIn(googleUser);

      }, function(error) {

        //alert(JSON.stringify(error, undefined, 2));
      });
}