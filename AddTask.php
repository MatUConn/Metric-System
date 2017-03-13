<?php
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');

$Message = $mysqli->real_escape_string(trim($_POST['Message']));
$PID = $mysqli->real_escape_string(trim($_POST['Project']));
$UserID = $mysqli->real_escape_string(trim($_POST['UserID']));
$Recipient_ID = $mysqli->real_escape_string(trim($_POST['Recipient']));
$MCode = $mysqli->real_escape_string(trim($_POST['MCode']));

//Getting the Salesperson
$q_get_user_info = "SELECT C.first_name, C.last_name
							FROM contact C
							JOIN project P ON (P.salesperson_id = C.contact_id)
							WHERE P.project_ID = $PID";
$r_get_user_info = $mysqli->query($q_get_user_info);
$SalespersonInfo = $r_get_user_info->fetch_array(MYSQLI_NUM);
$Username = $SalespersonInfo[0] . ' ' . $SalespersonInfo[1];
	
if ($MCode == 2){
	$headers = 'From: MetricSystem@jkenergysolutions.net' . "\r\n" .
   'Reply-To: MetricSystem@jkenergysolutions.net' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
	mail('mfox@energyresourcesusa.net', 'A New Task', $Message, $headers, '-freturn@jkenergysolutions.net');
} 
else {
	$Q_AddTask = "INSERT INTO Messages (Message, Author_ID, Project_ID, Message_Type, MCode, Completed)
						VALUES('$Message', '$UserID', '$PID', 'Task', '$MCode', 0)";
	$mysqli->query($Q_AddTask);

	$Last_ID = $mysqli->insert_id;
	$Q_AddTaskRecipients = "INSERT INTO Message_Recipients (Message_ID, Recipient_ID)
							VALUES('$Last_ID', '$Recipient_ID')";
	$mysqli->query($Q_AddTaskRecipients);

	//If the MCode is 2-6, we want to set the task which was assigned before this to be completed.  
	if ($MCode >= 2){
		$PrevCode = $MCode - 1;
		$Q_CompleteTask = "UPDATE Messages
					SET Completed = 1
					WHERE Project_ID = $PID
					AND Message_Type = 'Task'
					AND MCode = '$PrevCode' 
					LIMIT 1";
	$mysqli->query($Q_CompleteTask);
	}
}
?>