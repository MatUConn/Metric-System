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
	
	<title>Project Data</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	<!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-ColReorder-1.3.2/css/colReorder.bootstrap.min.css" rel="stylesheet">
	
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

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Filters</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
					<?php
					$q = "SELECT p.project_name, ep.short_desc, p.CreateDate,
						CONCAT(SUBSTRING(p.CreateDate,1,10)) AS formatted_date, p.project_status, p.city, p.state, CONCAT(c.first_name, ' ', c.last_name) AS Salesperson, p.pm_id, 
						p.Project_Cost, p.KWH_Savings, p.Incentive, ft.Fuel_Type_Name as HeatingFuelType, fat.Facility_Type_Name as FacilityType, ElectricBillAcct, DateSigned, UtilityApprovalDate, 
						PMWalkDate, MaterialsOrderedDate, InstallDate, PMChanges, SignedFinalsRecieved, PaidDate
						FROM project p
						JOIN energy_program ep ON (p.program = ep.program_id)
						LEFT JOIN contact c ON (p.salesperson_id = c.contact_id)
						LEFT JOIN Fuel_Type ft ON (p.HeatingFuelType = ft.Fuel_ID)
						LEFT JOIN Facility_Type fat ON (p.FacilityType = fat.Facility_ID)
						WHERE Deleted = 0
						ORDER BY project_id";
					$r = $mysqli->query($q);
					?>
					<select class="select2_multiple form-control" multiple="multiple">
						<option selected value="0">Project Name</option>
						<option selected value="1">Create Date</option>
						<option selected value="2">Project Status</option>
						<option selected value="3">Program</option>
						<option value="4">City</option>
						<option value="5">State</option>
						<option value="6">Salesperson</option>
						<option value="7">Project Manager</option>
						<option value="8">Project Cost</option>
						<option value="9">kWh Savings</option>
						<option value="10">Incentive</option>
						<option value="11">Heating Fuel Type</option>
						<option value="12">Facility Type</option>
						<option value="13">Electric Bill Account</option>
						<option value="14">Date Signed</option>
						<option value="15">Utility Approval Date</option>
						<option value="16">PM Walk Date</option>
						<option value="17">Materials Ordered Date</option>
						<option value="18">Install Date</option>
						<option value="19">PM Changes</option>
						<option value="20">Signed Finals Recieved</option>
						<option value="21">Paid Date</option>
					</select>
					<div class="x_title"></div>
                    <table id="DataTable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>Project Name</th>
                          <th>Create Date</th>
                          <th class="select-filter">Project Status</th>
						  <th class="select-filter">Program</th>
						  <th class="select-filter">City</th>
                          <th class="select-filter">State</th>
                          <th class="select-filter">Salesperson</th>
                          <th class="select-filter">Project Manager</th>
						  <th>Project Cost</th>
						  <th>KWH Savings</th>
						  <th>Incentive</th>
						  <th class="select-filter">Heating Fuel Type</th>
						  <th class="select-filter">Facility Type</th>
						  <th>Electric Bill Account</th>
						  <th>Date Signed</th>
						  <th>Utility Approval Date</th>
						  <th>PM Walk Date</th>
						  <th>Materials Ordered Date</th>
						  <th>Install Date</th>
						  <th class="select-filter">PM Changes</th>
						  <th>Signed Finals Recieved</th>
						  <th>Paid Date</th>
                        </tr>
                      </thead>
					  <tfoot>
						<tr>
						  <th>Project Name</th>
                          <th>Create Date</th>
                          <th>Project Status</th>
						  <th>Program</th>
						  <th>City</th>
                          <th>State</th>
                          <th>Salesperson</th>
                          <th>Project Manager</th>
						  <th>Project Cost</th>
						  <th>KWH Savings</th>
						  <th>Incentive</th>
						  <th>Heating Fuel Type</th>
						  <th>Facility Type</th>
						  <th>Electric Bill Account</th>
						  <th>Date Signed</th>
						  <th>Utility Approval Date</th>
						  <th>PM Walk Date</th>
						  <th>Materials Ordered Date</th>
						  <th>Install Date</th>
						  <th>PM Changes</th>
						  <th>Signed Finals Recieved</th>
						  <th>Paid Date</th>
						</tr>
					  </tfoot>
                      <tbody>
					  <?php
					  while($row = $r->fetch_array(MYSQLI_ASSOC)){
						//PREPARE THE TERNARY OPERATORS!  This could probably be improved by looping through the array and replacing all NULLS with "Not Specified"
						$row['project_status'] = (empty($row['project_status'])) ? 'Not Specified' : $row['project_status'];
						$row['city'] = (empty($row['city'])) ? 'Not Specified' : $row['city'];
						$row['Salesperson'] = (empty($row['Salesperson'])) ? 'Not Specified' : $row['Salesperson'];
						$row['Project_Cost'] = (empty($row['Project_Cost'])) ? 'Not Specified' : $row['Project_Cost'];
						$row['KWH_Savings'] = (empty($row['KWH_Savings'])) ? 'Not Specified' : $row['KWH_Savings'];
						$row['Incentive'] = (empty($row['Incentive'])) ? 'Not Specified' : $row['Incentive'];
						$row['HeatingFuelType'] = (empty($row['HeatingFuelType'])) ? 'Not Specified' : $row['HeatingFuelType'];
						$row['FacilityType'] = (empty($row['FacilityType'])) ? 'Not Specified' : $row['FacilityType'];
						$row['ElectricBillAcct'] = (empty($row['ElectricBillAcct'])) ? 'Not Specified' : $row['ElectricBillAcct'];
						$row['DateSigned'] = (empty($row['DateSigned'])) ? 'Not Specified' : $row['DateSigned'];
						$row['UtilityApprovalDate'] = (empty($row['UtilityApprovalDate'])) ? 'Not Specified' : $row['UtilityApprovalDate'];
						$row['PMWalkDate'] = (empty($row['PMWalkDate'])) ? 'Not Specified' : $row['PMWalkDate'];
						$row['MaterialsOrderedDate'] = (empty($row['MaterialsOrderedDate'])) ? 'Not Specified' : $row['MaterialsOrderedDate'];
						$row['InstallDate'] = (empty($row['InstallDate'])) ? 'Not Specified' : $row['InstallDate'];
						$row['PMChanges'] = (empty($row['PMChanges'])) ? 'Not Specified' : $row['PMChanges'];
						$row['SignedFinalsRecieved'] = (empty($row['SignedFinalsRecieved'])) ? 'Not Specified' : $row['SignedFinalsRecieved'];
						$row['PaidDate'] = (empty($row['PaidDate'])) ? 'Not Specified' : $row['PaidDate'];
						if ($row['pm_id'] == NULL) {
							$ProjectManager[0] = "Not Specified";
						}
						else {
							$ProjectManagerID = $row['pm_id'];
							$Q_ProjectManager = "SELECT CONCAT(first_name, ' ', last_name) AS 'full_name'
								FROM contact
								WHERE contact_id = $ProjectManagerID";
							$R_ProjectManager = $mysqli->query($Q_ProjectManager);
							$ProjectManager = $R_ProjectManager->fetch_array(MYSQLI_NUM);
						}
                        echo'
                        <tr>
                          <td>' . $row['project_name'] . '</td>
                          <td>' . $row['formatted_date'] . '</td>
                          <td>' . $row['project_status'] . '</td>
						  <td>' . $row['short_desc'] . '</td>
						  <td>' . $row['city'] . '</td>
                          <td>' . $row['state'] . '</td>
                          <td>' . $row['Salesperson'] . '</td>
                          <td>' . $ProjectManager[0] . '</td>
                          <td>' . $row['Project_Cost'] . '</td>
                          <td>' . $row['KWH_Savings'] . '</td>
                          <td>' . $row['Incentive'] . '</td>
                          <td>' . $row['HeatingFuelType'] . '</td>
                          <td>' . $row['FacilityType'] . '</td>
						  <td>' . $row['ElectricBillAcct'] . '</td>
						  <td>' . $row['DateSigned'] . '</td>
						  <td>' . $row['UtilityApprovalDate'] . '</td>
						  <td>' . $row['PMWalkDate'] . '</td>
						  <td>' . $row['MaterialsOrderedDate'] . '</td>
						  <td>' . $row['InstallDate'] . '</td>
						  <td>' . $row['PMChanges'] . '</td>
						  <td>' . $row['SignedFinalsRecieved'] . '</td>
						  <td>' . $row['PaidDate'] . '</td>
                        </tr>';
					  }
					  ?>
                      </tbody>
                    </table>
                </div>
              </div>
			</div>
          </div>
        </div>
        <!-- /page content -->

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
	<!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
	<!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
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
	<script src="../vendors/datatables.net-ColReorder-1.3.2/js/dataTables.colReorder.min.js"></script>
    <script src="../vendors/jszip/dist/jszip.min.js"></script>
    <script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

	<!-- Select2 -->
    <script>
      $(document).ready(function() {
        $(".select2_multiple").select2({
          placeholder: "",
          allowClear: true
        });
      });
    </script>
    <!-- /Select2 -->
    <!-- Datatables -->
    <script> 
$(document).ready(function() {
    var table = $('#DataTable').DataTable( {
		dom: "Blfrtip",
        buttons: [
        {
            extend: "excel",
			className: "btn-sm",
			exportOptions: {
				columns: ":visible"
            }
        },
        {
            extend: "print",
			className: "btn-sm",
			exportOptions: {
				columns: ":visible"
            }
        },
        ],
		"iDisplayLength": 25,
		"sScrollX": '70%',
        "bScrollCollapse": true,
		"bSortClasses": false,
		"bDeferRender": true,
		"responsive": false,
		colReorder: {
			realtime: false
		},
		initComplete: function () {
            this.api().columns( '.select-filter' ).every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
	} );
	ColumnCount = document.getElementById('DataTable').rows[0].cells.length;
	$(".select2_multiple").change(function() {
			array = $(".select2_multiple").select2("val");
			for (i = 0; i < ColumnCount; i++) { //for each column in the table, hide it
				table.column(i).visible(false);
			}
			for (var k in array) { //for each variable in the array from the select box, draw that value
				table.column(array[k]).visible(true);
			}

	} ).triggerHandler('change');
} );
    </script>
    <!-- /Datatables -->
  </body>
</html>