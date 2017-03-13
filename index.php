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

    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
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
          <!-- top tiles -->
          <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                      <!--<a href="#" class="dropdown-toggle" onclick="ChangeYear('GrossRevenue')"><i class="fa fa-clock-o"></i></a>-->
				<?php
                  echo'<div id="GrossRevenue" class="count"> $' . $GrossRevenue . '</div>';
				?>
                  <h3>Gross Revenue</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
				<?php
                  echo'<div class="count"> $' . $EarnedRevenue . '</div>';
				?>
                  <h3>Earned Revenue</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
				<?php
                  echo'<div class="count"><i class="fa fa-pencil"></i> ' . $TotalProjectsSigned . '</div>';
				?>
                  <h3>Total Projects Signed</h3>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
				<?php
                  echo'<div class="count"><i class="fa fa-money"></i> $' . $LargestSignedDeal . '</div>';
				?>
                  <h3>Largest Signed Deal</h3>
                </div>
              </div>
            </div>
		  <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-check-circle-o"></i> Projects Created</span>
			  <?
              echo'<div class="count">' . $ProjectsCreated . '</div>
              <span class="count_bottom"><b>' . $ProjectsCreatedChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-edit"></i> Projects Signed</span>
			  <?
              echo'<div class="count">' . $ProjectsSigned . '</div>
              <span class="count_bottom"><b>' . $ProjectsSignedChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-cubes"></i> Total in sales</span>
              <?
              echo'<div class="count green">$' . $TotalInSales . '</div>
              <span class="count_bottom"><b>$' . $TotalInSalesChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-cube"></i> Average project cost</span>
              <?
              echo'<div class="count red">$' . $AverageProjectCost . '</div>
              <span class="count_bottom"><b>$' . $AverageProjectCostChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-car"></i> In-State Projects</span>
              <?
              echo'<div class="count">' . $InStateProjects . '</div>
              <span class="count_bottom"><b>' . $InStateProjectsChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-rocket"></i> Out-of-State Projects</span>
              <?
              echo'<div class="count">' . $OutOfStateProjects . '</div>
              <span class="count_bottom"><b>' . $OutOfStateProjectsChange . '</b> in ' . $LastMonthName . '</span>';
			  ?>
            </div>
          </div>
          <!-- /top tiles -->

			<div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="dashboard_graph">

                <div class="row x_title">
                  <div class="col-md-6">
                    <h3>Programs Performance (Past 6 Months)</h3>
                  </div>
                  <div class="col-md-6"><!--
                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                      <span></span> <b class="caret"></b>
                    </div>-->
                  </div>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div id="placeholder33" style="height: 260px; display: none" class="demo-placeholder"></div>
                  <div style="width: 100%;">
                    <div id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                  </div>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                  <div class="x_title">
                    <h2><a href="View_Programs.php">Programs</a></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-6">
                    <div>
					  <p><a href="Program.php?Program_ID=1"><span style="color:blue;"><b>Eversource SMB</b></span></a></p> <!--Data2-->
					  <p><a href="Program.php?Program_ID=2"><span style="color:black;"><b>Eversource EO</b></span></a></p> <!--Data5-->
					  <p><a href="Program.php?Program_ID=4"><span style="color:green;"><b>Eversource Mass Save</b></span></a></p> <!--Data3-->
					  <p><a href="Program.php?Program_ID=3"><span style="color:red;"><b>UI SMB</b></span></a></p> <!--Data1-->
					  <p><a href="Program.php?Program_ID=6"><span style="color:purple;"><b>Shannon</b></span></a></p> <!--Data4-->
					  <p><span style="color:#FFCC11;"><b>Other</b></span></p> <!--Data7-->
                    </div>
                  </div>
                </div>

                <div class="clearfix"></div>
              </div>
            </div>

          </div>
          <br />

          <div class="row">


            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2><a href="View_Salespeople.php">Top Salespeople</a></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <h4>(Last 30 Days)</h4>
				  <?php
				  $Highest = 0;
				  while ($Top_Salespeople = $R_Top_Salespeople->fetch_array(MYSQLI_NUM)) {
					if($Top_Salespeople[0] >= $Highest){
						$Highest = round($Top_Salespeople[0]);
					}
					$Percent = ($Top_Salespeople[0] / $Highest) * 100;
				  ?>
                  <div class="widget_summary">
                    <div class="w_left w_25">
					  <?php
                      echo'<span><a href="Salesperson.php?Salesperson_ID=' . $Top_Salespeople[1] . '">' . $Top_Salespeople[2] . '</a></span>';
					  ?>
                    </div>
                    <div class="w_center w_55">
                      <div class="progress">
					  <?php
                        echo'<div class="progress-bar bg-green" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: ' . $Percent . '%;">';
					  ?>
                          <span class="sr-only">80% Complete</span>
                        </div>
                      </div>
                    </div>
                    <div class="w_right w_20">
					  <?php
                      echo'<span>' . number_format(round($Top_Salespeople[0])) . '</span>';
					  ?>
                    </div>
                    <div class="clearfix"></div>
                  </div>
				  <?php
				  }
				  ?>

                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320 overflow_hidden">
                <div class="x_title">
                  <h2>Sales Breakdown</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table class="" style="width:100%">
                    <tr>
                      <th style="width:37%;">
                        <p>Divisions</p>
                      </th>
                      <th>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                          <p class="">Percentage of Sales</p>
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
                              <p><i class="fa fa-square blue"></i>JK Energy</p>
                            </td>
                            <td>52%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square purple"></i>Shannon</p>
                            </td>
                            <td>36%</td>
                          </tr>
                          <tr>
                            <td>
                              <p><i class="fa fa-square red"></i>Wegmans</p>
                            </td>
                            <td>12%</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>


            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2><a href="Whiteboard.php">Monthly Sales Goal</a></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">
                    <ul class="quick-list-small">
					<?
					$q = "SELECT project_name, project_id
						FROM project
						WHERE YEAR(DateSigned) = YEAR(NOW())
						AND MONTH(DateSigned) = MONTH(NOW())
						AND Deleted = 0
						ORDER BY project_ID DESC
						LIMIT 7";
					$r = $mysqli->query($q);
					while($row = $r->fetch_array(MYSQLI_ASSOC)){
                      echo'<li><a href="Edit_Project.php?proj_id=' . $row['project_id'] . '">' . $row['project_name'] . '</a></li>';
					}
					?>
                    </ul>

                    <div class="sidebar-widget">
                      <h4>Sales Goal</h4>
                      <canvas width="150" height="80" id="foo" class="" style="width: 160px; height: 100px;"></canvas>
                      <div class="goal-wrapper">
                        <span class="gauge-value pull-left">$</span>
						<?php
							echo'<span id="gauge-text" class="gauge-value pull-left">' . $TotalInSales . '</span>';
						?>
                        <span id="goal-text" class="goal-value pull-right">$1,150,000</span>
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
    <!-- Chart.js -->
    <script src="../vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="../vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../vendors/iCheck/icheck.min.js"></script>
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

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- Flot -->
    <script>
	TodayDate = new Date();
