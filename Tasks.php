<?php
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');
$UserID = $sow_user->getUserID();
$q_get_user_info = "SELECT first_name, last_name, Usergroup, Avatar FROM user
                    WHERE user_id = $UserID";
$r_get_user_info = $mysqli->query($q_get_user_info);
$UserInfo = $r_get_user_info->fetch_array(MYSQLI_NUM);
$Username = $UserInfo[0] . ' ' . $UserInfo[1];
//Dealing with Task ID's
if ((isset($_GET['Task_ID']))  && (is_numeric($_GET['Task_ID']))){
	$Task_ID = $_GET['Task_ID'];
	$SingleBool = true;
}
else if ((isset($_POST['Task_ID'])) && (is_numeric($_POST['Task_ID']))){
	$Task_ID = $_POST['Task_ID'];
	$SingleBool = true;
}
else {
//Get Tasks
$Q_Get_Tasks = "SELECT m.Project_ID, m.Message, TIMESTAMPDIFF(DAY, m.Create_Date, NOW()) AS Date, p.project_name
		FROM Messages m
		JOIN Message_Recipients mr ON (m.Message_ID = mr.Message_ID)
		INNER JOIN project p ON (m.Project_ID = p.project_ID)
		WHERE m.Message_Type = 'Task'
		AND m.Completed = 0
		AND mr.Recipient_ID = '$UserID'
		ORDER BY m.Create_Date DESC";
$R_Tasks = $mysqli->query($Q_Get_Tasks);
$SingleBool = false;
//Get Users
$Q_Get_Users = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS 'full_name'
		FROM user
		WHERE enabled_flag = '1'
		ORDER BY full_name";
$R_Get_Users = $mysqli->query($Q_Get_Users);
}
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tasks</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">

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
        ?>
		
		<!-- page content -->
		<div class="right_col" role="main">
		<div class="row">
		  <!-- Start to do list -->
                <div class="col-md-12 col-sm-12 col-xs-12">
				<?php
				if ($SingleBool == false){
				?>
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Tasks assigned to you</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
					  <div class="row">
						<div class="col-md-6"><h4>Task Description</h4></div>
						<div class="col-md-3"><h4>Project Name</h4></div>
						<div class="col-md-2"><h4>Date</h4></div>
						<!--<div class="col-md-2"><a class="btn btn-default btn-md" data-toggle="modal" data-target=".bs-example-modal-lg">Create a New Task</a></div>-->
					  </div>
                      <ul class="to_do">
						<?php
						if($R_Tasks->num_rows == 0) {
							echo'You do not have any tasks assigned to you currently';
						}
						else {
							while($Tasks = $R_Tasks->fetch_array(MYSQLI_ASSOC)){
								echo '	<li>
										  <div class="row">
											<div class="col-md-6">
											<p><a href="Edit_Project.php?proj_id='. $Tasks['Project_ID'] . '">' . $Tasks['Message'] . '</a></p>
											</div>
											<div class="col-md-3">
												<span class="time"><a href="Edit_Project.php?proj_id='. $Tasks['Project_ID'] . '">' . $Tasks['project_name'] . '</a></span>
											</div>
											<div class="col-md-2">';
											if ($Tasks['Date'] <= 0){
												echo'<span class="time"><a href="Edit_Project.php?proj_id='. $Tasks['Project_ID'] . '">Today</a></span>';
											}
											else {
												echo'<span class="time"><a href="Edit_Project.php?proj_id='. $Tasks['Project_ID'] . '">' . $Tasks['Date'] . ' day(s) ago</a></span>';
											}
											echo'
											</div>
										  </div>
										</li>';
							}
						}
						?>
                      </ul>
                    </div>
                  </div>
				<?php
				}
				else {
				?>
				<div class="x_panel">
                    <div class="x_title">
                      <h2>Task</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
					<?
					$Q_Get_Tasks = "SELECT m.Message_ID, m.Message, m.Create_Date, m.Project_ID, m.Completed, CONCAT(u.first_name, ' ', u.last_name) AS 'full_name', p.project_name
						FROM Messages m
						JOIN Message_Recipients mr ON (m.Message_ID = mr.Message_ID)
						JOIN user u ON (m.Author_ID = u.user_id)
						JOIN project p ON (m.Project_ID = p.project_ID)
						WHERE m.Message_ID = '$Task_ID'
						AND m.Message_Type = 'Task'
						AND mr.Recipient_ID = '$UserID'
						ORDER BY m.Message_ID";
					$R_Tasks = $mysqli->query($Q_Get_Tasks);
					$Tasks = $R_Tasks->fetch_array(MYSQLI_NUM);
					?>
					<br />
                    <form class="form-horizontal form-label-left input_mask">
					
					<blockquote>
						<?php
                        echo '<p><b><a href="Edit_Project.php?proj_id='.$Tasks[3].'">View Project: '.$Tasks[6].'</a></b></p>
						<p>"' . $Tasks[1] . '"</p>
						
                        <footer><cite>' . $Tasks[5] . '</cite> on ' . $Tasks[2] . '</footer>';
						?>
                    </blockquote>
                    </form>
                    </div>
                  </div>
				<?php
				}
				?>
                </div>
			</div>
          <!-- End to do list -->
		</div>
		
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
	<!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
	<!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_multiple").select2({
		  placeholder: "Recipients",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
	<script type="text/javascript">
		function CreateTask(LineNum, AuthorID){
			var CommentText = prompt("Enter a comment for line number " + LineNum + ":");
			if ((CommentText == null) || (CommentText == "")){
				//Do nothing because there was no input
			}
			else {
				var ID = "ImageID";
				ID = ID + MLID;
					$.ajax({
						url: "AddComment.php",
						type: "POST",
						data: {CommentText: CommentText, MLID: MLID, EntityType: EntityType, AuthorID: AuthorID},
						success: function(data){
							$("#ImageID"+MLID).attr("src", "/images/Add_Measure_Comment_Fill_Icon.png");
						},
						error: function(){
							alert("The measure was not changed");
						}
					});
					
			}
		}
	</script>	
  </body>
</html>