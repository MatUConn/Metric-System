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

    <title>View Salespeople</title>

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
		include (GEN_INCLUDES_PATH . 'DashboardFunctions.php');
		include (GEN_INCLUDES_PATH . 'Left_Menu.html');
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');
        ?>
		
        <!-- page content -->
		<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>View Salespeople</h3>
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
                  <div class="x_content">
                    <div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 text-center">
                        <ul class="pagination pagination-split">
                        </ul>
                      </div>

                      <div class="clearfix"></div>
					  
					  
						<?
						$q = "SELECT contact_id, CONCAT(first_name, ' ', last_name) AS 'full_name', CONCAT('(', SUBSTRING(phone_num, 1, 3), ') ', SUBSTRING(phone_num, 4, 3), '-', SUBSTRING(phone_num, 7, 4)) AS 'formatted_phone', Inactive
							FROM contact
							WHERE role = 'Salesperson'
							AND Inactive = 0
							ORDER BY contact_id";
						$r = $mysqli->query($q);
						while($row = $r->fetch_array(MYSQLI_ASSOC)){
							$SalespersonID = $row['contact_id'];
							$Q_Get_States = "SELECT state
								FROM project
								WHERE salesperson_id = $SalespersonID
								AND YEAR(DateSigned)= YEAR(Now())
								AND Deleted = 0
								GROUP BY state
								ORDER BY SUM(Project_Cost) DESC";
							$R_Get_States = $mysqli->query($Q_Get_States);
						echo'
                      <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <h4 class="brief"><i>Salesperson</i></h4>
                            <div class="left col-xs-7">
                              <h2>' . $row['full_name'] . '</h2>
                              <p><strong>States</strong>';
							  while($State = $R_Get_States->fetch_array(MYSQLI_ASSOC)){
								  echo' | ' . $State['state'] . '';
							  }
							  echo'</p>
                              <ul class="list-unstyled">
                                <li><i class="fa fa-phone"></i> Phone #: ' . $row['formatted_phone'] . '</li>
                              </ul>
                            </div>
                            <div class="right col-xs-5 text-center">
                              <img src="images/Avatars/Default.jpg" alt="" class="img-circle img-responsive">
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                            <div class="col-xs-12 col-sm-6 emphasis pull-right">
								<a class="btn btn-primary btn-xs" href="Salesperson.php?Salesperson_ID=' . $row['contact_id'] . '">
                                <i class="fa fa-user"> </i> View Profile</a>
                            </div>
                          </div>
                        </div>
                      </div>';
					  }
					  ?>
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
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

  </body>
</html>