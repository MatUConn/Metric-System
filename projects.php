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

if (isset($_GET['search_query'])) {
	$Search = '%' . $_GET['search_query'] . '%';
	$q = "SELECT project_name, program_id, CreateDate,
				CONCAT(SUBSTRING(CreateDate,1,10))
				AS formatted_date, project_id, project_status
				FROM project
				WHERE Deleted = 0
				AND project_name LIKE '$Search' OR program_id LIKE '$Search'
				ORDER BY project_id DESC
				LIMIT 500";
}
else{
	$q = "SELECT project_name, CreateDate,
				CONCAT(SUBSTRING(CreateDate,1,10))
				AS formatted_date, project_id, project_status
				FROM project
				WHERE Deleted = 0
				ORDER BY project_id DESC
				LIMIT 50";
}
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project Search</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php
		include (GEN_INCLUDES_PATH . 'Left_Menu.html');
        ?>

        <!-- top navigation -->
		<?php
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');
        ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Project Search</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
				<form action="projects.php?<? $_GET['search_query'] ?>" method="GET">
                  <div class="input-group">
                    <input type="text" name="search_query" class="form-control" placeholder="Search for..." 
					<?php
					if(!empty($_GET['search_query'])) {
						echo 'value="' . $_GET['search_query'] . '"';
					}
					?>>
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="submit">Go!</button>
                    </span>
                  </div>
				</form>
                </div>
              </div>
            </div>
            
            <div class="clearfix"></div>
			<?php
			$r = $mysqli->query($q);
			if ($r->num_rows > 0) {
			?>
			
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Project Listing</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <!-- start project list -->
                    <table class="table table-striped projects">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Project Name</th>
                          <th width="155">Team Members</th>
                          <th>Status</th>
                          <th>Project Progress</th>
                          <th width="216">Utilities</th> <!-- 216 is the lowest it can be to ensure that all three buttons are adjacent -->
                        </tr>
                      </thead>
                      <tbody>
					  <?php
					  while($row = $r->fetch_array(MYSQLI_ASSOC)){
					  $ProjID = $row['project_id'];
						$Q_Get_Members = "SELECT PM.User_ID, U.Avatar, CONCAT(U.first_name, ' ', U.last_name) AS 'full_name'
							FROM ProjectMembers PM
							JOIN user U ON (U.user_id = PM.User_ID)
							WHERE Project_ID = $ProjID
							LIMIT 3";
						$R_Get_Members = $mysqli->query($Q_Get_Members);
					  
                        echo'<tr id="' . $row['project_id'] . '">
                          <td>' . $row['project_id'] . '</td>
                          <td>
                            <a>' . $row['project_name'] . '</a>
                            <br />
                            <small>Created ' . $row['formatted_date'] . '</small>
                          </td>
                          <td>
                            <ul class="list-inline">';
							while($Members = $R_Get_Members->fetch_array(MYSQLI_ASSOC)){
								if ($Members['Avatar']) {
								echo'	<li>
										<img src="images/Avatars/' . $Members['User_ID'] . '.jpg" class="avatar" alt="Avatar" title="' . $Members['full_name'] . '">
									</li>';
								}
								else {
								echo'	<li>
										<img src="images/Avatars/Default.jpg" class="avatar" alt="Avatar" title="' . $Members['full_name'] . '">
									</li>';
								}
							}
					echo'	</ul>
                          </td>
                          <td>';
						switch ($row['project_status']) {
							case "In Development":
								echo '<button type="button" class="btn btn-default btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 10;
								break;
							case "With Seller/Proposed":
								echo '<button type="button" class="btn btn-primary btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 30;
								break;
							case "Signed; Not To Production":
								echo '<button type="button" class="btn btn-success btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 50;
								break;
							case "In Production":
								echo '<button type="button" class="btn btn-info btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 65;
								break;
							case "Installed":
								echo '<button type="button" class="btn btn-warning btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 80;
								break;
							case "Invoiced":
								echo '<button type="button" class="btn btn-danger btn-xs">' . $row['project_status'] . '</button>';
								$ProgressBar = 100;
								break;
							default: 
								echo '<button type="button" class="btn btn-dark btn-xs">Unknown</button>';
								$ProgressBar = 0;
								break;
						}
                            
                          echo'</td>
						  <td class="project_progress">
                            <div class="progress progress_sm">
                              <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="' . $ProgressBar . '"></div>
                            </div>
                          </td>
                          <td>';
							//Only Administrators, Project Developers, or Project Managers can see Project Overview
							if ($UserInfo[2] == "Administrator" || $UserInfo[2] == "Project Developer" || $UserInfo[2] == "Project Manager"){
								echo'<a href="Project_Detail.php?proj_id='. $row['project_id'] . '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Overview</a>';
							}
							echo'<a href="Edit_Project.php?proj_id='. $row['project_id'] . '" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
							//Only Administrators can delete the projects
							if ($UserInfo[2] == "Administrator"){
								echo'<a class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-example-modal-sm" onclick="GetPID(' . $row['project_id'] . ')"><i class="fa fa-trash-o"></i> Delete</a>';
							}
                          echo'</td>
                        </tr>';
					  
					  }
					  ?>
                      </tbody>
                    </table>
					<?php
					}
					?>
                    <!-- end project list -->
					<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title" id="myModalLabel2">Delete Project?</h4>
								</div>
								<div class="modal-body">
									<h4>Are you sure?</h4>
									<p>Deleting a project will remove all audits and measures associated with the project for the Metric System and SOW.  </p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<?php
									echo'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="DeleteButton()">Delete Project</button>';
									?>
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
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
	<script>
	var Project;
	function GetPID(PID){
		Project = PID;
	}
	function DeleteButton(){
		$.ajax({
			url: "DeleteProject.php",
			type: "POST",
			data: {Project: Project},
			success: function(data){
				$("#" + Project + "").remove();
			},
			error: function(){
				alert("The project was not deleted");
			}
		});
	}
	</script>
  </body>
</html>