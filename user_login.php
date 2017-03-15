<?php
// Config File Include
require ('priv_includes/config.inc.php');
//App Core Include
define('PAGE_GATED', FALSE);
//App Core Include
require ('priv_includes/core.inc.php');

//Load Google PHP Client library
require_once(GEN_INCLUDES_PATH . 'libraries/google-api-php-client-0_6_7/src/Google_Client.php');
require_once(GEN_INCLUDES_PATH . 'libraries/google-api-php-client-0_6_7/src/contrib/Google_Oauth2Service.php');

//create new instance of User class
$sow_user = new User($mysqli);
//create array to store login error messages
$login_errors = array();
$login_msg = array();

$user_logged_in_flg = $sow_user->getLoggedInFlag();

//Note session has been started/read via instantiation of User class
//create new Google Client object
$client = new Google_Client();
// Set $client attributes, related to API access paramters in Google API console
$client->setApplicationName(GOOGLE_APPLICATION_NAME);
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$protocol = (isset($_SERVER['HTTPS'])) ? 'https' : 'http'; 
$client->setRedirectUri($protocol . '://metrictest.jkenergysolutions.net/user_login.php');
// $client->setDeveloperKey('insert_your_developer_key');
// set auto approval - uses auto approval of user scope prompt
$client->setApprovalPrompt('auto');

// create object that is an instance of Google_Oauth2Service class, pass Google Client object to constructor
$oauth2 = new Google_Oauth2Service($client);

$authUrl = $client->createAuthUrl(); 


// ## Check for request method

if ( $_SERVER['REQUEST_METHOD'] == 'GET') {

	//Check if user is viewing page via GET request
	
		//check if action is a logout
	if ($user_logged_in_flg == TRUE && $_REQUEST['action']== 'logout') {
		
		$sow_user->logout();
		
		// clear token from session (Google Authorization)
		unset($_SESSION['token']);
		$client->revokeToken;
		
		
		//inform user that they have been logged out, store message in msg array
		$login_msg[] = 'You have been logged out.';
	}
	//check if action is equal to redirect, if so this user is being redirected after a login
	else if ( $user_logged_in_flg == TRUE && ($_GET['action'] == 'redirect') AND (isset($_GET['returnURL'])) ) {
		
		free_app_resources();
		redirect_user($_GET['returnURL']);
		exit();
	}
	else if ( $user_logged_in_flg == TRUE ){ //Check if user is already logged in
	
	//Inform user that he is already logged in
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>JK Metric System</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
	<?php
	echo'<meta http-equiv="refresh" content="1; url=index.php" />';
	?>
  </head>
		
<?php
	echo '<div class="alert alert-success"><p>You are logged in, "' . $sow_user->getUserName() . '"!</p></div>
			  <body class="login">
    <div>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form>
              <h2>Redirecting you to the</h2>
                <div>
                  <h1>Metric System</h1>
                </div>
				<div class="separator">
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>';

	free_app_resources();
	exit();
	}
	
	// if code is found in GET request and has value
	if (isset($_GET['code'])) {
		//call authenticate method of Google Client class using value of URL parameter [code]
	  $client->authenticate($_GET['code']);
	  //set session token to value of client's access token
	  $_SESSION['token'] = $client->getAccessToken();
	  $redirect = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	  return;
	}
	
	//if token is defined in session data, call setAccessToken method of google_client class
	if (isset($_SESSION['token'])) {
		$client->setAccessToken($_SESSION['token']);
	}
	
	if ($client->getAccessToken()) { //if web app was able to get user's access token, Google ID has been verified
		//get user information & store as array in $user
	  $user = $oauth2->userinfo->get();
	
	  // These fields are currently filtered through the PHP sanitize filters.
	  // See http://www.php.net/manual/en/filter.filters.sanitize.php
	  $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $img = filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup = "$email<div><img src='$img?sz=50'></div>";
	
		$google_id = $user['id'];
		$google_email = $email;
	
	  // The access token may have been updated lazily.
	  $_SESSION['token'] = $client->getAccessToken();
	  
	  //check to see if google user is authorized to use SOW web application via google id
	  /*
	  $q_chk_creds = "SELECT user_id, user_name
											FROM user
											WHERE google_id = " . $google_id;
											*/
		//check to see if google user is authorized to use SOW web application via google email
		$q_chk_creds = "SELECT user_id, user_name
										FROM user
										WHERE email = '" . $google_email . "'";
											
											
											
				//store query result in new variable
		$r_chk_creds = $mysqli->query($q_chk_creds);
			
		if ($r_chk_creds->num_rows == 1) { //if a row was returned, then provided credentials are valid
					
			//get result row as an object
			$result_row = $r_chk_creds->fetch_object();
				
			//write user data into session
			$_SESSION['u_id'] = $result_row->user_id;
			$_SESSION['u_name'] = $result_row->user_name;
			$_SESSION['u_logged_in_flg'] = 1;
			
			redirect_user('user_login.php');
		}
		else {
				$login_errors[] = "
										<p>Your are not authorized to use this application. Please contact site admin.</p>";

				//clear tested token, it is not needed anymore
			 	unset($_SESSION['token']);
  			$client->revokeToken();
  			
		}
			
	} //end if SESSION token is found
	else { //SESSION token not found, create google authURL
		//User has not signed in via Google, generate authroization url by calling createAuthUrl method
  	$authUrl = $client->createAuthUrl(); 
  	//To James: This command is called twice, test to see why calling it twice (see line 46) is needed, when does the $authURL
  	// get accessed or modified, what does createAuthURL return?
	}
	
	

} // End if request is using GET method

