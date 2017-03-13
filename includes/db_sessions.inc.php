<?php
# JK SOW p1.0
# db_sessions.inc.php
# Author: JJK
# Create Date: 11/06/2013
# Description: Implements Sessions Storage in Database system
# Revision History:
# Note: Must include a call to session_write_close() in scripts in order to prevent database connection issues

//global variable for database connection, stores database connection
$sdbc = NULL;

//Open_Session() function
//Arguments: None
//Description: Function opens database
//Returns: BOOL, true

function open_session() {
	
	global $sdbc;
	$sdbc = new MySQLi(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
	if ($sdbc->connect_errorno){ //class attribute has value of zero if no connection failures
		return false;
	}
	else {
		return true;
	}
}

// close_session() function
// Arguments: None
// Return: Closed status
// Returns : Bool , true

function close_session() {
	
	global $sdbc;
	//return result of mysqli close function
	return $sdbc->close();
}

// read_session() function
// Arguments: session ID
// Return: Session Data as string

function read_session($sid) {
	global $sdbc;
	
	//Query the database
	
	$q = sprintf('SELECT data FROM session WHERE session_id="%s"', mysqli_real_escape_string($sdbc, $sid));
	
	$r = $sdbc->query($q);
	
	//retrieve result
	if ($r->num_rows == 1 ) {
		
		//assign variable as if it was an array
		list($data) = $r->fetch_array(MYSQLI_NUM);
		
		//return data
		return $data;
	}
	else {
		//return empty string
		return '';
	}
} //end of read_session()

// write_session() function
// Arguments: session ID, Session Data
// Return: Session Data as string

function write_session($sid, $data) {
	
	global $sdbc;
	
	//Store session data in database
	$q = sprintf('REPLACE INTO session (session_id, data) VALUES ("%s", "%s")', 
	$sdbc->real_escape_string($sid), $sdbc->real_escape_string($data));
	
	//return binary result of mysqli query
	return $sdbc->query($q);
	
}

// destroy_session() function
// Arguments: session ID
// Return: Session Data as string

function destroy_session($sid) {
	
	global $sdbc;
	
	//Delete from database
	
	$q = sprintf('DELETE FROM session where session_id = "%s"', $sdbc->real_escape_string($sid));
	
	$r = $sdbc->query($q);
	
	//clear $_SESSION array
	
	$_SESSION = array();
	
	//return result of mysqli query call
	return $r;
}

// clean_session() function
// Arguments: value in seconds
// Return: Session Data as string

function clean_session($expire) {
	
	global $sdbc;
	
	//Delete Old Sessions
	
	$q = sprintf('DELETE FROM session WHERE DATE_ADD(last_accessed, INTERVAL %d SECOND) < NOW() ', (int) $expire);
	
	return $sdbc->query($q);
	
}

//declare functions to use for session handling
session_set_save_handler('open_session', 'close_session', 'read_session', 'write_session', 'destroy_session', 'clean_session');
// register shutdown function, function will be called after script execution finishes or exit() is called
//    this helps avoid calling session_write_close() before each script termination point
register_shutdown_function('session_write_close');
//session_start();