//	function GetYear (Months) {
//		Months = Months - 1;
//		TodayDate.setMonth(TodayDate.getMonth()-Months);
//		return TodayDate.TodayDate.getFullYear();
//	}
	TodayMonth = TodayDate.getMonth();
      $(document).ready(function() {
        var data1 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($Program3[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($Program3[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($Program3[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($Program3[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($Program3[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($Program3[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($Program3[0]); ?>]
        ];
		var data2 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($Program1[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($Program1[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($Program1[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($Program1[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($Program1[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($Program1[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($Program1[0]); ?>]
        ];
		var data3 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($Program4[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($Program4[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($Program4[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($Program4[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($Program4[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($Program4[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($Program4[0]); ?>]
        ];
		var data4 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($Program6[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($Program6[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($Program6[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($Program6[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($Program6[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($Program6[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($Program6[0]); ?>]
        ];
		var data5 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($Program2[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($Program2[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($Program2[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($Program2[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($Program2[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($Program2[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($Program2[0]); ?>]
        ];
		var data7 = [
          [gd(2016, TodayMonth - 5, 1), <?php echo json_encode($ProgramOther[6]); ?>],
          [gd(2016, TodayMonth - 4, 1), <?php echo json_encode($ProgramOther[5]); ?>],
          [gd(2016, TodayMonth - 3, 1), <?php echo json_encode($ProgramOther[4]); ?>],
          [gd(2016, TodayMonth - 2, 1), <?php echo json_encode($ProgramOther[3]); ?>],
          [gd(2016, TodayMonth - 1, 1), <?php echo json_encode($ProgramOther[2]); ?>],
          [gd(2016, TodayMonth, 1), <?php echo json_encode($ProgramOther[1]); ?>],
          [gd(2016, TodayMonth + 1, 1), <?php echo json_encode($ProgramOther[0]); ?>]
        ];

        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
          data1, data2, data3, data4, data5, data7
        ], {
          series: {
            lines: {
              show: true
            },
            points: {
              radius: 0,
              show: true
            },
            shadowSize: 2
          },
          grid: {
            verticalLines: true,
            hoverable: true,
            clickable: true,
            tickColor: "#d5d5d5",
            borderWidth: 3,
            color: '#fff'
          },
          colors: ["#CD0000", "#0022FF", "#008000", "#800080", "#000000", "#FFCC11"],
          xaxis: {
            tickColor: "rgba(51, 51, 51, 0.1)",
            mode: "time",
            tickSize: [1, "month"],
            //tickLength: 10,
            axisLabel: "Date",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 10
          },
          yaxis: {
            ticks: 8,
            tickColor: "rgba(51, 51, 51, 0.1)",
          },
          tooltip: false
        });

        function gd(year, month, day) {
          return new Date(year, month - 1, day).getTime();
        }
      });
    </script>
    <!-- /Flot -->

    <!-- Doughnut Chart -->
    <script>
      $(document).ready(function(){
        var options = {
          legend: false,
          responsive: false
        };

        new Chart(document.getElementById("canvas1"), {
          type: 'doughnut',
          tooltipFillColor: "rgba(51, 51, 51, 0.55)",
          data: {
            labels: [
              "JK Energy",
              "Shannon",
              "Wegmans"
            ],
            datasets: [{
              data: [52, 36, 12],
              backgroundColor: [
                "#3498DB",
                "#9B59B6",
                "#E74C3C",
                "#26B99A"
              ],
              hoverBackgroundColor: [
                "#49A9EA",
                "#B370CF",
                "#E95E4F",
                "#36CAAB"
              ]
            }]
          },
          options: options
        });
      });
    </script>
    <!-- /Doughnut Chart -->

    <!-- gauge.js -->
    <script>
	var Value = 0.01;
      var opts = {
          lines: 24,
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

      gauge.maxValue = 1150000;
      gauge.animationSpeed = 32;
      gauge.set(Value);
      
    </script>
    <!-- /gauge.js -->
	<!--DateChange-->
	<script>
	function ChangeYear(Div){
		$("#" + Div + "").html("Hetuiewfr");
	}
	</script>
  </body>
</html>