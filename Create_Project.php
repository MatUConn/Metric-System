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

//Salesperson Queries
$q_get_salespersons = "SELECT contact_id, CONCAT(first_name, ' ', last_name) AS 'full_name',
		CONCAT('(', SUBSTRING(phone_num, 1, 3), ') ', SUBSTRING(phone_num, 4, 3), '-', SUBSTRING(phone_num, 7, 4)) AS 'formatted_phone'
		FROM contact
		WHERE role = 'Salesperson'
		AND Inactive = '0'
		ORDER BY first_name";
$rslt_salespersons = $mysqli->query($q_get_salespersons);
//Programs List Queries
$Q_Programs = "SELECT program_id, short_desc
		FROM energy_program
		WHERE Inactive = 0
		ORDER BY program_id";
$R_Programs = $mysqli->query($Q_Programs);
//Fuel Type List Queries
$Q_FuelType = "SELECT Fuel_ID, Fuel_Type_Name
		FROM Fuel_Type
		WHERE Active = 1
		ORDER BY Fuel_Type_Name";
$R_FuelType = $mysqli->query($Q_FuelType);
//Facility Type List Queries
$Q_FacilityType = "SELECT Facility_ID, Facility_Type_Name
		FROM Facility_Type
		WHERE Active = 1
		ORDER BY Facility_Type_Name";
$R_FacilityType = $mysqli->query($Q_FacilityType);
//Finance Type List Queries
$Q_FinanceType = "SELECT Finance_ID, DropDownText
		FROM DropDownOptions
		WHERE Active = 1
		AND DropDown = 'FacilityType'
		ORDER BY DropDownText";
