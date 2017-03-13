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
//Dealing with Profile ID's
if ( (isset($_GET['Profile_ID']))  && (is_numeric($_GET['Profile_ID'])) ) { //source is from projects.php
	$Profile_ID = $_GET['Profile_ID'];
}
else if ( (isset($_POST['Profile_ID'])) && (is_numeric($_POST['Profile_ID'])) ) { //data being passed via form submission
	$Profile_ID = $_POST['Profile_ID'];
}
else { //If the Profile ID isn't set, then just redirect to the users own profile.  
	$Profile_ID = $UserID; 
}
$Q_Profile_Info = "SELECT first_name, last_name, Usergroup, Avatar FROM user
                    WHERE user_id = $Profile_ID";
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
	<!-- Dropzone.js -->
    <link href="../vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

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
				<?php
                echo'<h3>User Profile: '.$ProfileName.'</h3>';
				?>
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
						  <?php
						  $AvatarURL = $ProfileInfo[3] ? $Profile_ID : "Default";
							  echo'<img class="img-responsive avatar-view" src="images/Avatars/'.$AvatarURL.'.jpg" alt="Avatar" title="Change the avatar">';
						  ?>
                        </div>
                      </div>
					  <?php
                      echo'<h3>'.$ProfileName.'</h3>';
					  ?>
                      <ul class="list-unstyled user_data">
                        <li><i class="fa fa-map-marker user-profile-icon"></i> Thomaston, CT, USA
                        </li>

                        <li>
						<?php
                          echo'<i class="fa fa-briefcase user-profile-icon"></i> '. $ProfileInfo[2].'';
						?>
						  </li>
                      </ul>
					  <?php
					  if ($Profile_ID == $UserID) {
						echo'<a class="btn btn-success btn-md" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="fa fa-edit m-right-xs"></i> Edit Profile Picture</a>';
					  }
					  ?>
                      <br />

                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">

                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>User Activity Report</h2>
                        </div>
                        <div class="col-md-6">
                          <div id="reportrange" class="pull-right" style="margin-top: 5px; background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #E6E9ED">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                          </div>
                        </div>
                      </div>
                      <!-- start of user-activity-graph -->
                      <div id="graph_bar" style="width:100%; height:280px;"></div>
                      <!-- end of user-activity-graph -->

                      <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Projects Worked On</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Recent Activity</a>
                          </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
						  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab">
                            <!-- start user projects -->
                            <table class="data table table-striped no-margin">
                              <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Project Name</th>
                                  <th>Client Company</th>
                                  <th>Current Status</th>
                                  <th>Utilities</th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
							  $Q_ProjectsWorkedOn = "SELECT PM.Project_ID, p.project_name, p.business_name, p.project_status
								FROM ProjectMembers PM
								JOIN project p ON (PM.Project_ID = p.project_ID)
								WHERE PM.User_ID = $Profile_ID
								AND p.Deleted = 0";
							  $R_ProjectsWorkedOn = $mysqli->query($Q_ProjectsWorkedOn);
							  $Increment = 1;
							  while ($PWO = $R_ProjectsWorkedOn->fetch_array(MYSQLI_ASSOC)){
								echo'<tr>
								<td>'.$Increment.'</td>
								<td>'.$PWO['project_name'].'</td>
								<td>'.$PWO['business_name'].'</td>
								<td>'.$PWO['project_status'].'</td>
								<td class="vertical-align-mid">';
								//Only Administrators, Project Developers, or Project Managers can see Project Overview
								if ($UserInfo[2] == "Administrator" || $UserInfo[2] == "Project Developer" || $UserInfo[2] == "Project Manager"){
								  echo'<a href="Project_Detail.php?proj_id='.$PWO['Project_ID'].'" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Overview</a>';
								}
								echo'<a href="Edit_Project.php?proj_id='.$PWO['Project_ID'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
								</td>
								</tr>';
							  $Increment++;
							  }
							  ?>
                              </tbody>
                            </table>
                            <!-- end user projects -->

                          </div>
                          <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="home-tab">

                            <!-- start recent activity -->
                            <ul class="messages">
							<?php
							  $Q_RecentActivity = "SELECT M.Message, MONTHNAME(M.Create_Date) as Month, DAY(M.Create_Date) as Day, p.project_name
								FROM Messages M
								JOIN project p ON (M.Project_ID = p.project_ID)
								WHERE M.Author_ID = $Profile_ID
								AND M.Message_Type = 'Project'";
							  $R_RecentActivity = $mysqli->query($Q_RecentActivity);
							  $Increment = 1;
							  while ($RecentActivity = $R_RecentActivity->fetch_array(MYSQLI_ASSOC)){
                              echo'<li>
                                <img src="images/Avatars/'.$AvatarURL.'.jpg" class="avatar" alt="Avatar">
                                <div class="message_date">
                                  <h3 class="date text-info">'.$RecentActivity['Day'].'</h3>
                                  <p class="month">'.$RecentActivity['Month'].'</p>
                                </div>
                                <div class="message_wrapper">
                                  <h4 class="heading">'.$ProfileName.' <i>regarding the project</i> "'.$RecentActivity['project_name'].'"</h4>
                                  <blockquote class="message">'.$RecentActivity['Message'].'</blockquote>
                                  <br />
                                  <p class="url">
                                    <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                                  </p>
                                </div>
                              </li>';
							  }
							  ?>

                            </ul>
                            <!-- end recent activity -->

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
				  <!-- Starting the modal -->
				  <div id="ImageModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-lg">
					  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						  <h4 class="modal-title" id="myModalLabel2">Change Profile Picture</h4>
						</div>
						<div class="modal-body">
						  <form action="includes/ProfilePictureUpload.php" class="dropzone"></form>
					    </div>
					  <br/><br/>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close Window</button>
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
	<!-- Dropzone.js -->
    <script src="../vendors/dropzone/dist/min/dropzone.min.js"></script>
	
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <script>
      $(function() {
        Morris.Bar({
          element: 'graph_bar',
          data: [
            { "period": "Jan", "Projects Sold": 5 }, 
            { "period": "Feb", "Projects Sold": 8 }, 
            { "period": "Mar", "Projects Sold": 15 }, 
            { "period": "Apr", "Projects Sold": 7 }, 
            { "period": "May", "Projects Sold": 14 }, 
            { "period": "Jun", "Projects Sold": 17 }, 
            { "period": "Jul", "Projects Sold": 15 }, 
            { "period": "Aug", "Projects Sold": 15 }, 
            { "period": "Sep", "Projects Sold": 10 }, 
            { "period": "Oct", "Projects Sold": 14 }
          ],
          xkey: 'period',
          hideHover: 'auto',
          barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
          ykeys: ['Projects Sold'],
          labels: ['Projects Sold'],
          xLabelAngle: 60,
          resize: true
        });

        $MENU_TOGGLE.on('click', function() {
          $(window).resize();
        });
      });
    </script>

    <!-- datepicker -->
    <script type="text/javascript">
      $(document).ready(function() {

        var cb = function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
        }

        var optionSet1 = {
          startDate: moment().subtract(29, 'days'),
          endDate: moment(),
          minDate: '01/01/2012',
          maxDate: '12/31/2015',
          dateLimit: {
            days: 60
          },
          showDropdowns: true,
          showWeekNumbers: true,
          timePicker: false,
          timePickerIncrement: 1,
          timePicker12Hour: true,
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          opens: 'left',
          buttonClasses: ['btn btn-default'],
          applyClass: 'btn-small btn-primary',
          cancelClass: 'btn-small',
          format: 'MM/DD/YYYY',
          separator: ' to ',
          locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
          }
        };
        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker(optionSet1, cb);
        $('#reportrange').on('show.daterangepicker', function() {
          console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function() {
          console.log("hide event fired");
        });
        $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
          console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
          console.log("cancel event fired");
        });
        $('#options1').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        });
        $('#options2').click(function() {
          $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
        });
        $('#destroy').click(function() {
          $('#reportrange').data('daterangepicker').remove();
        });
      });
    </script>
    <!-- /datepicker -->
	
	<!-- Image Upload -->
	<script>
	$('#ProfilePicture').click(function() {
	alert("hi");
	}
	</script>
  </body>
</html>