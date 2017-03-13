<?php
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');

$PID = $mysqli->real_escape_string(trim($_POST['Project']));
			
$Q_DeleteProject = "UPDATE project
					SET Deleted = '1'
					WHERE project_ID = '$PID'";
$mysqli->query($Q_DeleteProject);