$R_FinanceType = $mysqli->query($Q_FinanceType);
//Form Submission and data validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$ProjectName = $mysqli->real_escape_string(trim($_POST['ProjectName']));
	if (empty($_POST['BusinessName'])) {
		$BusinessName = '';
	}
	else {
		$BusinessName = $mysqli->real_escape_string(trim($_POST['BusinessName']));
	}
	$ProgramNum = $mysqli->real_escape_string(trim($_POST['ProgramNum']));
	$ProgramID = $mysqli->real_escape_string(trim($_POST['ProgramID']));
	$Address1 = $mysqli->real_escape_string(trim($_POST['Address1']));
	$Address2 = $mysqli->real_escape_string(trim($_POST['Address2']));
	$City = $mysqli->real_escape_string(trim($_POST['City']));
	$State = $mysqli->real_escape_string(trim($_POST['State']));
	$Zip = $mysqli->real_escape_string(trim($_POST['ZipCode']));
	$ProjectCost = $mysqli->real_escape_string(trim($_POST['Cost']));
	$KWHSavings = $mysqli->real_escape_string(trim($_POST['KWH']));
	$Incentive = $mysqli->real_escape_string(trim($_POST['Incentive']));
	if (isset($_POST['Financing'])){
		$Financing = 1;
	}
	else {
		$Financing = 0;
	}
	if (isset($_POST['Comprehensive'])){
		$Comprehensive = 1;
	}
	else {
		$Comprehensive = 0;
	}
	if (isset($_POST['TaxExempt'])){
		$TaxExempt = 1;
	}
	else {
		$TaxExempt = 0;
	}
	$HeatingFuelType = $mysqli->real_escape_string(trim($_POST['HeatingFuelType']));
	$FacilityType = $mysqli->real_escape_string(trim($_POST['FacilityType']));
	$ElectricBillAcct = $mysqli->real_escape_string(trim($_POST['ElectricBillNum']));
	$AreaSQFT = $mysqli->real_escape_string(trim($_POST['SquareFootage']));
	$ContactName = $mysqli->real_escape_string(trim($_POST['ContactName']));
	$ContactPhone = $mysqli->real_escape_string(trim($_POST['ContactPhone']));
	$ContactEmail = $mysqli->real_escape_string(trim($_POST['ContactEmail']));
	$Salesperson = $mysqli->real_escape_string(trim($_POST['Salesperson']));
	$FinanceType = $mysqli->real_escape_string(trim($_POST['FinanceType']));
	$FinanceTerm = $mysqli->real_escape_string(trim($_POST['FinanceTerm']));
	$FinanceAmount = $mysqli->real_escape_string(trim($_POST['FinanceAmount']));
	

	//Insert Query
	$q = "INSERT INTO project (project_name, business_name, program, program_id, project_status, CreateDate, address1, address2, city, state, zip, Project_Cost, KWH_Savings, Incentive, Financing, FinanceType, FinanceAmount, FinanceTerm, Comprehensive, Tax_Exempt, HeatingFuelType, FacilityType, ElectricBillAcct, 
		contact_name, contact_phone, contact_email, salesperson_id) VALUES('$ProjectName', '$BusinessName', '$ProgramNum', '$ProgramID', 'In Development', NOW(), '$Address1', '$Address2',
		'$City', '$State', '$Zip', '$ProjectCost', '$KWHSavings', '$Incentive', $Financing, '$FinanceType', '$FinanceTerm', '$FinanceAmount', $Comprehensive, $TaxExempt, '$HeatingFuelType', '$FacilityType', '$ElectricBillAcct', '$ContactName', '$ContactPhone', '$ContactEmail', $Salesperson)";
	$r = $mysqli->query($q); //Run Query
	if($mysqli->affected_rows == 1){ //If query ran 
		$Q_Get_ID = "SELECT AUTO_INCREMENT
			FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_SCHEMA = 'jkenergysow'
			AND TABLE_NAME = 'project'";
		$R_Get_ID = $mysqli->query($Q_Get_ID);
		$ID = $R_Get_ID->fetch_array(MYSQLI_NUM);
		$RealID = $ID[0] - 1;
		$Q_Add_Audit = "INSERT INTO audit (project_id, audit_type, area_sqft, author_id)
		 VALUES('$RealID', 'Sales Version', '$AreaSQFT', $UserID)";
		$R_Add_Audit = $mysqli->query($Q_Add_Audit);
		if($mysqli->affected_rows == 1){
			$Q_Update_Members = "INSERT INTO ProjectMembers (Project_ID, User_ID)
					VALUES($RealID, $UserID)";
			$mysqli->query($Q_Update_Members);
			if ($mysqli->affected_rows == 1){
				$Q_AddTask = "INSERT INTO Messages (Message, Author_ID, Project_ID, Message_Type, MCode, Completed)
								VALUES('Develop project proposal.  ', '$UserID', '$RealID', 'Task', 1, 0)";
				$mysqli->query($Q_AddTask);
				$Last_ID = $mysqli->insert_id;
				$Q_AddTaskRecipients = "INSERT INTO Message_Recipients (Message_ID, Recipient_ID)
									VALUES('$Last_ID', $UserID)";
				$mysqli->query($Q_AddTaskRecipients);
				echo'<meta http-equiv="refresh" content="0; url=Edit_Project.php?proj_id=' . $RealID . '" />';
			}
		}
	}
	else {
		// Public message:
		echo '<h1>System Error</h1>
		<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
		// Debugging message:
		echo '<p>' . $mysqli->error . '<br /><br />Query: ' . $q . '</p>';
	}
}
?>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create Project</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">

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
        <?
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');
        ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Create Project</h3>
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
                  <div class="x_title">
                    <h2>Project Information</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form role="form" action="Create_Project.php" class="form-horizontal form-label-left" novalidate method="post">
					  
					  <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ProjectName">Project Name</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12 requiredcolor" placeholder="Business/Trade/Known Name" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="BusinessName">Account Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input id="BusinessName" name="BusinessName" class="form-control col-md-7 col-xs-12" placeholder="Name Associated With Utility Account" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address1">Address</label>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input id="Address1" name="Address1" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input id="Address2" name="Address2" placeholder="Apartment, Suite, Unit, etc." class="form-control col-md-7 col-xs-12" value="' . $row['address2'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address"></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input id="State" name="State" placeholder="State (Two Letter)" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['state'] . '" data-inputmask="\'mask\': \'AA\'" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" data-inputmask="\'mask\': \'99999\'">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ContactName">Contact Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input id="ContactName" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="ContactName" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ContactPhone">Contact Information</label>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input id="ContactPhone" name="ContactPhone" placeholder="Contact Phone Number*" required="required" data-inputmask="\'mask\': \'9999999999\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input type="email" id="ContactEmail" name="ContactEmail" placeholder="Contact Email" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Salesperson</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select id="Salesperson" name ="Salesperson" class="form-control requiredcolor" tabindex="-1" required="required">
								<option value="" hidden></option>
								<?php
								while($row_salesperson = $rslt_salespersons->fetch_array(MYSQLI_NUM)){
									if ($row['salesperson_id']==$row_salesperson[0]) {
										echo "\t"; //add tab to html source
										echo '<option value="'. $row_salesperson[0] .'" selected="selected">' . $row_salesperson[1] . '</option>';
										echo "\n"; // add line break to html source
									}
									else {
										echo "\t"; //add tab to html source
										echo '<option value="' . $row_salesperson[0] . '">' . $row_salesperson[1] . '</option>';
										echo "\n"; // add line break to html source
									}
								}
								?>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Cost">Financial Information</label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<input id="Cost" name="Cost" placeholder="Project Cost" data-inputmask="'repeat': 10, 'greedy': 'false'" class="form-control col-md-7 col-xs-12">
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<input id="KWH" name="KWH" placeholder="kWh Savings" data-inputmask="'repeat': 10, 'greedy': 'false'" class="form-control col-md-7 col-xs-12">
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<input id="Incentive" name="Incentive" placeholder="Incentive" data-inputmask="'repeat': 10, 'greedy': 'false'" class="form-control col-md-7 col-xs-12">
							</div>
						</div>
					  <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Program Type
						</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select name="ProgramNum" class="form-control" tabindex="-1">
							<option value="0"></option>
							<?php
							while($Row_Programs = $R_Programs->fetch_array(MYSQLI_ASSOC)){
								if ($row['program'] == $Row_Programs['program_id']) {
									echo '<option value="' . $Row_Programs['program_id'] . '" selected="selected">' . $Row_Programs['short_desc'] . '</option>';
								}
								else {
									echo '<option value="' . $Row_Programs['program_id'] . '">' . $Row_Programs['short_desc'] . '</option>';
								}
							}
							?>
                          </select>
                        </div>
                      </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ProgramID">Facility Information
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                          <input id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12">
                        </div>
						<div class="col-md-3 col-sm-3 col-xs-6">
                          <input id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-2 col-sm-2 col-xs-4">
                          <input id="SquareFootage" name="SquareFootage" placeholder="Square Footage" data-inputmask="'mask': '9', 'repeat': 9, 'greedy': 'false'" class="form-control col-md-7 col-xs-12">
                        </div>
						<div class="col-md-2 col-sm-2 col-xs-4">
                          <select name="HeatingFuelType" class="form-control" tabindex="-1">
								<option value="0" disabled selected hidden>Fuel Type</option>
								<?php
								while($Row_Programs = $R_FuelType->fetch_array(MYSQLI_ASSOC)){
									if ($row['HeatingFuelType'] == $Row_Programs['Fuel_ID']) {
										echo '<option value="' . $Row_Programs['Fuel_ID'] . '" selected="selected">' . $Row_Programs['Fuel_Type_Name'] . '</option>';
									}
									else {
										echo '<option value="' . $Row_Programs['Fuel_ID'] . '">' . $Row_Programs['Fuel_Type_Name'] . '</option>';
									}
								}
								?>
							  </select>
                        </div>
						<div class="col-md-2 col-sm-2 col-xs-4">
                          <select name="FacilityType" class="form-control" tabindex="-1">
								<option value="0" disabled selected hidden>Facility Type</option>
								<?php
								while($Row_Programs = $R_FacilityType->fetch_array(MYSQLI_ASSOC)){
									if ($row['FacilityType'] == $Row_Programs['Facility_ID']) {
										echo '<option value="' . $Row_Programs['Facility_ID'] . '" selected="selected">' . $Row_Programs['Facility_Type_Name'] . '</option>';
									}
									else {
										echo '<option value="' . $Row_Programs['Facility_ID'] . '">' . $Row_Programs['Facility_Type_Name'] . '</option>';
									}
								}
								?>
							  </select>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <a href="index.php" class="btn btn-primary">Cancel</a>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>
					</form>
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
	<!-- validator -->
    <script src="../vendors/validator/validator.js"></script>
	<!-- jquery.inputmask -->
    <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
	<!-- jQuery Smart Wizard -->
    <script src="../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
	<!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
	<!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>

	<!-- Setting up the page -->
	<script>
	$(".requiredcolor").each(function() {
		if ($(this).val().length !== 0) {
			$(this).removeClass("requiredcolor");
		}
	});
	$('#FinanceInfo').hide();
	$('#ComprehensiveItems').hide();
	</script>
	<!-- validator -->
	<!-- jquery.inputmask -->
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <!-- /jquery.inputmask -->
    <script>
      // initialize the validator function
      validator.message.date = 'not a real date';

      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
      });
    </script>
    <!-- /validator -->
	<!-- Functionality with buttons -->
	<script>
	$('#Financing').click(function() {
		Value = $(this).is(':checked') ? 1 : 0;
		if (Value == 1){
			$('#FinanceInfo').show(300);
		}	
		else{
			$('#FinanceInfo').hide(300);
		}
	  });
//	  $('#Comprehensive').click(function() {
//		Value = $(this).is(':checked') ? 1 : 0;
//		if (Value == 1){
//			$('#ComprehensiveItems').show(300);
//		}	
//		else{
//			$('#ComprehensiveItems').hide(300);
//		}
//	  });
	</script>
	
  </body>
</html>