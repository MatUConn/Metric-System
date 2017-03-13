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
//Dealing with Salesperson ID's
if ( (isset($_GET['Salesperson_ID']))  && (is_numeric($_GET['Salesperson_ID'])) ) {
	$Salesperson_ID = $_GET['Salesperson_ID'];
}
else if ( (isset($_POST['Salesperson_ID'])) && (is_numeric($_POST['Salesperson_ID'])) ) { //data being passed via form submission
	$Salesperson_ID = $_POST['Salesperson_ID'];
}
else { //an invalid Salesperson ID was provided, kill script
//No reason to pass an additional error here, since the query won't work without the $Salesperson_ID variable
}
$Q_Profile_Info = "SELECT first_name, last_name, role, phone_num 
					FROM contact
                    WHERE contact_id = $Salesperson_ID";
$R_Profile_Info = $mysqli->query($Q_Profile_Info);
$ProfileInfo = $R_Profile_Info->fetch_array(MYSQLI_NUM);
$ProfileName = $ProfileInfo[0] . ' ' . $ProfileInfo[1];
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
    echo'<title>' . $ProfileName . '\'s Profile</title>';
	?>
    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Salesperson Profile</h3>
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
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
							<img class="img-responsive avatar-view" src="images/Avatars/Default.jpg" alt="Avatar" title="Change the avatar">
                        </div>
                      </div>
					  <?php
                      echo'<h3>' . $ProfileName . '</h3>';
					  ?>
                      <ul class="list-unstyled user_data">
                        <!--<li><i class="fa fa-map-marker user-profile-icon"></i> Wellesley, MA
                        </li>-->
                        <li>
						<?php
                          echo'<i class="fa fa-briefcase user-profile-icon"></i> ' . $ProfileInfo[2] . '';
						?>
						</li>
						<li>
						<?php
                          echo'<i class="fa fa-phone user-profile-icon"></i> ' . $ProfileInfo[3] . '';
						?>
						</li>
                      </ul>

                      <!--<a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>-->
                      <br />

                      <!-- start skills -->
                      <h4>Sales by State (This Year)</h4>
					  <ul class="list-unstyled user_data">
						<?php
						$Q_Get_States = "SELECT SUM(Project_Cost) as cost, state
							FROM project
							WHERE salesperson_id = $Salesperson_ID
							AND YEAR(DateSigned)= YEAR(Now())
							AND Deleted = 0
							GROUP BY state
							ORDER BY cost DESC";
						$R_Get_States = $mysqli->query($Q_Get_States);
						$Highest = 0;
						while($State = $R_Get_States->fetch_array(MYSQLI_NUM)){
							if($State[0] >= $Highest){
								$Highest = number_format(round($State[0]));
							}
						$Percent = ($State[0] / $Highest) * 100;
						?>
                        <li>
						<?php
                          echo'<p>' . $State[1] . ' - $' .  number_format(round($State[0])) . '</p>';
						?>
                          <div class="progress progress_sm">
						  <?php
                            echo'<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="' . $Percent . '"></div>';
						  ?>
                          </div>
                        </li>
						<?php
						}
						?>
                      </ul>
                      <!-- end of skills -->

                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">

                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>Sales Activity Report (Past 6 Months)</h2>
                        </div>
                        <div class="col-md-6">
<!--                          <div id="reportrange" class="pull-right" style="margin-top: 5px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #E6E9ED">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                          </div>-->
                        </div>
                      </div>
                      <!-- start of user-activity-graph -->
                      <div id="graph_bar" style="width:100%; height:280px;"></div>
                      <!-- end of user-activity-graph -->

                      <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Recent Activity</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                            <!-- start user projects -->
                            <table id="DataTable" class="table table-striped table-bordered dt-responsive nowrap">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Project Name</th>
                                  <th>Create Date</th>
                                  <th>Project Cost</th>
								  <th>Project Status</th>
                                  <th>Location</th>
                                </tr>
                              </thead>
                              <tbody>
								<?php
								$Q_Recent_Projects = "SELECT project_ID, project_name, CONCAT(SUBSTRING(CreateDate,1,10))
									AS formatted_date, Project_Cost, project_status, CONCAT(city, ', ', state) AS 'location'
									FROM project
									WHERE salesperson_id = $Salesperson_ID
									ORDER BY project_ID DESC";
								$R_Recent_Projects = $mysqli->query($Q_Recent_Projects);
								$i = 1;
								while($Recents = $R_Recent_Projects->fetch_array(MYSQLI_NUM)){
								echo'
                                <tr>
                                  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">' . $i . '</td>
                                  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">' . $Recents[1] . '</a></td>
                                  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">' . $Recents[2] . '</td>
                                  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">$' . number_format(round($Recents[3])) . '</td>
								  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">' . $Recents[4] . '</td>
                                  <td><a href="Edit_Project.php?proj_id='. $Recents[0] . '">' . $Recents[5] . '</td>
                                </tr>';
								$i++;
								}
								?>
                              </tbody>
                            </table>
                            <!-- end user projects -->

                          </div>
                        </div>
                      </div>
                    </div>
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
    <!-- morris.js -->
    <script src="../vendors/raphael/raphael.min.js"></script>
    <script src="../vendors/morris.js/morris.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
	    <!-- Datatables -->
    <script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	
	<?php
	$Q_Get_SoldCount = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE salesperson_id = $Salesperson_ID
	AND Deleted = 0";
	$R_Get_SoldCount = $mysqli->query($Q_Get_SoldCount);
	$Sold = $R_Get_SoldCount->fetch_array(MYSQLI_NUM);
	?>
	
    <script>
	TodayDate = new Date();
	TodayMonth = TodayDate.getMonth();
	var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
      $(function() {
        Morris.Bar({
          element: 'graph_bar',
          data: [
            { "period": monthNames[TodayMonth - 6], "Total Cost":<?php echo json_encode(round($Sold[6])); ?> }, 
            { "period": monthNames[TodayMonth - 5], "Total Cost":<?php echo json_encode(round($Sold[5])); ?> }, 
            { "period": monthNames[TodayMonth - 4], "Total Cost":<?php echo json_encode(round($Sold[4])); ?> }, 
            { "period": monthNames[TodayMonth - 3], "Total Cost":<?php echo json_encode(round($Sold[3])); ?> }, 
            { "period": monthNames[TodayMonth - 2], "Total Cost":<?php echo json_encode(round($Sold[2])); ?> }, 
            { "period": monthNames[TodayMonth - 1], "Total Cost":<?php echo json_encode(round($Sold[1])); ?> },
			{ "period": monthNames[TodayMonth], "Total Cost":<?php echo json_encode(round($Sold[0])); ?> }
          ],
          xkey: 'period',
          hideHover: 'auto',
          barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
          ykeys: ['Total Cost'],
          labels: ['Total Cost'],
          xLabelAngle: 60,
          resize: true
        });

        $MENU_TOGGLE.on('click', function() {
          $(window).resize();
        });
      });
    </script>

	<!--Datatables-->
	<script>
	$(document).ready(function() {
    var table = $('#DataTable').DataTable( {
		dom: "Blfrtip",
        buttons: [],
		"iDisplayLength": 25
	} );
	} );
    </script>
  </body>
</html>