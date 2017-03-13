<?php
# JK SOW p1.0
# login.inc.php
# Author: JJK
# Create Date: 10/01/2013
# Description: Implements Login functionality of SOW system
# Revision History:

$page_title = 'Login';
include('includes/header.html');

//Print error messages if they exist

if (isset($errors) && !empty($errors)) {
	echo '<h1> Error </h1>
				<p class="error">The following error(s) occurred:<br />';
	//print each message
	foreach($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '<p>Please try again.</p>';
}

//Display login form
?>
<form role="form" action="login.php" method="post">
	<input type="text" class="form-control" name="user_name" placeholder="User Name" />
	<input type="password" class="form-control" name="password" placeholder="Password" />
	<button type="submit" class="btn btn-default">Sign In</button>
</form>

<?php include('includes/footer.html');