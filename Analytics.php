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
//Dealing with States's
if (isset($_GET['State'])) { //source is from projects.php
	$State = $_GET['State'];
	$IsState = true;
}
else { //an invalid State was provided, kill script
	$IsState = false;
}
//Dealing with Salespeople
if (isset($_GET['Salesperson'])) { //source is from projects.php
	$Salesperson = $_GET['Salesperson'];
	$IsSalesperson = true;
}
else { //an invalid Salesperson ID was provided, kill script
	$IsSalesperson = false;
}
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Analytics</title>

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
                <h3>Analytics</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="">
                  <div class="x_content">
                    <div class="row">
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="count">19/33</div>
						  <?php
                          echo'<h3>Projects in ' . $State . '</h3>';
                          ?>
						  <p>For the past 30 days</p>
                        </div>
                      </div>
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="count">978/1252</div>
						  <?php
                          echo'<h3>Projects in ' . $Salesperson . '</h3>';
						  ?>
                          <p>Overall</p>
                        </div>
                      </div>
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-sort-amount-desc"></i>
                          </div>
                          <div class="count">179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
                      <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                          <div class="icon"><i class="fa fa-check-square-o"></i>
                          </div>
                          <div class="count">179</div>

                          <h3>New Sign ups</h3>
                          <p>Lorem ipsum psdea itgum rixt.</p>
                        </div>
                      </div>
                    </div>

                    <div class="row top_tiles" style="margin: 10px 0;">
                      <div class="col-md-3 tile">
                        <span>Total Sessions</span>
                        <h2>231,809</h2>
                        <span class="sparkline_one" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                      </div>
                      <div class="col-md-3 tile">
                        <span>Total Revenue</span>
                        <h2>$ 1,231,809</h2>
                        <span class="sparkline_one" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                      </div>
                      <div class="col-md-3 tile">
                        <span>Total Sessions</span>
                        <h2>231,809</h2>
                        <span class="sparkline_two" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                      </div>
                      <div class="col-md-3 tile">
                        <span>Total Sessions</span>
                        <h2>231,809</h2>
                        <span class="sparkline_one" style="height: 160px;">
                                      <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
                                  </span>
                      </div>
                    </div>




                    <br />
                    <div class="row">
                      <div class="col-md-3 col-xs-12 widget widget_tally_box">
                        <div class="x_panel fixed_height_390">
                          <div class="x_title">
                            <h2>Tally Design</h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="#">Settings 1</a>
                                  </li>
                                  <li><a href="#">Settings 2</a>
                                  </li>
                                </ul>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">

                            <div style="text-align: center; overflow: hidden; margin: 10px 5px 0;">
                              <canvas id="canvas_line1" height="200"></canvas>
                            </div>

                            <div style="text-align: center; margin-bottom: 15px;">
                              <div class="btn-group" role="group" aria-label="First group">
                                <button type="button" class="btn btn-default btn-sm">Day</button>
                                <button type="button" class="btn btn-default btn-sm">Month</button>
                                <button type="button" class="btn btn-default btn-sm">Year</button>
                              </div>
                            </div>

                            <div>
                              <ul class="list-inline widget_tally">
                                <li>
                                  <p>
                                    <span class="month">12 December 2014 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="month">29 December 2014 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="month">16 January 2015 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="col-md-3 col-xs-12 widget widget_tally_box">
                        <div class="x_panel fixed_height_390">
                          <div class="x_title">
                            <h2>Sales Close</h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="#">Settings 1</a>
                                  </li>
                                  <li><a href="#">Settings 2</a>
                                  </li>
                                </ul>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">

                            <div style="text-align: center; margin-bottom: 17px">
                              <ul class="verticle_bars list-inline">
                                <li>
                                  <div class="progress vertical progress_wide bottom">
                                    <div class="progress-bar progress-bar-dark" role="progressbar" data-transitiongoal="65"></div>
                                  </div>
                                </li>
                                <li>
                                  <div class="progress vertical progress_wide bottom">
                                    <div class="progress-bar progress-bar-gray" role="progressbar" data-transitiongoal="85"></div>
                                  </div>
                                </li>
                                <li>
                                  <div class="progress vertical progress_wide bottom">
                                    <div class="progress-bar progress-bar-info" role="progressbar" data-transitiongoal="45"></div>
                                  </div>
                                </li>
                                <li>
                                  <div class="progress vertical progress_wide bottom">
                                    <div class="progress-bar progress-bar-success" role="progressbar" data-transitiongoal="75"></div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                            <div class="divider"></div>

                            <ul class="legend list-unstyled">
                              <li>
                                <p>
                                  <span class="icon"><i class="fa fa-square dark"></i></span> <span class="name">Item One Name</span>
                                </p>
                              </li>
                              <li>
                                <p>
                                  <span class="icon"><i class="fa fa-square grey"></i></span> <span class="name">Item Two Name</span>
                                </p>
                              </li>
                              <li>
                                <p>
                                  <span class="icon"><i class="fa fa-square blue"></i></span> <span class="name">Item Three Name</span>
                                </p>
                              </li>
                              <li>
                                <p>
                                  <span class="icon"><i class="fa fa-square green"></i></span> <span class="name">Item Four Name</span>
                                </p>
                              </li>
                            </ul>

                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-xs-12 widget widget_tally_box">
                        <div class="x_panel fixed_height_390">
                          <div class="x_content">

                            <div class="flex">
                              <ul class="list-inline widget_profile_box">
                                <li>
                                  <a>
                                    <i class="fa fa-facebook"></i>
                                  </a>
                                </li>
                                <li>
                                  <img src="images/user.png" alt="..." class="img-circle profile_img">
                                </li>
                                <li>
                                  <a>
                                    <i class="fa fa-twitter"></i>
                                  </a>
                                </li>
                              </ul>
                            </div>

                            <h3 class="name">Musimbi</h3>

                            <div class="flex">
                              <ul class="list-inline count2">
                                <li>
                                  <h3>123</h3>
                                  <span>Articles</span>
                                </li>
                                <li>
                                  <h3>1234</h3>
                                  <span>Followers</span>
                                </li>
                                <li>
                                  <h3>123</h3>
                                  <span>Following</span>
                                </li>
                              </ul>
                            </div>
                            <p>
                              If you've decided to go in development mode and tweak all of this a bit, there are few things you should do.
                            </p>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-xs-12 widget widget_tally_box">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>Tally Design1</h2>
                            <ul class="nav navbar-right panel_toolbox">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                              <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="#">Settings 1</a>
                                  </li>
                                  <li><a href="#">Settings 2</a>
                                  </li>
                                </ul>
                              </li>
                              <li><a class="close-link"><i class="fa fa-close"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">

                            <div style="text-align: center; margin-bottom: 17px">
                              <span class="chart" data-percent="86">
                                                  <span class="percent"></span>
                              </span>
                            </div>

                            <div class="pie_bg" style="text-align: center; display: none; margin-bottom: 17px">
                              <canvas id="canvas_doughnut" height="130"></canvas>
                            </div>

                            <div style="text-align: center;">
                              <div class="btn-group" role="group" aria-label="First group">
                                <button type="button" class="btn btn-default btn-sm">1 D</button>
                                <button type="button" class="btn btn-default btn-sm">1 W</button>
                                <button type="button" class="btn btn-default btn-sm">1 M</button>
                                <button type="button" class="btn btn-default btn-sm">1 Y</button>
                              </div>
                            </div>
                            <div style="text-align: center; overflow: hidden; margin: 10px 5px 3px;">
                              <canvas id="canvas_line" height="190"></canvas>
                            </div>
                            <div>
                              <ul class="list-inline widget_tally">
                                <li>
                                  <p>
                                    <span class="month">12 December 2014 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="month">29 December 2014 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                                <li>
                                  <p>
                                    <span class="month">16 January 2015 </span>
                                    <span class="count">+12%</span>
                                  </p>
                                </li>
                              </ul>
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
    <!-- jQuery Sparklines -->
    <script src="../vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- easy-pie-chart -->
    <script src="../vendors/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

    <!-- chart js -->
    <!-- <script type="text/javascript" src="js/moment/moment.min.js"></script> -->
    <!-- <script src="js/chartjs/chart.min.js"></script> -->
    <!-- bootstrap progress js -->
    <!-- <script src="js/progressbar/bootstrap-progressbar.min.js"></script> -->
    <!-- icheck -->
    <!-- <script src="js/icheck/icheck.min.js"></script> -->

    <!-- <script src="../build/js/custom.min.js"></script> -->

    <!-- pace -->
    <!-- <script src="js/pace/pace.min.js"></script> -->

    <!-- sparkline -->
    <!-- <script src="js/sparkline/jquery.sparkline.min.js"></script> -->
    <!-- easypie -->
    <!-- <script src="js/easypie/jquery.easypiechart.min.js"></script> -->
    <script>
      $(function() {
        $('.chart').easyPieChart({
          easing: 'easeOutElastic',
          delay: 3000,
          barColor: '#26B99A',
          trackColor: '#fff',
          scaleColor: false,
          lineWidth: 20,
          trackWidth: 16,
          lineCap: 'butt',
          onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
          }
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        Chart.defaults.global.legend = {
          enabled: false
        };

        new Chart(document.getElementById("canvas_line"), {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
              label: "My First dataset",
              backgroundColor: "rgba(38, 185, 154, 0.31)",
              borderColor: "rgba(38, 185, 154, 0.7)",
              pointBorderColor: "rgba(38, 185, 154, 0.7)",
              pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(220,220,220,1)",
              pointBorderWidth: 1,
              data: [31, 74, 6, 39, 20, 85, 7]
            }, {
              label: "My Second dataset",
              backgroundColor: "rgba(3, 88, 106, 0.3)",
              borderColor: "rgba(3, 88, 106, 0.70)",
              pointBorderColor: "rgba(3, 88, 106, 0.70)",
              pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(151,187,205,1)",
              pointBorderWidth: 1,
              data: [82, 23, 66, 9, 99, 4, 2]
            }]
          },
        });

        new Chart(document.getElementById("canvas_line1"), {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
              label: "My First dataset",
              backgroundColor: "rgba(38, 185, 154, 0.31)",
              borderColor: "rgba(38, 185, 154, 0.7)",
              pointBorderColor: "rgba(38, 185, 154, 0.7)",
              pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(220,220,220,1)",
              pointBorderWidth: 1,
              data: [31, 74, 6, 39, 20, 85, 7]
            }, {
              label: "My Second dataset",
              backgroundColor: "rgba(3, 88, 106, 0.3)",
              borderColor: "rgba(3, 88, 106, 0.70)",
              pointBorderColor: "rgba(3, 88, 106, 0.70)",
              pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(151,187,205,1)",
              pointBorderWidth: 1,
              data: [82, 23, 66, 9, 99, 4, 2]
            }]
          },
        });

        new Chart(document.getElementById("canvas_line2"), {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
              label: "My First dataset",
              backgroundColor: "rgba(38, 185, 154, 0.31)",
              borderColor: "rgba(38, 185, 154, 0.7)",
              pointBorderColor: "rgba(38, 185, 154, 0.7)",
              pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(220,220,220,1)",
              pointBorderWidth: 1,
              data: [31, 74, 6, 39, 20, 85, 7]
            }, {
              label: "My Second dataset",
              backgroundColor: "rgba(3, 88, 106, 0.3)",
              borderColor: "rgba(3, 88, 106, 0.70)",
              pointBorderColor: "rgba(3, 88, 106, 0.70)",
              pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(151,187,205,1)",
              pointBorderWidth: 1,
              data: [82, 23, 66, 9, 99, 4, 2]
            }]
          },
        });

        new Chart(document.getElementById("canvas_line3"), {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
              label: "My First dataset",
              backgroundColor: "rgba(38, 185, 154, 0.31)",
              borderColor: "rgba(38, 185, 154, 0.7)",
              pointBorderColor: "rgba(38, 185, 154, 0.7)",
              pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(220,220,220,1)",
              pointBorderWidth: 1,
              data: [31, 74, 6, 39, 20, 85, 7]
            }, {
              label: "My Second dataset",
              backgroundColor: "rgba(3, 88, 106, 0.3)",
              borderColor: "rgba(3, 88, 106, 0.70)",
              pointBorderColor: "rgba(3, 88, 106, 0.70)",
              pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(151,187,205,1)",
              pointBorderWidth: 1,
              data: [82, 23, 66, 9, 99, 4, 2]
            }]
          },
        });

        new Chart(document.getElementById("canvas_line4"), {
          type: 'line',
          data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
              label: "My First dataset",
              backgroundColor: "rgba(38, 185, 154, 0.31)",
              borderColor: "rgba(38, 185, 154, 0.7)",
              pointBorderColor: "rgba(38, 185, 154, 0.7)",
              pointBackgroundColor: "rgba(38, 185, 154, 0.7)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(220,220,220,1)",
              pointBorderWidth: 1,
              data: [31, 74, 6, 39, 20, 85, 7]
            }, {
              label: "My Second dataset",
              backgroundColor: "rgba(3, 88, 106, 0.3)",
              borderColor: "rgba(3, 88, 106, 0.70)",
              pointBorderColor: "rgba(3, 88, 106, 0.70)",
              pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
              pointHoverBackgroundColor: "#fff",
              pointHoverBorderColor: "rgba(151,187,205,1)",
              pointBorderWidth: 1,
              data: [82, 23, 66, 9, 99, 4, 2]
            }]
          },
        });
      });
    </script>
    
    <script>
      $('document').ready(function() {
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
      })
    </script>
  </body>
</html>