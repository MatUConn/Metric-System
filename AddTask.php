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
	
if ($MCode == 2){
    //Getting the project name, salesperson, and salespersons email
    $Q_Get_Salesperson = "SELECT p.project_name, c.first_name AS Salesperson, CONCAT(SUBSTRING(c.first_name,1,1), c.last_name, '@energyresourcesusa.net') AS SalespersonEmail
                            FROM project p
                            LEFT JOIN contact c ON (p.salesperson_id = c.contact_id)
                            WHERE p.project_ID = $PID";
    $R_Get_Salesperson = $mysqli->query($Q_Get_Salesperson);
    $SalespersonInfo = $R_Get_Salesperson->fetch_array(MYSQLI_ASSOC);
    $SalespersonEmail = $SalespersonInfo['SalespersonEmail'];
    $Subject = 'Your proposal for project ' . $SalespersonInfo['project_name'] . ' is ready';
    $EmailBody = $SalespersonInfo['Salesperson'] . ", \r\n" . $Message;
    
	$headers = 'From: MetricSystem@jkenergysolutions.net' . "\r\n" .
   'Reply-To: MetricSystem@jkenergysolutions.net' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();
	mail($SalespersonEmail, $Subject, $EmailBody, $headers, '-freturn@jkenergysolutions.net');
    //I'm keeping these emails being sent to me for debugging ONLY
    mail('mfox@energyresourcesusa.net', $Subject, $EmailBody, $headers, '-freturn@jkenergysolutions.net');
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