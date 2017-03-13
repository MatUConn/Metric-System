<?php
# JK SOW p1.0
# login_functions.inc.php
# Author: JJK
# Create Date: 10/01/2013
# Description: Implements Login functionality of SOW system via function definitions
# Revision History:

// #Function redirect_user
// #Parameters: page (default=index.php)
// #Return Type: N/A, no return
function redirect_user($page = 'index.php') {
	//absolute URL for website
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	//remove trailing slashes (either '/' or '\') from right side of url
	$url = rtrim($url, '/\\');
	
	//append slashes and page via concatention assignment operator
	$url .= '/' . $page;
	echo $url;
	
	//redirect user
	header("Location: $url");
	exit(); //quit script
	
} //end of redirect_user function

// #Function check_login
// #Parameters: dbc, user_name (default is empty string), password (default is empty string)
// #Return Type: array - contains boolean of result and error array if false or mysqli assoc array if true
// Fucntion validates login credentials
function check_login($dbc, $user_name='', $password='') {
	$errors = array();
	if (empty($user_name)) {
		$errors[] = 'User name is required.';
	}
	else {
		$u_n = mysqli_real_escape_string($dbc, trim($user_name));
	}
	if (empty($password)) {
		$errors[] = 'Password is required.';
	}
	else {
		$p_w = mysqli_real_escape_string($dbc, trim($password));
	}
	
	//if no errors, run database query
	if (empty($errors)) {
		$q_chk_creds = "SELECT user_id, user_name
										FROM user
										WHERE user_name = '$u_n' AND password = UNHEX(SHA1('$p_w'))";
		
		$r_chk_creds = @mysqli_query($dbc, $q_chk_creds);
		if (mysqli_num_rows($r_chk_creds) == 1) {
			$row_creds = mysqli_fetch_array($r_chk_creds, MYSQLI_ASSOC);
			//return array of sucessful login(TRUE) and data in result set, function will stop here if condition is true
			return array(TRUE, $row_creds);
		}
		else { //no match
			$errors[] = 'Match for given user name and password not found';
		}
	} // end of if empty errors
	// return errors that were generated
	return array(FALSE, $errors);
} //end of check_login function