else if ( $_SERVER['REQUEST_METHOD'] == 'POST') { 
	//is page being accessed via post method (user has submitted form data)
	
	//validate form data and attempt to login user
	
	//Validate that user name & password were submitted
	if (empty($_POST['user_name'])){
		$login_errors[] = 'User name is required.';
	}
	if (empty($_POST['password'])){
		$login_errors[] = 'Password is required.';
	}
	if( !(empty($_POST['user_name'])) && !(empty($_POST['password']) ) ){ //Password and user name provided?
		
		$login_report = $sow_user->login( $_POST['user_name'], $_POST['password'] );
		
		if($login_report['Result'] == 1) { //login was successful
			//redirect user to returnURL
			free_app_resources();
			redirect_user($_REQUEST['returnURL']);
			exit();
		}
		else{ //logged in failed
			//add reason from login function call to login_errors array
			$login_errors[] = $login_report['Reason'];
		}
	}
} //End of REQUEST METHOD == POST, user submitting login form

// Include Headers
//include(GEN_INCLUDES_PATH . 'header.html');
//include(GEN_INCLUDES_PATH . 'header2.html');


//determine returnURL to display what URL was denied, when user not logged in
if ( isset($_REQUEST['returnURL']) ) {
	$returnURL = $_REQUEST['returnURL'];
	// do not display message for app index
	if ($returnURL != 'index.php') {
		$login_msg[]= 'Access to ' . $returnURL . ' denied. User not logged in. Please login.';
	}
}
else {
	$returnURL = 'user_login.php';
}

//Display any notification messages
if(!empty($login_msg)){

	echo '<div class="alert alert-warning">';
	foreach ($login_msg as $msg){
		echo "<p>$msg</p>\n"; //print error
	}
	echo '</div>';
}


//display any error messages
if(!empty($login_errors)){
	echo '<div class="form-signin">';
	foreach ($login_errors as $msg){
		echo "<div class=\"alert alert-danger\"><p>$msg</p></div><br />\n"; //print error
	}
	echo '</div><!-- /form signin -->';
	//echo '</p><p>Please try again.</p><p><br /></p>';
}

//display google login authentication errors - To James: Consider merging into above control statements
/*
if(empty($app_auth_chk_result)== FALSE) { // Google ID verified, but not authorized for SOW system
	echo $app_auth_chk_result;
}
*/
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>JK Metric System</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://colorlib.com/polygon/gentelella/css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form>
              <h1>Login</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
				<?php
                echo'<a class="btn btn-default" href="' . $authUrl . '"><img src="images\google_brand\White-signin-Long-base-44dp.png" class="img-responsive"></a>';
				?>
              </div>
              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1> Metric System</h1>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>


<?php

free_app_resources();