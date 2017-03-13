<?php
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');
$UserID = $sow_user->getUserID();
$q_get_user_info = "SELECT first_name, last_name, Usergroup, Avatar, user_id FROM user
                    WHERE user_id = $UserID";
$r_get_user_info = $mysqli->query($q_get_user_info);
$UserInfo = $r_get_user_info->fetch_array(MYSQLI_NUM);
$Username = $UserInfo[0] . ' ' . $UserInfo[1];
$UserURL = $UserInfo[3] ? $UserInfo[4] : "Default";//If the User has an avatar, set the URL to their ID.  Otherwise use default.  
//Dealing with Project ID's
if ( (isset($_GET['proj_id']))  && (is_numeric($_GET['proj_id'])) ) { //source is from projects.php
	$Project_ID = $_GET['proj_id'];
}
else if ( (isset($_POST['proj_id'])) && (is_numeric($_POST['proj_id'])) ) { //data being passed via form submission
	$Project_ID = $_POST['proj_id'];
}
else { //an invalid project id was provided, kill script
//No reason to pass an additional error here, since the query won't work without the $Project_ID variable
}
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>View Project Details</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
  </head>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
		include (GEN_INCLUDES_PATH . 'Left_Menu.html');
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');

		$q_get_project = "SELECT project_name, business_name, program, program_id, project_status,
						address1, address2, city, state, zip, Project_Cost, Incentive, KWH_Savings, contact_name, contact_phone, Installation_Contact_Name, Installation_Contact_Phone, 
						salesperson_id, pm_id
						FROM project
						WHERE project_ID = $Project_ID";
		if (!($r_get_project = $mysqli->query($q_get_project)) ) {
			echo ' <div class="right_col" role="main"><div class="alert alert-danger">There was an error with the query.  If this problem persists please email the site admin at mfox@energyresourcesusa.net</div></div>';
		}
		if ($r_get_project->num_rows == 1) {
			$row = $r_get_project->fetch_array(MYSQLI_ASSOC);
		?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Project Overview</h3>
              </div>
              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <!--<div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
						<button class="btn btn-default" type="button">Go!</button>
					</span>
                  </div>-->
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
				  <?php
                    echo'<h3 class="green">' . $row['project_name'] . ' (' . $row['project_status'] . ') </h3> 
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<div class="col-md-4 col-sm-4 col-xs-6"> <!--The First Column-->
                        <div class="panel-body">
                          <div class="project_detail">';
							//Adding a check for business name
							if (($row['business_name'] !== "") && ($row['business_name'] !== NULL)) {
                            echo '<p class="title"><u>Business Name</u></p>
                            <p>' . $row['business_name'] . '</p>';
							}
                            echo '<p class="title"><u>Program ID</u></p>
                            <p>' . $row['program_id'] . '</p>
							<p class="title"><u>Address</u></p>
                            <p>' . $row['address1'] . '<br /> ' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zip'] . '</p>
							<p class="title"><u>Contact Name</u></p>
                            <p>' . $row['contact_name'] . '</p>
                          </div>
                          <br />
                          <div class="text-center">
                            <a href="Edit_Project.php?proj_id='. $Project_ID . '" class="btn btn-md btn-primary">Edit Project</a>
                            <a href="https://sow.jkenergysolutions.net/edit_project.php?proj_id=' . $Project_ID . '" class="btn btn-md btn-warning" target="_blank">View SOW</a>
                          </div>
                        </div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-6"> <!--The Second Column-->
                        <div class="panel-body">
                          <div class="project_detail">';
							//Adding a check for business name
							if (($row['Project_Cost'] !== "") && ($row['Project_Cost'] !== NULL)) {
                            echo '<p class="title"><u>Project Value:</u> $'.$row['Project_Cost'].' </p><p></p>';
							}
                            echo '<p class="title"><u>Incentive:</u> $'.$row['Incentive'].' </p><p></p>
							<p class="title"><u>KwH Savings:</u> '.$row['KWH_Savings'].' </p><p></p>
							<!--
							<p class="title"><u>Energy Savings:</u>  </p><p></p>
							<p class="title"><u>Material Cost:</u> $ </p><p></p>
							<p class="title"><u>Labor Cost:</u> $ </p><p></p>
							<p class="title"><u>Miscellaneous Cost:</u> $ </p><p></p>
							-->';
							?>
                          </div>
                          <br/>
                        </div>
					</div>
					<!--The Third Column-->
					<div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="panel-body">
                          <div class="project_detail">
						  <?php
							echo'<!--
							<p class="title"><u>Gross Margin:</u> $3,425 </p><p></p>
							-->';
							$InKWH = round(($row['Incentive'] / $row['KWH_Savings']), 3);
							echo'<p class="title"><u>Incentive/KwH Saved:</u> '.$InKWH.' </p><p></p>';
						  ?>
                          </div>
                          <br/>
                        </div>
					</div>
					
					<?php
					$Q_Get_Dates = "SELECT CreateDate, MovedToWSP, DateSigned, MovedToSNTP, UtilityApprovalDate, MovedToInProduction, PMWalkDate, 
									MaterialsOrderedDate, InstallDate, MovedToInstalled, SignedFinalsRecieved, MovedToInvoiced, PaidDate
									FROM project
									WHERE project_ID = $Project_ID";
					$R_Get_Dates = $mysqli->query($Q_Get_Dates);
					$Dates = $R_Get_Dates->fetch_array(MYSQLI_ASSOC);
					$Q_Get_Notes = "SELECT m.Message, MONTHNAME(m.Create_Date) as Month, DAY(m.Create_Date) as Day, m.Author_ID, CONCAT(u.first_name, ' ', u.last_name) AS Name, u.Avatar
									FROM Messages m
									JOIN user u ON (m.Author_ID = u.user_id)
									WHERE Project_ID = $Project_ID
									AND Message_Type = 'Project'
									AND Completed = 0
									ORDER BY Message_ID DESC";
					$R_Get_Notes = $mysqli->query($Q_Get_Notes);
					echo'
					<!-- Project Notes -->
					<div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="x_title">
						  <h2>Project Notes</h2><a class="btn btn-dark btn-md pull-right" data-toggle="modal" data-target=".bs-example-modal-lg">Add Comment</a>
						  <div class="clearfix"></div>
						</div>
					<div class="x_content">
						<div id="MessageHeader">
							<ul class="messages">';
							while($Notes = $R_Get_Notes->fetch_array(MYSQLI_ASSOC)){
								$URL = $Notes['Avatar'] ? $Notes['Author_ID'] : "Default"; //If the author has an avatar, set the URL to their ID.  Otherwise use default.  
							  echo'<li>
								<img src="images/Avatars/' . $URL . '" class="avatar" alt="Avatar">
								<div class="message_date">
								  <h3 class="date text-info">' . $Notes['Day'] . '</h3>
								  <p class="month">' . $Notes['Month'] . '</p>
								</div>
								<div class="message_wrapper">
								  <h4 class="heading">' . $Notes['Name'] . '</h4>
								  <blockquote class="message">' . $Notes['Message'] . '</blockquote>
								  <br />
								</div>
							  </li>';
							}
							echo'</ul>
							</div>
                        </div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12"> <!--The Fourth Column-->
						<div class="x_title">
						  <h2>Project History</h2>
						  <div class="clearfix"></div>
						</div>
						<div class="x_content">
						  <ul class="list-unstyled timeline">';
							arsort($Dates);	//Sorting the array in order from lowest to highest
						  foreach($Dates as $key => $value){
							if ($value !== NULL) {
							$FDate = date("m-d-Y", strtotime($value));
							$FKey = preg_replace('/([a-z])([A-Z])/U', '$1 $2', $key); 
							echo'<li>
							  <div class="block">
								<div class="tags">
								  <a class="tag">';
								  echo'<span>' . $FDate . '</span>';
								  echo'
								  </a>
								</div>
								<div class="block_content">
								  <h2 class="title">' . $FKey . '</h2>
								  <div class="byline"></div>
								  <div class="byline"></div>
								</div>
							  </div>
							</li>';
							}
						  }
						  ?>
						  </ul>
						</div>
					</div>
                    <!-- end project-detail sidebar -->
					<!-- Starting the modal -->
					<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title" id="myModalLabel2">Create Project Note</h4>
								</div>
								<div class="modal-body">
									<h4>Enter comment below:</h4>
									<input id="CommentInput" class="form-control col-md-6 col-xs-12" placeholder="Comment Text">
								</div>
								<br/><br/>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-dark" data-dismiss="modal" onclick="AddNote()">Add Note</button>
								</div>
							</div>
						</div>
					</div>
					<!--End Modal-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		<?php
		}
		?>
        <!-- /page content -->
        <!-- footer content -->
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
	<script>
	function AddNote(){
		var Comment = $("#CommentInput").val();
		var UserID = <? echo $UserID ?>;
		var Project = <? echo $Project_ID ?>;
		var UserURL = <? echo $UserURL ?>; //Getting the URL for the user
		$.ajax({
			url: "AddComment.php",
			type: "POST",
			data: {Comment: Comment, UserID: UserID, Project: Project},
			success: function(data){
				$("#MessageHeader ul").prepend('<li><img src="images/Avatars/' + UserURL + '" class="avatar" alt="Avatar"><div class="message_date"><h3 class="date text-info"></h3><p class="month">Just Now</p></div><div class="message_wrapper"><h4 class="heading">You</h4><blockquote class="message">' + Comment + '</blockquote><br /></div></li>');
			},
			error: function(){
				alert("The comment was not added");
			}
		});
	}
	</script>
	
  </body>
</html>