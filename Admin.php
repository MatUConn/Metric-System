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

    <title>View Programs</title>

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
                <h3>View Programs</h3>
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
                    <h2>Programs</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
				  <?
                    $Q_Get_Salesperson = "SELECT CONCAT(c.first_name, ' ', c.last_name) AS Salesperson
                                            FROM project p
                                             LEFT JOIN contact c ON (p.salesperson_id = c.contact_id)
                                             WHERE p.project_ID = 1569";
                     $R_Get_Salesperson = $mysqli->query($Q_Get_Salesperson);
                     $SalespersonInfo = $R_Get_Salesperson->fetch_array(MYSQLI_ASSOC);
                     $Salesperson = $SalespersonInfo['Salesperson'];
					$q = "SELECT program_id, description, Inactive
						FROM energy_program
						ORDER BY Program_ID";
					$r = $mysqli->query($q);
					while($row = $r->fetch_array(MYSQLI_ASSOC)){
					echo'
					<article class="media event">
                      <a class="pull-left date" href="Program.php?Program_ID=' . $row['program_id'] . '">
                        <p class="day">' . $Salesperson . '</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="Program.php?Program_ID=' . $row['program_id'] . '">' . $row['description'] . '</a>';
						if ($row['Inactive']) {
							echo' (Inactive)';
						}
                        echo'<p><a href="Program.php?Program_ID=' . $row['program_id'] . '"><!-- If you add a description, put it here --></a></p>
                      </div>
                    </article>';
					}
					?>
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
    
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
        
  </body>
</html>
