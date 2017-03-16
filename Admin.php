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
?>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Administration </title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php
		include (GEN_INCLUDES_PATH . 'DashboardFunctions.php');
		include (GEN_INCLUDES_PATH . 'Left_Menu.html');
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');
        ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="row">
              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="x_panel fixed_height_320">
                  <div class="x_title">
                    <h2>App Devices <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <h4>App Versions</h4>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>1.5.2</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 66%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span>123k</span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>1.5.3</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span>53k</span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>1.5.4</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span>23k</span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>1.5.5</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 5%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span>3k</span>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="widget_summary">
                      <div class="w_left w_25">
                        <span>0.1.5.6</span>
                      </div>
                      <div class="w_center w_55">
                        <div class="progress">
                          <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 2%;">
                            <span class="sr-only">60% Complete</span>
                          </div>
                        </div>
                      </div>
                      <div class="w_right w_20">
                        <span>1k</span>
                      </div>
                      <div class="clearfix"></div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="x_panel fixed_height_320">
                  <div class="x_title">
                    <h2>Team Member Activity</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
                      <th style="width:37%;">
                        <p>Top 5</p>
                      </th>
                      <th>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                          <p class="">Device</p>
                        </div>
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                          <p class="">Progress</p>
                        </div>
                      </th>
                    </tr>
                    <tr>
                      <td>
                        <canvas id="canvas1" height="140" width="140" style="margin: 15px 10px 10px 0"></canvas>
                      </td>
                      <td>
                        <table class="tile_info">
                          <tr>
                            <td>
                              <p><i class="fa fa-square blue"></i>IOS </p>
                            </td>
                            <td>30%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square green"></i>Android </p>
                            </td>
                            <td>10%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square purple"></i>Blackberry </p>
                            </td>
                            <td>20%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square aero"></i>Symbian </p>
                            </td>
                            <td>15%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square red"></i>Others </p>
                            </td>
                            <td>30%</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="x_panel fixed_height_320">
                  <div class="x_title">
                    <h2>Profile Settings <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="dashboard-widget-content">
                      <ul class="quick-list">
                        <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a></li>
                        <li><i class="fa fa-thumbs-up"></i><a href="#">Favorites</a></li>
                        <li><i class="fa fa-calendar-o"></i><a href="#">Activities</a></li>
                        <li><i class="fa fa-cog"></i><a href="#">Settings</a></li>
                        <li><i class="fa fa-area-chart"></i><a href="#">Logout</a></li>
                      </ul>

                      <div class="sidebar-widget">
                        <h4>Profile Completion</h4>
                        <canvas width="150" height="80" id="foo" class="" style="width: 160px; height: 100px;"></canvas>
                        <div class="goal-wrapper">
                          <span id="gauge-text" class="gauge-value pull-left">0</span>
                          <span class="gauge-value pull-left">%</span>
                          <span id="goal-text" class="goal-value pull-right">100%</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-sm-6 col-xs-12 widget_tally_box">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>User Uptake</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <div id="graph_bar" style="width:100%; height:200px;"></div>

                    <div class="col-xs-12 bg-white progress_summary">

                      <div class="row">
                        <div class="progress_title">
                          <span class="left">Escudor Wireless 1.0</span>
                          <span class="right">This sis</span>
                          <div class="clearfix"></div>
                        </div>

                        <div class="col-xs-2">
                          <span>SSD</span>
                        </div>
                        <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="89"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
                          <span>89%</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="progress_title">
                          <span class="left">Mobile Access</span>
                          <span class="right">Smart Phone</span>
                          <div class="clearfix"></div>
                        </div>

                        <div class="col-xs-2">
                          <span>App</span>
                        </div>
                        <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="79"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
                          <span>79%</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="progress_title">
                          <span class="left">WAN access users</span>
                          <span class="right">Total 69%</span>
                          <div class="clearfix"></div>
                        </div>

                        <div class="col-xs-2">
                          <span>Usr</span>
                        </div>
                        <div class="col-xs-8">
                          <div class="progress progress_sm">
                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="69"></div>
                          </div>
                        </div>
                        <div class="col-xs-2 more_info">
                          <span>69%</span>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

              <!-- Start users list -->
			  <?
			  $Q_Get_UserOdd = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS 'FullName', email, Usergroup, Avatar, LastLoggedIn
						FROM user
						WHERE (user_id % 2 > 0)
						ORDER BY user_id";
			  $R_Get_UserOdd = $mysqli->query($Q_Get_UserOdd);
			  $Q_Get_UserEven = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS 'FullName', email, Usergroup, Avatar, LastLoggedIn
						FROM user
						WHERE (user_id % 2 = 0)
						ORDER BY user_id";
			  $R_Get_UserEven = $mysqli->query($Q_Get_UserEven);
			  ?>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Users</h2>
                      <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                      </ul>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
					
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <ul class="to_do">
						<?
						while($UsersOdd = $R_Get_UserOdd->fetch_array(MYSQLI_ASSOC)){
						  echo'
                          <li>
						  <a data-toggle="modal" data-target=".bs-example-modal-lg">
                            <p><class="flat">' . $UsersOdd['FullName'] . '</p>
						  </a>
                          </li>
						  ';
						}
						?>
                        </ul>
                      </div>
					  <div class="col-md-6 col-sm-6 col-xs-12">
                        <ul class="to_do">
						<?
						while($UsersEven = $R_Get_UserEven->fetch_array(MYSQLI_ASSOC)){
						  echo'
                          <li>
						  <a data-toggle="modal" data-target=".bs-example-modal-lg">
                            <p><class="flat">' . $UsersEven['FullName'] . '</p>
						  </a>
                          </li>
						  ';
						}
						?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End users list -->
            </div>
			<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title" id="myModalLabel2">Manage Profile</h4>
								</div>
								<div class="modal-body">
								<div class="col-md-4 col-sm-6 col-xs-12">
									  <img class="img-responsive avatar-view" src="images/Avatars/Default.jpg" alt="Avatar">
								</div>
								<div class="col-md-8 col-sm-6 col-xs-12">
								  <div class="row">
									<div class="col-md-3 col-sm-6 col-xs-12">
									  <h5>First Name</h5>
									  <input id="FirstName" class="form-control col-md-6 col-xs-12" placeholder="James">
									</div>
									<div class="col-md-3 col-sm-6 col-xs-12">
									  <h5>Last Name</h5>
									  <input id="LastName" class="form-control col-md-6 col-xs-12" placeholder="Smith">
									</div>
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <h5>Email Address</h5>
									  <input id="Email" class="form-control col-md-6 col-xs-12" placeholder="jsmith@energyresourcesusa.net">
									</div>
								  </div>
								  <div class="row">
									<div class="col-md-6 col-sm-6 col-xs-12">
									  <h5>Usergroup</h5>
									  <select id="Usergroup" class="form-control" tabindex="-1">
									  </select>
									</div>
								  </div>
								</div>
								</div>
								<!-- Stuffing a lot of breaks for formatting (Temporary) -->
								<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-success" data-dismiss="modal" onclick="AddTask()">Update Info</button>
								</div>
							</div>
						</div>
					</div>
					<!--End Modal-->
          </div>
        </div>
        <!-- /page content -->
    </div>

    <!-- jQuery -->
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- jQuery Sparklines -->
    <script src="../vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- morris.js -->
    <script src="../vendors/raphael/raphael.min.js"></script>
    <script src="../vendors/morris.js/morris.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- Skycons -->
    <script src="../vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="../vendors/Flot/jquery.flot.js"></script>
    <script src="../vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../vendors/Flot/jquery.flot.time.js"></script>
    <script src="../vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="js/flot/jquery.flot.orderBars.js"></script>
    <script src="js/flot/date.js"></script>
    <script src="js/flot/jquery.flot.spline.js"></script>
    <script src="js/flot/curvedLines.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- jQuery Sparklines -->
    <script>
      $(document).ready(function() {
        $(".sparkline_one").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
          type: 'bar',
          height: '40',
          barWidth: 9,
          colorMap: {
            '7': '#a1a1a1'
          },
          barSpacing: 2,
          barColor: '#26B99A'
        });

        $(".sparkline_two").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4, 5, 6, 7, 5, 4, 3, 5, 6], {
          type: 'line',
          width: '200',
          height: '40',
          lineColor: '#26B99A',
          fillColor: 'rgba(223, 223, 223, 0.57)',
          lineWidth: 2,
          spotColor: '#26B99A',
          minSpotColor: '#26B99A'
        });
      });
    </script>
    <!-- /jQuery Sparklines -->

    <!-- Doughnut Chart -->
    <script>
      $(document).ready(function() {
        var options = {
          legend: false,
          responsive: false
        };

        new Chart(document.getElementById("canvas1"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "Symbian",
              "Blackberry",
              "Other",
              "Android",
              "IOS"
            ],
            datasets: [{
              data: [15, 20, 30, 10, 30],
              backgroundColor: [
                "#BDC3C7",
                "#9B59B6",
                "#E74C3C",
                "#26B99A",
                "#3498DB"
              ],
              hoverBackgroundColor: [
                "#CFD4D8",
                "#B370CF",
                "#E95E4F",
                "#36CAAB",
                "#49A9EA"
              ]
            }]
          },
          options: options
        });
      });
    </script>
    <!-- /Doughnut Chart -->

    <!-- bootstrap-daterangepicker -->
    <script type="text/javascript">
      $(document).ready(function() {

        var cb = function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        };

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
    <!-- /bootstrap-daterangepicker -->

    <!-- morris.js -->
    <script>
      $(document).ready(function() {
        Morris.Bar({
          element: 'graph_bar',
          data: [
            { "period": "Jan", "Hours worked": 80 }, 
            { "period": "Feb", "Hours worked": 125 }, 
            { "period": "Mar", "Hours worked": 176 }, 
            { "period": "Apr", "Hours worked": 224 }, 
            { "period": "May", "Hours worked": 265 }, 
            { "period": "Jun", "Hours worked": 314 }, 
            { "period": "Jul", "Hours worked": 347 }, 
            { "period": "Aug", "Hours worked": 287 }, 
            { "period": "Sep", "Hours worked": 240 }, 
            { "period": "Oct", "Hours worked": 211 }
          ],
          xkey: 'period',
          hideHover: 'auto',
          barColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
          ykeys: ['Hours worked', 'sorned'],
          labels: ['Hours worked', 'SORN'],
          xLabelAngle: 60,
          resize: true
        });

        $MENU_TOGGLE.on('click', function() {
          $(window).resize();
        });
      });
    </script>
    <!-- /morris.js -->

    <!-- Skycons -->
    <script>
      var icons = new Skycons({
          "color": "#73879C"
        }),
        list = [
          "clear-day", "clear-night", "partly-cloudy-day",
          "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
          "fog"
        ],
        i;

      for (i = list.length; i--;)
        icons.set(list[i], list[i]);

      icons.play();
    </script>
    <!-- /Skycons -->

    <!-- gauge.js -->
    <script>
      var opts = {
        lines: 12,
        angle: 0,
        lineWidth: 0.4,
        pointer: {
          length: 0.75,
          strokeWidth: 0.042,
          color: '#1D212A'
        },
        limitMax: 'false',
        colorStart: '#1ABC9C',
        colorStop: '#1ABC9C',
        strokeColor: '#F0F3F3',
        generateGradient: true
      };
      var target = document.getElementById('foo'),
          gauge = new Gauge(target).setOptions(opts);

      gauge.maxValue = 100;
      gauge.animationSpeed = 32;
      gauge.set(80);
      gauge.setTextField(document.getElementById("gauge-text"));

      var target = document.getElementById('foo2'),
          gauge = new Gauge(target).setOptions(opts);

      gauge.maxValue = 5000;
      gauge.animationSpeed = 32;
      gauge.set(4200);
      gauge.setTextField(document.getElementById("gauge-text2"));
    </script>
    <!-- /gauge.js -->
  </body>
</html>