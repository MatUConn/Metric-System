<?php

// #Function pk_exists
// #Parameters: 
// #Return Type: Array
function pk_exists($database_obj, $table_name, $pk_value){
	//function body
	
	//*Might want to explcitly set table name - pk column names here for security reasons and if user assoc. with app doesn't have
	//priv to query information schema
	//determine primary key column for give table name
	
		//array will contain boolean result and description of result
	$result;
	$reason_msg;
	
	$q_get_pk_col = "SELECT tc.table_name, column_name AS 'primary_key_col'
										FROM information_schema.table_constraints tc
										INNER JOIN information_schema.key_column_usage kcu
										ON (kcu.table_name = tc.table_name  AND kcu.constraint_name = tc.constraint_name)
										WHERE tc.constraint_type = 'primary key' AND tc.table_name = '$table_name'";
	$r_get_pk_col = $database_obj->query($q_get_pk_col);
	
	$my_sqli_error = '';
	
	//Begin nested Ifs, do not go deeper into nest if an error is encountered
	if(($r_get_pk_col == FALSE) OR ($r_get_pk_col->num_rows != 1) ){ //did query fail to run or did it not return 1 row
		// 
		$my_sqli_error = $database_obj->error;
		$reason_msg = 'Unable to determine primary key column name. MySQL Error: ' . $my_sqli_error;
		$result = array(
			"result" => 'unknown',
			"reason" => $reason_msg
		);
	}
	else{
		
		$row_get_pk = $r_get_pk_col->fetch_array(MYSQLI_ASSOC);
		
		//not using while loop becuase query should only return 1 row
		$pk_column_name = $row_get_pk['primary_key_col'];
		
		$q_chk_for_pk_val = "SELECT '1' FROM " . $table_name . " WHERE " . $pk_column_name . " = " . $pk_value . " LIMIT 1";
		$r_chk_for_pk_val = $database_obj->query($q_chk_for_pk_val);
		
		if($r_chk_for_pk_val == FALSE){
			
			$reason_msg = 'Unable to check for primary key violation for ' . $table . '.' . $pk_column_name .
			 'MySQL Error: ' . $database_obj->error;
			$result = array(
				"result" => 'unknown',
				"reason" => $reason_msg
			);
		}
		else if ($r_chk_for_pk_val->num_rows == 1){
			
			$reason_msg = "A value of " . $pk_value . " exists for primary key " . $table_name . "." . $pk_column_name . ".";  
			$result = array(
				"result" => 'true',
				"reason" => $reason_msg
			);
		}
		else{
			//Primary Key does not exist
			$reason_msg = "No Primary Key Conflict.";
			$result = array(
				"result" => 'false',
				"reason" => $reason_msg
			);
		}
	}
	//make result array a new instance of the array class (*eliminated)
	//$result = new ArrayObject($result);
	return $result; 
}

// #Function echoActiveClassIfRequestMatches
// #Parameters: requestURL
// #Return Type: none
// #Description
// Create Date: 09/20/2013
// Description: Used to implement active page for Twitter Bootstrap Navbar
function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

// #Function redirect_user
// #Parameters: page (default=index.php)
// #Return Type: N/A, no return
function redirect_user($page = 'index.php') {
	//absolute URL for website
	$protocol = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
	$url = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	//remove trailing slashes (either '/' or '\') from right side of url
	$url = rtrim($url, '/\\');
	
	//append slashes and page via concatention assignment operator
	$url .= '/' . $page;
	
	//redirect user
	header("Location: $url");
	exit(); //quit script
	
} //end of redirect_user function

// #Function free_app_resources
// #Parameters: void
// #Return Type: no return
// #Description: Frees up resources used by app - mysqli connections & objects, Objects of User class

function free_app_resources(){
	
	//declare gloabal scope of following variables
	global $mysqli;
	global $sow_user;
	
	if (isset($mysqli)) {
		$mysqli->close();
		unset($mysqli);
	}
	if (isset($sow_user)) {
		unset($sow_user);
	}
	
}

// #Function validate_color_temp
// #Parameters: mysql database object, integer fixture id, integer color temperature
// #Return Type: no return
// #Description: Frees up resources used by app - mysqli connections & objects, Objects of User class

function color_temp_req($mysqli_db, $fixture_id) {
	
	$result = '';
	$reason = '';

	//check if color temperature is required for selected fixture type
	
	//Query
	$q_check_clr_req = "SELECT '1' 
											FROM fixture
											WHERE fixture_id = $fixture_id AND require_color_temp = TRUE LIMIT 1";
	
	// Execute query and check result
	if (!($r_check_clr_req = $mysqli_db->query($q_check_clr_req))) { //Did query fail?
		$result = 'unknown';
		$reason = 'Replacement color temperature validation failed. MySQL Error: ' . $mysqli->error . '
      Query: ' . $q_check_clr_req . ' ';
	}
	else if($r_check_clr_req->num_rows == 1) { //Did query return one result?
		// Yes, then throw error: 
		$result = 'true';
	}
	else { //set replacement color temp to NULL, no rows returned from above query
		$result = 'false';
	}
	
	$chk_result = array(
			"result" => $result,
			"reason" => $reason
		);
	
	return $chk_result;
}