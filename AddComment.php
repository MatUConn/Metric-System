<?php
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');

$Comment = $mysqli->real_escape_string(trim($_POST['Comment']));
$UserID = $mysqli->real_escape_string(trim($_POST['UserID']));
$PID = $mysqli->real_escape_string(trim($_POST['Project']));
			
$Q_AddComment = "INSERT INTO Messages (Message, Author_ID, Project_ID, Message_Type, Completed)
					VALUES('$Comment', '$UserID', '$PID', 'Project', 0)";
$mysqli->query($Q_AddComment);