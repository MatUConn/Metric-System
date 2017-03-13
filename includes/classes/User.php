<?php
//Contains methods for users on this website

//To James: Consider using MYSQLi_Result::fetch_object() method to load results of query into new instance of User, look
// at PHP reference manual
// Revised by Mat Fox on 5/18/2016

class User {
	
	//database connection
	private $db = NULL;
	private $user_id = '';
	private $user_name = '';
	private $logged_in_flag = FALSE;
	
	public $messages = array();
	public $errors = array();
	
	public function __construct($user_mysql_database) {
		$this->db = $user_mysql_database;
		
		//create/read current session
		session_start();
		
		//is a logged in session associated with this user
		if (isset($_SESSION['u_id']) && ($_SESSION['u_logged_in_flg'] == TRUE) ) {
			//if yes, set login status and refresh object via u_id found in session
			$this->logged_in_flag = TRUE;
			//load data from session into this object (user_name, user_id)
			$this->refreshUserFromSession();
		}
		//if user is not logged in, do nothing to this object
		
		
	} // end of user constructor
	
	//function authenticate user, this should be called when session id does not exist
	// Parameters - user_name, user_password
	// Returns Boolean
	
	private function authenticate($user_name, $user_password)
	{
		//escape MySQL sensitive characters from user input
		$user_name = $this->db->real_escape_string($user_name);
		$user_password = $this->db->real_escape_string($user_password);
		
		//Authentication query
		$q_chk_creds = "SELECT user_id, user_name
										FROM user
										WHERE user_name = '" . $user_name . 
										"' AND password = UNHEX(SHA1('" . $user_password . "'))";

		if(!($r_chk_creds = $this->db->query($q_chk_creds))) {
			//query failed, return false
			return FALSE;
		}

		if ($r_chk_creds->num_rows == 1) { //if a row was returned, then user exists in db and is who they say they are
			//authentication was successful
			
			$row = $r_chk_creds->fetch_object();
			
			// update User attributes for this object
			$this->user_id = $row->user_id;
			$this->user_name = $row->user_name;
			return TRUE;
			}
		else {
			//authentication failed, do not know user's identity
			return FALSE;
		}
	} // end of authenticate()
	
	//refeshes user attributes based on session data
	private function refreshUserFromSession() {
		$this->user_id = $_SESSION['u_id'];
		$this->user_name = $_SESSION['u_name'];
		$this->logged_in_flag = $_SESSION['u_logged_in_flg'];
	}
	
	//
	/*
	private function refreshUserFromDB() {
		$this->user_id
	}
	*/
	
	
	//Checks if user has permission to access website
	// Returns boolean
	private function isEnabled(){
		//enabled query
		$q_chk_enable_flg = "SELECT '1'
										FROM user
										WHERE user_name = '" . $this->user_name . 
										"' AND enabled_flag = TRUE";
		//store query result in new variable
		$r_chk_enable_flg = $this->db->query($q_chk_enable_flg);

		if ($r_chk_enable_flg->num_rows == 1) { //if a row was returned, then user is enabled & permission granted
				return TRUE;
			}
			else {
				//user does not have permission
				return FALSE;
			}
		
	}
	
	//Function login, returns result and feedback related to login process
	//returns array of 
	public function login($user_name, $user_password) {
		
		//new array for returning errors
		$user_login_report = array('Result' => 0 , 'Reason' => '');
		
		if( $this->authenticate($user_name, $user_password) == TRUE) {
			//user has been authenticated & this user object was updated
			
			//Does user have an enabled account
			if( $this->isEnabled() == TRUE) {
				//set logged in attribute
				$this->logged_in_flag = TRUE;
				//update session variables
				$_SESSION['u_id'] = $this->user_id;
				$_SESSION['u_name'] = $this->user_name;
				$_SESSION['u_logged_in_flg'] = $this->logged_in_flag;
				
				$user_login_report['Result'] = 1;
			}
			else {
				$user_login_report['Result'] = 0;
				$user_login_report['Reason'] = 'User account is disabled. Please contact admin.';
			}
		}
		else {
			$user_login_report['Result'] = 0;
			$user_login_report['Reason'] = 'Invalid user name or password.';
		}
		//return report
		return $user_login_report;
	}
	
	public function logout() {
		//reset session variables
		$_SESSION = array();
		session_destroy();
		$this->user_logged_in = FALSE;
	}
	
	// returns whether user is logged in or not
	public function getLoggedInFlag() {
		return $this->logged_in_flag;
	}
	
	public function getUserName() {
		return $this->user_name;
	}
	
	public function getUserID() {
		return $this->user_id;
	}
	
	public function getReadOnlyFlag() {
		
		//enabled query
		$q_chk_read_only_flg = "SELECT '1'
										FROM user
										WHERE user_name = '" . $this->user_name . 
										"' AND read_only = TRUE";
		//store query result in new variable
		$r_chk_read_only_flg = $this->db->query($q_chk_read_only_flg);

		if ($r_chk_read_only_flg->num_rows == 1) { //if a row was returned, then user is read only
				return TRUE;
			}
			else {
				//user is not read only, has write access
				return FALSE;
			}	
	}
    //Adding a function to see who is a Project Manager
   	 public function getProjectManagerFlag() {
		
		//enabled query
		$q_check_PM_flag = "SELECT '1'
										FROM user
										WHERE user_name = '" . $this->user_name . 
										"' AND Project_Manager = TRUE";
		//store query result in new variable
		$r_check_PM_flag = $this->db->query($q_check_PM_flag);

		if ($r_check_PM_flag->num_rows == 1) { //if a row was returned, then user is a Project Manager
				return TRUE;
			}
			else {
				//user is not a PM
				return FALSE;
			}	
	}
    //Adding a function to see who is an Administrator
    public function getAdministratorFlag() {
	   //enabled query
	   $q_check_Admin_flag = "SELECT '1'
	       					FROM user
		      				WHERE user_name = '" . $this->user_name . 
			     			"' AND Administrator = TRUE";
        //store query result in new variable
        $r_check_Admin_flag = $this->db->query($q_check_Admin_flag);
        if ($r_check_Admin_flag->num_rows == 1) { //if a row was returned, then user is an Administrator
            return TRUE;
        }
        else {
            //user is not an Admin
            return FALSE;
        }	
    }
	public function __destruct(){
		//Close database connection when this object is unset
		//handle this via close at end of script - JJK 11/12/2013
		//$this->db->close();
	}
	
}