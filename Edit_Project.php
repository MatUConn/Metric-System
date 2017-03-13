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
//Salesperson Queries
$q_get_salespersons = "SELECT contact_id, CONCAT(first_name, ' ', last_name) AS 'full_name',
		CONCAT('(', SUBSTRING(phone_num, 1, 3), ') ', SUBSTRING(phone_num, 4, 3), '-', SUBSTRING(phone_num, 7, 4)) AS 'formatted_phone'
		FROM contact
		WHERE role = 'Salesperson'
		AND Inactive = '0'
		ORDER BY first_name";
$rslt_salespersons = $mysqli->query($q_get_salespersons);
//Project Manager Queries
$q_get_proj_managers = "SELECT contact_id, CONCAT(first_name, ' ', last_name) AS 'full_name',
		CONCAT('(', SUBSTRING(phone_num, 1, 3), ') ', SUBSTRING(phone_num, 4, 3), '-', SUBSTRING(phone_num, 7, 4)) AS 'formatted_phone'
		FROM contact
		WHERE role = 'Project Manager'
		AND Inactive = '0'
		ORDER BY first_name";
$rslt_proj_managers = $mysqli->query($q_get_proj_managers);
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
//Comprehensive Type List Queries
$Q_ComprehensiveType = "SELECT Finance_ID, DropDownText
		FROM DropDownOptions
		WHERE Active = 1
		AND DropDown = 'ComprehensiveType'
		ORDER BY DropDownText";
$R_ComprehensiveType = $mysqli->query($Q_ComprehensiveType);
//Users List Queries
$Q_Users = "SELECT user_id, CONCAT(first_name, ' ', last_name) AS Name
		FROM user
		WHERE enabled_flag = 1
		ORDER BY Name";
$R_Users = $mysqli->query($Q_Users);
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
	$ContactName = $mysqli->real_escape_string(trim($_POST['ContactName']));
	$ContactPhone = $mysqli->real_escape_string(trim($_POST['ContactPhone']));
	$ContactEmail = $mysqli->real_escape_string(trim($_POST['ContactEmail']));
	$Salesperson = $mysqli->real_escape_string(trim($_POST['Salesperson']));
	

	//Insert Query
	$q = "INSERT INTO project (project_name, business_name, program, program_id, project_status, address1, address2, city, state, zip, Project_Cost, KWH_Savings, Incentive, Financing, Comprehensive, Tax_Exempt, HeatingFuelType, FacilityType, ElectricBillAcct, 
		contact_name, contact_phone, contact_email, salesperson_id) VALUES('$ProjectName', '$BusinessName', '$ProgramNum', '$ProgramID', 'In Development', '$Address1', '$Address2',
		'$City', '$State', '$Zip', '$ProjectCost', '$KWHSavings', '$Incentive', $Financing, $Comprehensive, $TaxExempt, '$HeatingFuelType', '$FacilityType', '$ElectricBillAcct', '$ContactName', '$ContactPhone', '$ContactEmail', $Salesperson)";
	$r = $mysqli->query($q); //Run Query
	if($mysqli->affected_rows == 1){ //If query ran 
		$Q_Get_ID = "SELECT AUTO_INCREMENT
			FROM INFORMATION_SCHEMA.TABLES
			WHERE TABLE_SCHEMA = 'jkenergysow'
			AND TABLE_NAME = 'project'";
		$R_Get_ID = $mysqli->query($Q_Get_ID);
		$ID = $R_Get_ID->fetch_array(MYSQLI_NUM);
		$RealID = $ID[0] - 1;
		//a href="Edit_Project.php?proj_id='. $row['project_id'] . '"
		echo'<meta http-equiv="refresh" content="0; url=Edit_Project.php?proj_id=' . $RealID . '" />';
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

    <title>Edit Project</title>

    <!-- Bootstrap -->
    <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- Select2 -->
    <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
	<!-- Switchery -->
    <link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../build/css/custom.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.ico">
	<!-- PNotify -->
    <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
		include (GEN_INCLUDES_PATH . 'Left_Menu.html');
		include (GEN_INCLUDES_PATH . 'Top_Navigation.html');
		
        $q_get_project = "SELECT project_name, business_name, program, program_id, project_status, DeadBool, address1, address2, city, state, zip, 
			Project_Cost, KWH_Savings, Incentive, Financing, FinanceType, FinanceAmount, FinanceTerm, Comprehensive, Tax_Exempt, ExemptionType, 
			HeatingFuelType, FacilityType, ElectricBillAcct, date(DateSigned) AS DateSigned, date(UtilityApprovalDate) AS UtilityApprovalDate, 
			date(PMWalkDate) AS PMWalkDate, date(MaterialsOrderedDate) AS MaterialsOrderedDate, date(InstallDate) AS InstallDate, PMChanges, 
			date(SignedFinalsRecieved) AS SignedFinalsRecieved, ValueChanged, date(PaidDate) AS PaidDate, contact_name, contact_phone, contact_email, 
			Installation_Contact_Name, Installation_Contact_Phone, salesperson_id, pm_id
			FROM project
			WHERE project_ID = $Project_ID";
		if (!($r_get_project = $mysqli->query($q_get_project)) ) {
			echo ' <div class="right_col" role="main"><div class="alert alert-danger">There was an error with the query.  If this problem persists please email the site admin at mfox@energyresourcesusa.net</div></div>';
		}
		if ($r_get_project->num_rows == 1) {
			$row = $r_get_project->fetch_array(MYSQLI_ASSOC);
			if ($row['Project_Cost'] == 0){
				$row['Project_Cost'] = "";
			}
			if ($row['KWH_Savings'] == 0){
				$row['KWH_Savings'] = "";
			}
			if ($row['Incentive'] == 0){
				$row['Incentive'] = "";
			}
			if ($row['Incentive'] == 0){
				$row['Incentive'] = "";
			}
			if ($row['FinanceTerm'] == 0){
				$row['FinanceTerm'] = "";
			}
			if ($row['FinanceAmount'] == 0){
				$row['FinanceAmount'] = "";
			}
			$q_get_audit = "SELECT area_sqft
				FROM audit
				WHERE project_id = $Project_ID";
			$r_get_audit = $mysqli->query($q_get_audit);
			$audit = $r_get_audit->fetch_array(MYSQLI_ASSOC);
			if ($audit['area_sqft'] == 0){
				$audit['area_sqft'] = "";
			}
			switch ($row['project_status']) {
				case 'In Development':
					$CurrentStep = 1;
					$Statuses = array("In Development");
					break;
				case 'With Seller/Proposed':
					$CurrentStep = 2;
					$Statuses = array("In Development", "With Seller/Proposed");
					break;
				case 'Signed; Not To Production':
					$CurrentStep = 3;
					$Statuses = array("In Development", "With Seller/Proposed", "Signed; Not To Production");
					break;
				case 'In Production':
					$CurrentStep = 4;
					$Statuses = array("In Development", "With Seller/Proposed", "Signed; Not To Production", "In Production");
					break;
				case 'Installed':
					$CurrentStep = 5;
					$Statuses = array("In Development", "With Seller/Proposed", "Signed; Not To Production", "In Production", "Installed");
					break;
				case 'Invoiced':
					$CurrentStep = 6;
					$Statuses = array("In Development", "With Seller/Proposed", "Signed; Not To Production", "In Production", "Installed", "Invoiced");
					break;
				default:
					$CurrentStep = 1;
					$Statuses = array("In Development");
					break;
			}

		?>
        <!-- /top navigation -->

        <!-- page content -->
        <div name="Content2" class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>View Project Pipeline</h3>
              </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Project Info</h2>
					<div class="col-md-2 pull-right">
						<select id="ProjectStatus" name="ProjectStatus" class="form-control" tabindex="-1">
							<?php
							foreach($Statuses as $key => $value){
								if ($row['project_status']==$value) {
									echo '<option value="'.$value .'" selected="selected">'.$value.'</option>';
								}
								else {
									echo '<option value="'.$value.'">'.$value.'</option>';
								}
							}
							?>
						</select>
					</div>
					<div class="pull-right">
					  <h2>Dead?</h2>
						<?php
						  if ($row['DeadBool'] == 1) {
							echo '<input type="checkbox" id="DeadBool" name="DeadBool" class="js-switch" checked/>  ';
						  }
						  else {
							echo '<input type="checkbox" id="DeadBool" name="DeadBool" class="js-switch"/>  ';
						  }
						?>
					</div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <!-- Smart Wizard -->
                    <div id="wizard" class="form_wizard wizard_horizontal">
                      <ul class="wizard_steps">
                        <li>
                          <a href="#step-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">
								In Development<br />
							</span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">
								With Seller/Proposed<br />
							</span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">
								Signed; Not To Production<br />
							</span>
                          </a>
                        </li>
                        <li>
                          <a href="#step-4">
                            <span class="step_no">4</span>
                            <span class="step_descr">
								In Production<br />
							</span>
                          </a>
                        </li>
						<li>
                          <a href="#step-5">
                            <span class="step_no">5</span>
                            <span class="step_descr">
								Installed<br />
							</span>
                          </a>
                        </li>
						<li>
                          <a href="#step-6">
                            <span class="step_no">6</span>
                            <span class="step_descr">
								Invoiced<br />
							</span>
                          </a>
                        </li>
                      </ul>
                      <div id="step-1"><!--THIS IS STEP 1-->
                        <form class="form-horizontal form-label-left">
						
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-6 col-sm-6 col-xs-12">Project Name</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ProjectName">Project Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-6 col-sm-6 col-xs-12">Business Name</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="BusinessName">Business Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" id="BusinessName" name="BusinessName" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-12">Street Address</div>
							<div class="col-md-3 col-sm-3 col-xs-12">Apartment, Suite, Unit, etc.</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address">Address</label>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input autocomplete="off" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input autocomplete="off" id="Address2" name="Address2" placeholder="Apartment, Suite, Unit, etc." class="form-control col-md-7 col-xs-12" value="' . $row['address2'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-2 col-sm-2 col-xs-4">State (Two Letter)</div>
							<div class="col-md-3 col-sm-3 col-xs-4">Zip Code</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Address"></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input autocomplete="off" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['city'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input autocomplete="off" id="State" name="State" placeholder="State (Two Letter)" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input autocomplete="off" id="ZipCode" name="ZipCode" placeholder="Zip Code" data-inputmask="\'mask\': \'99999\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-6 col-sm-6 col-xs-12">Contact Name</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Contact Name</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" id="name" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Phone Number</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="telephone">Contact Information</label>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input autocomplete="off" type="tel" id="telephone" name="phone" placeholder="Contact Phone Number" required="required" data-inputmask="\'mask\': \'9999999999\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input autocomplete="off" type="email" id="email" name="email" placeholder="Contact Email" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-6 col-sm-6 col-xs-12">Salesperson</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Salesperson</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select id="Salesperson" name="Salesperson" class="form-control requiredcolor" tabindex="-1">
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
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">Project Value</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Incentive</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Cost">Financial Information</label>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input autocomplete="off" id="Cost" name="Cost" placeholder="Project Value" data-inputmask="\'repeat\': 10, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['Project_Cost'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input autocomplete="off" id="KWH" name="KWH" placeholder="kWh Savings" data-inputmask="\'repeat\': 10, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['KWH_Savings'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input autocomplete="off" id="Incentive" name="Incentive" placeholder="Incentive" data-inputmask="\'repeat\': 10, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $row['Incentive'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-6 col-sm-6 col-xs-12">Program</div>
						</div>
					   <div class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Program Type</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select id="ProgramNum" name="ProgramNum" class="form-control requiredcolor" tabindex="-1">
						  <option value="" hidden></option>
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
					  <div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Account #</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="ProgramID">Facility Information
                        </label>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                        <?php
						  echo'<input autocomplete="off" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
						?>
                        </div>
						<div class="col-md-3 col-sm-3 col-xs-6">
						<?php
                          echo'<input autocomplete="off" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
						?>
						</div>
                      </div>
					  <div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Fuel Type</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Facility Type</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-2 col-sm-2 col-xs-4">
						<?php
                          echo'<input autocomplete="off" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" data-inputmask="\'mask\': \'9\', \'repeat\': 9, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12 requiredcolor" value="' . $audit['area_sqft'] . '" required="required">';
						?>
                        </div>
						<div class="col-md-2 col-sm-2 col-xs-4">
							  <select id="HeatingFuelType" name="HeatingFuelType" class="form-control" tabindex="-1">
								<option value="" disabled selected hidden>Fuel Type</option>
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
							  <select id="FacilityType" name="FacilityType" class="form-control requiredcolor" tabindex="-1">
								<option value="" disabled selected hidden>Facility Type</option>
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
					  <div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<?php
									if ($row['Financing'] == 1) {
										echo '<input type="checkbox" id="Financing" name="Financing" class="js-switch" checked/> <b>Financing?</b>';
									}
									else {
										echo '<input type="checkbox" id="Financing" name="Financing" class="js-switch"/> <b>Financing?</b>';
									}
									?>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input type="checkbox" id="Comprehensive" name="Comprehensive" value="1" class="js-switch" checked/> <b>Comprehensive?</b>';
									}
									else {
										echo '<input type="checkbox" id="Comprehensive" name="Comprehensive" value="1" class="js-switch"/> <b>Comprehensive?</b>';
									}
									?>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input type="checkbox" id="TaxExempt" name="TaxExempt" value="1" class="js-switch" checked/> <b>Tax Exempt?</b>';
									}
									else {
										echo '<input type="checkbox" id="TaxExempt" name="TaxExempt" value="1" class="js-switch"/> <b>Tax Exempt?</b>';
									}
									?>
								</div>
							</div>
						</div>
						<div id="FinanceInfo">
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-3 col-sm-3 col-xs-12"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">Finance Type</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Finance Term (Months)</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Finance Amount</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="Cost">Financing Information</label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<select id="FinanceType" name="FinanceType" class="form-control" tabindex="-1">
									<option value="0" disabled selected hidden>Finance Type</option>
									<?php
									while($Row_Finance = $R_FinanceType->fetch_array(MYSQLI_ASSOC)){
										if ($row['FinanceType'] == $Row_Finance['Finance_ID']) {
											echo '<option value="' . $Row_Finance['Finance_ID'] . '" selected="selected">' . $Row_Finance['DropDownText'] . '</option>';
										}
										else {
											echo '<option value="' . $Row_Finance['Finance_ID'] . '">' . $Row_Finance['DropDownText'] . '</option>';
										}
									}
									?>
								</select>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<?php echo'<input id="FinanceTerm" name="FinanceTerm" placeholder="Finance Term" data-inputmask="\'mask\': \'9\', \'repeat\': 2, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12" value="' . $row['FinanceTerm'] . '">';?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<?php echo'<input id="FinanceAmount" name="FinanceAmount" placeholder="Finance Amount" data-inputmask="\'repeat\': 10, \'greedy\': \'false\'" class="form-control col-md-7 col-xs-12" value="' . $row['FinanceAmount'] . '">';?>
							</div>
						</div>
						</div>
						<div id="ComprehensiveItems" class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Comprehensive Items</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select id="ComprehensiveList" name="ComprehensiveList" class="select2_multiple form-control" multiple="multiple" tabindex="-1" style="width: 100%">
							<?php
							while($Row_Comprehensive = $R_ComprehensiveType->fetch_array(MYSQLI_ASSOC)){ //This has row['program'], once I save it, I need to plug the proper source in
								if ($row['program'] == $Row_Comprehensive['Finance_ID']) {
									echo '<option value="' . $Row_Comprehensive['Finance_ID'] . '" selected="selected">' . $Row_Comprehensive['DropDownText'] . '</option>';
								}
								else {
									echo '<option value="' . $Row_Comprehensive['Finance_ID'] . '">' . $Row_Comprehensive['DropDownText'] . '</option>';
								}
							}
							?>
                          </select>
                        </div>
                      </div>
					  <div id="TaxExemptInfo" class="item form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Exemption Type</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select id="ExemptionType" name="ExemptionType" class="form-control" tabindex="-1">
						  <option value="0" disabled hidden selected></option>
							<option value="Nonprofit" <?php if ($row['ExemptionType'] == 'Nonprofit') echo ' selected'; ?>>Nonprofit</option>
							<option value="Municipality" <?php if ($row['ExemptionType'] == 'Municipality') echo ' selected'; ?>>Municipality</option>
                          </select>
                        </div>
                      </div>
					  
                        </form>

                      </div>
                      <div id="step-2"><!--THIS IS STEP 2-->
                         <form class="form-horizontal form-label-left">

						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Project Name</div>
							<div class="col-md-4 col-sm-4 col-xs-8">Business Name</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="BusinessName" name="BusinessName" placeholder="Business Name" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Street Address</div>
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-1 col-sm-1 col-xs-2">State</div>
							<div class="col-md-1 col-sm-1 col-xs-2">Zip Code</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="State" name="State" placeholder="State" class="form-control col-md-7 col-xs-12" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Name</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Contact Phone</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="name" class="form-control col-md-7 col-xs-12" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" type="tel" id="telephone" name="phone" required="required" data-inputmask="\'mask\': \'(999) 999-9999\'" class="form-control col-md-7 col-xs-12" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" type="email" id="email" name="email" placeholder="Email Address" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Project Cost</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Incentive</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Cost" name="Cost" placeholder="Project Value" class="form-control col-md-7 col-xs-12" value="' . $row['Project_Cost'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input readonly="readonly" id="KWH" name="KWH" placeholder="kWh Savings" class="form-control col-md-7 col-xs-12" value="' . $row['KWH_Savings'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="Incentive" name="Incentive" placeholder="Incentive" class="form-control col-md-7 col-xs-12" value="' . $row['Incentive'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Number</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
							$ProgramID = $row['program'];
							$Q_Program = "SELECT Program_Name
								FROM Program
								WHERE Program_ID = $ProgramID";
							$R_Program = $mysqli->query($Q_Program);
							$Program = $R_Program->fetch_array(MYSQLI_NUM);
								echo'<input readonly="readonly" id="ProgramType" name="ProgramType" placeholder="Program Type" class="form-control col-md-7 col-xs-12" value="' . $Program[0] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Heating Fuel Type</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Facility Type</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" class="form-control col-md-7 col-xs-12" value="' . $audit['area_sqft'] . '">';
								?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
							$FuelTypeID = $row['HeatingFuelType'];
							if ($FuelTypeID == '' || $FuelTypeID == NULL){
								$FuelType[0] = "";
							}
							else {
							$Q_FuelType = "SELECT Fuel_Type_Name
								FROM Fuel_Type
								WHERE Fuel_ID = $FuelTypeID";
							$R_FuelType = $mysqli->query($Q_FuelType);
							$FuelType = $R_FuelType->fetch_array(MYSQLI_NUM);
							}
								echo'<input readonly="readonly" id="HeatingFuelType" name="HeatingFuelType" placeholder="Heating Fuel Type" class="form-control col-md-7 col-xs-12" value="' . $FuelType[0] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
							$FacilityTypeID = $row['FacilityType'];
							if ($FacilityTypeID == '' || $FacilityTypeID == NULL){
								$FacilityType[0] = "";
							}
							else {
							$Q_FacilityType = "SELECT Facility_Type_Name
								FROM Facility_Type
								WHERE Facility_ID = $FacilityTypeID";
							$R_FacilityType = $mysqli->query($Q_FacilityType);
							$FacilityType = $R_FacilityType->fetch_array(MYSQLI_NUM);
							}
								echo'<input readonly="readonly" id="FacilityType" name="FacilityType" placeholder="Facility Type" class="form-control col-md-7 col-xs-12" value="' . $FacilityType[0] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Financing'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Financing" class="js-switch" checked/> Financing';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Financing"  class="js-switch"/> Financing';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive" class="js-switch" checked/> Comprehensive';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive"  class="js-switch"/> Comprehensive';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt" class="js-switch" checked/> Tax Exempt';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt"  class="js-switch"/> Tax Exempt';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<?php
							$SalespersonID = $row['salesperson_id'];
							$Q_Salesperson = "SELECT CONCAT(first_name, ' ', last_name) AS 'full_name'
								FROM contact
								WHERE contact_id = $SalespersonID";
							$R_Salesperson = $mysqli->query($Q_Salesperson);
							$Salesperson = $R_Salesperson->fetch_array(MYSQLI_NUM);
							echo'<div class="col-md-8 col-sm-8 col-xs-12">
							Salesperson: ' . $Salesperson[0] . '</div>';
							?>
						</div>
						<span class="section"></span><!--New content for this state-->
						<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Signed<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="DateSigned" name="DateSigned" placeholder="yyyy/mm/dd" class="form-control requiredcolor" value="' . $row['DateSigned'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
                        </form>

                      </div>
                      <div id="step-3"><!--THIS IS STEP 3-->
                        <form class="form-horizontal form-label-left">
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Project Name</div>
							<div class="col-md-4 col-sm-4 col-xs-8">Business Name</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="BusinessName" name="BusinessName" placeholder="Business Name" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Street Address</div>
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-1 col-sm-1 col-xs-2">State</div>
							<div class="col-md-1 col-sm-1 col-xs-2">Zip Code</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="State" name="State" placeholder="State" class="form-control col-md-7 col-xs-12" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Name</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Contact Phone</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="name" class="form-control col-md-7 col-xs-12" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" type="tel" id="telephone" name="phone" required="required" data-inputmask="\'mask\': \'(999) 999-9999\'" class="form-control col-md-7 col-xs-12" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" type="email" id="email" name="email" placeholder="Email Address" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Project Cost</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Incentive</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Cost" name="Cost" placeholder="Project Value" class="form-control col-md-7 col-xs-12" value="' . $row['Project_Cost'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input readonly="readonly" id="KWH" name="KWH" placeholder="kWh Savings" class="form-control col-md-7 col-xs-12" value="' . $row['KWH_Savings'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="Incentive" name="Incentive" placeholder="Incentive" class="form-control col-md-7 col-xs-12" value="' . $row['Incentive'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Number</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="ProgramType" name="ProgramType" placeholder="Program Type" class="form-control col-md-7 col-xs-12" value="' . $Program[0] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Heating Fuel Type</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Facility Type</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" class="form-control col-md-7 col-xs-12" value="' . $audit['area_sqft'] . '">';
								?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="HeatingFuelType" name="HeatingFuelType" placeholder="Heating Fuel Type" class="form-control col-md-7 col-xs-12" value="' . $FuelType[0] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="FacilityType" name="FacilityType" placeholder="Facility Type" class="form-control col-md-7 col-xs-12" value="' . $FacilityType[0] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Financing'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Financing" class="js-switch" checked/> Financing';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Financing"  class="js-switch"/> Financing';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive" class="js-switch" checked/> Comprehensive';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive"  class="js-switch"/> Comprehensive';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt" class="js-switch" checked/> Tax Exempt';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt"  class="js-switch"/> Tax Exempt';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<?php
							echo'<div class="col-md-8 col-sm-8 col-xs-12">
							Salesperson: ' . $Salesperson[0] . '</div>';
							?>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Signed on: ' . $row['DateSigned'] . '';
							?>
							</div>
						</div>
						<span class="section"></span><!--New content for this state-->
						<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Utility Approval Date<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="UtilityApprovalDate" name="UtilityApprovalDate" placeholder="yyyy/mm/dd" class="form-control" value="' . $row['UtilityApprovalDate'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
                        </form>

                      </div>
                      <div id="step-4"><!--THIS IS STEP 4-->
                         <form class="form-horizontal form-label-left">
						 <div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Project Name</div>
							<div class="col-md-4 col-sm-4 col-xs-8">Business Name</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="BusinessName" name="BusinessName" placeholder="Business Name" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Street Address</div>
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-1 col-sm-1 col-xs-2">State</div>
							<div class="col-md-1 col-sm-1 col-xs-2">Zip Code</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="State" name="State" placeholder="State" class="form-control col-md-7 col-xs-12" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Name</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Contact Phone</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="name" class="form-control col-md-7 col-xs-12" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" type="tel" id="telephone" name="phone" required="required" data-inputmask="\'mask\': \'(999) 999-9999\'" class="form-control col-md-7 col-xs-12" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" type="email" id="email" name="email" placeholder="Email Address" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Project Cost</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Incentive</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Cost" name="Cost" placeholder="Project Value" class="form-control col-md-7 col-xs-12" value="' . $row['Project_Cost'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input readonly="readonly" id="KWH" name="KWH" placeholder="kWh Savings" class="form-control col-md-7 col-xs-12" value="' . $row['KWH_Savings'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Incentive" name="Incentive" placeholder="Incentive" class="form-control col-md-7 col-xs-12" value="' . $row['Incentive'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Number</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="ProgramType" name="ProgramType" placeholder="Program Type" class="form-control col-md-7 col-xs-12" value="' . $Program[0] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Heating Fuel Type</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Facility Type</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" class="form-control col-md-7 col-xs-12" value="' . $audit['area_sqft'] . '">';
								?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="HeatingFuelType" name="HeatingFuelType" placeholder="Heating Fuel Type" class="form-control col-md-7 col-xs-12" value="' . $FuelType[0] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="FacilityType" name="FacilityType" placeholder="Facility Type" class="form-control col-md-7 col-xs-12" value="' . $FacilityType[0] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Financing'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Financing" class="js-switch" checked/> Financing';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Financing"  class="js-switch"/> Financing';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive" class="js-switch" checked/> Comprehensive';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive"  class="js-switch"/> Comprehensive';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt" class="js-switch" checked/> Tax Exempt';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt"  class="js-switch"/> Tax Exempt';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<?php
							echo'<div class="col-md-8 col-sm-8 col-xs-12">
							Salesperson: ' . $Salesperson[0] . '</div>';
							?>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Signed On: ' . $row['DateSigned'] . '';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-8 col-sm-8 col-xs-12">
							<?php
							echo'Utility Approval On: ' . $row['UtilityApprovalDate'] . '';
							?>
							</div>
						</div>
						<span class="section"></span><!--New content for this state-->
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Project Manager<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select id="ProjectManager" name= "ProjectManager" class="form-control" tabindex="-1">
								<option value="undefined"></option>
								<?php
								while($row_proj_manager = $rslt_proj_managers->fetch_array(MYSQLI_NUM)){
									if ($row['pm_id']==$row_proj_manager[0]) {
										echo "\t"; //add tab to html source
										echo '<option value="'. $row_proj_manager[0] .'" selected="selected">' . $row_proj_manager[1] . '</option>';
										echo "\n"; // add line break to html source
									}
									else {
										echo "\t"; //add tab to html source
										echo '<option value="' . $row_proj_manager[0] . '">' . $row_proj_manager[1] . '</option>';
										echo "\n"; // add line break to html source
									}
								}
								?>
								</select>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Date of PM Walk<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="PMWalkDate" name="PMWalkDate" placeholder="yyyy/mm/dd" class="form-control" value="' . $row['PMWalkDate'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Date Materials Ordered<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="MaterialsOrderedDate" name="MaterialsOrderedDate" placeholder="yyyy/mm/dd" class="form-control" value="' . $row['MaterialsOrderedDate'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Scheduled Install Date<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="InstallDate" name="InstallDate" placeholder="yyyy/mm/dd" class="form-control" value="' . $row['InstallDate'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">PM Changes?<span class="required">*</span></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<?php
									if ($row['PMChanges'] == 1) {
										echo '<input type="checkbox" value="1" name="PMChanges" class="js-switch" checked/>';
									}
									else {
										echo '<input type="checkbox" value="1" name="PMChanges"  class="js-switch"/>';
									}
									?>
								</div>
							</div>
						</div>
                        </form>

                      </div>
					  <div id="step-5"><!--THIS IS STEP 5-->
                        <form class="form-horizontal form-label-left">
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Project Name</div>
							<div class="col-md-4 col-sm-4 col-xs-8">Business Name</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="BusinessName" name="BusinessName" placeholder="Business Name" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Street Address</div>
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-1 col-sm-1 col-xs-2">State</div>
							<div class="col-md-1 col-sm-1 col-xs-2">Zip Code</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="State" name="State" placeholder="State" class="form-control col-md-7 col-xs-12" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Name</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Contact Phone</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="name" class="form-control col-md-7 col-xs-12" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" type="tel" id="telephone" name="phone" required="required" data-inputmask="\'mask\': \'(999) 999-9999\'" class="form-control col-md-7 col-xs-12" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" type="email" id="email" name="email" placeholder="Email Address" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Project Cost</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Incentive</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Cost" name="Cost" placeholder="Project Value" class="form-control col-md-7 col-xs-12" value="' . $row['Project_Cost'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input readonly="readonly" id="KWH" name="KWH" placeholder="kWh Savings" class="form-control col-md-7 col-xs-12" value="' . $row['KWH_Savings'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="Incentive" name="Incentive" placeholder="Incentive" class="form-control col-md-7 col-xs-12" value="' . $row['Incentive'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Number</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="ProgramType" name="ProgramType" placeholder="Program Type" class="form-control col-md-7 col-xs-12" value="' . $Program[0] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Heating Fuel Type</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Facility Type</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" class="form-control col-md-7 col-xs-12" value="' . $audit['area_sqft'] . '">';
								?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="HeatingFuelType" name="HeatingFuelType" placeholder="Heating Fuel Type" class="form-control col-md-7 col-xs-12" value="' . $FuelType[0] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="FacilityType" name="FacilityType" placeholder="Facility Type" class="form-control col-md-7 col-xs-12" value="' . $FacilityType[0] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Financing'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Financing" class="js-switch" checked/> Financing';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Financing"  class="js-switch"/> Financing';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive" class="js-switch" checked/> Comprehensive';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive"  class="js-switch"/> Comprehensive';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt" class="js-switch" checked/> Tax Exempt';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt"  class="js-switch"/> Tax Exempt';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<?php
							$PMID = $row['pm_id'];
							if ($PMID == '' || $PMID == NULL){
								$ProjectManager[0] = "Not Specified";
							}
							else{
							$Q_ProjectManager = "SELECT CONCAT(first_name, ' ', last_name) AS 'full_name'
								FROM contact
								WHERE contact_id = $PMID";
							$R_ProjectManager = $mysqli->query($Q_ProjectManager);
							$ProjectManager = $R_ProjectManager->fetch_array(MYSQLI_NUM);
							}
							echo'	<div class="col-md-4 col-sm-4 col-xs-8">
										Sold By: ' . $Salesperson[0] . '
									</div>
									<div class="col-md-4 col-sm-4 col-xs-8">
										Project Manager: ' . $ProjectManager[0] . '
									</div>';
							?>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Signed On: ' . $row['DateSigned'] . '';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'PM Walked On: ' . $row['PMWalkDate'] . '';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Utility Approval On: ' . $row['UtilityApprovalDate'] . '';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Materials Ordered On: ' . $row['MaterialsOrderedDate'] . '';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-8 col-sm-8 col-xs-12">
							<?php
							echo'Scheduled Install Date: ' . $row['InstallDate'] . '';
							?>
							</div>
						</div>
						<span class="section"></span><!--New content for this state-->
						<div class="item form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">Signed Finals Recieved<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="SignedFinalsRecieved" name="SignedFinalsRecieved" placeholder="yyyy/mm/dd" class="form-control requiredcolor" value="' . $row['SignedFinalsRecieved'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Project Value Changed?<span class="required">*</span></label>
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<?php
									if ($row['ValueChanged'] == 1) {
										echo '<input type="checkbox" value="1" name="ValueChanged" class="js-switch" checked/>';
									}
									else {
										echo '<input type="checkbox" value="1" name="ValueChanged"  class="js-switch"/>';
									}
									?>
								</div>
							</div>
						</div>
                        </form>

                      </div>
					  <div id="step-6"><!--THIS IS STEP 6-->
                        <form class="form-horizontal form-label-left">
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Project Name</div>
							<div class="col-md-4 col-sm-4 col-xs-8">Business Name</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="ProjectName" name="ProjectName" class="form-control col-md-7 col-xs-12" value="' . $row['project_name'] . '" required="required">';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="BusinessName" name="BusinessName" placeholder="Business Name" class="form-control col-md-7 col-xs-12" value="' . $row['business_name'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">Street Address</div>
							<div class="col-md-2 col-sm-2 col-xs-4">City</div>
							<div class="col-md-1 col-sm-1 col-xs-2">State</div>
							<div class="col-md-1 col-sm-1 col-xs-2">Zip Code</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-8">
							<?php
								echo'<input readonly="readonly" id="Address" name="Address" placeholder="Street Address" class="form-control col-md-7 col-xs-12" value="' . $row['address1'] . '" required="required">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="City" name="City" placeholder="City" class="form-control col-md-7 col-xs-12" value="' . $row['city'] . '">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="State" name="State" placeholder="State" class="form-control col-md-7 col-xs-12" value="' . $row['state'] . '" required="required" data-inputmask="\'mask\': \'AA\'">';
							?>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-2">
							<?php
								echo'<input readonly="readonly" id="ZipCode" name="ZipCode" placeholder="Zip Code" class="form-control col-md-7 col-xs-12" value="' . $row['zip'] . '" required="required">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Name</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Contact Phone</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Contact Email</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="name" class="form-control col-md-7 col-xs-12" value="' . $row['contact_name'] . '" data-validate-length-range="6" data-validate-words="2" name="name" placeholder="First and Last Name Required" required="required" type="text">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" type="tel" id="telephone" name="phone" required="required" data-inputmask="\'mask\': \'(999) 999-9999\'" class="form-control col-md-7 col-xs-12" value="' . $row['contact_phone'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" type="email" id="email" name="email" placeholder="Email Address" class="form-control col-md-7 col-xs-12" value="' . $row['contact_email'] . '">';
							?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Project Cost</div>
							<div class="col-md-2 col-sm-2 col-xs-4">kWh Savings</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Incentive</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="Cost" name="Cost" placeholder="Project Value" class="form-control col-md-7 col-xs-12" value="' . $row['Project_Cost'] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php	
								echo'<input readonly="readonly" id="KWH" name="KWH" placeholder="kWh Savings" class="form-control col-md-7 col-xs-12" value="' . $row['KWH_Savings'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="Incentive" name="Incentive" placeholder="Incentive" class="form-control col-md-7 col-xs-12" value="' . $row['Incentive'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Program</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Program ID</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Electric Bill Number</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="ProgramType" name="ProgramType" placeholder="Program Type" class="form-control col-md-7 col-xs-12" value="' . $Program[0] . '">';
							?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="ProgramID" name="ProgramID" placeholder="Program ID" class="form-control col-md-7 col-xs-12" value="' . $row['program_id'] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="ElectricBillNum" name="ElectricBillNum" placeholder="Electric Bill Account #" class="form-control col-md-7 col-xs-12" value="' . $row['ElectricBillAcct'] . '">';
								?>
							</div>
						</div>
						<div class="item form-group" style="margin-bottom: 0px">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">Square Footage</div>
							<div class="col-md-2 col-sm-2 col-xs-4">Heating Fuel Type</div>
							<div class="col-md-3 col-sm-3 col-xs-6">Facility Type</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-3 col-sm-3 col-xs-6">
								<?php
								echo'<input readonly="readonly" id="SquareFootage" name="SquareFootage" placeholder="Square Footage" class="form-control col-md-7 col-xs-12" value="' . $audit['area_sqft'] . '">';
								?>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-4">
							<?php
								echo'<input readonly="readonly" id="HeatingFuelType" name="HeatingFuelType" placeholder="Heating Fuel Type" class="form-control col-md-7 col-xs-12" value="' . $FuelType[0] . '">';
							?>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-6">
							<?php
								echo'<input readonly="readonly" id="FacilityType" name="FacilityType" placeholder="Facility Type" class="form-control col-md-7 col-xs-12" value="' . $FacilityType[0] . '">';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Financing'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Financing" class="js-switch" checked/> Financing';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Financing"  class="js-switch"/> Financing';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Comprehensive'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive" class="js-switch" checked/> Comprehensive';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="Comprehensive"  class="js-switch"/> Comprehensive';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-2 col-sm-2 col-xs-4">
								<div class="">
									<label>
									<?php
									if ($row['Tax_Exempt'] == 1) {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt" class="js-switch" checked/> Tax Exempt';
									}
									else {
										echo '<input readonly="readonly" type="checkbox" name="TaxExempt"  class="js-switch"/> Tax Exempt';
									}
									?>
									</label>
								</div>
							</div>
						</div>
						<div class="item form-group">
							<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<?php
							echo'	<div class="col-md-4 col-sm-4 col-xs-8">
										Sold By: ' . $Salesperson[0] . '
									</div>
									<div class="col-md-4 col-sm-4 col-xs-8">
										Project Manager: ' . $ProjectManager[0] . '
									</div>';
							?>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Signed On: ' . $row['DateSigned'] . '';
							?> 
							</div>
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'PM Walked On:  ' . $row['PMWalkDate'] . '';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Utility Approval On: ' . $row['UtilityApprovalDate'] . '';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Materials Ordered On: ' . $row['MaterialsOrderedDate'] . '';
							?>
							</div>
						</div>
						<div class="item form-group">
						<div class="col-md-2 col-sm-2 col-xs-4"></div><!--Placeholder for formatting-->
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Scheduled Install Date: ' . $row['InstallDate'] . '';
							?>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-6">
							<?php
							echo'Signed Finals Received: ' . $row['SignedFinalsRecieved'] . '';
							?>
							</div>
						</div>
						<span class="section"></span><!--New content for this state-->
						<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">Paid Date<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
							<?php
								echo'<input autocomplete="off" type="text" id="PaidDate" name="PaidDate" placeholder="yyyy/mm/dd" class="form-control requiredcolor" value="' . $row['PaidDate'] . '" data-inputmask="\'mask\': \'9999/99/99\'">';
							?>
							</div>
						</div>
                        </form>


                      </div>
					  
                    </div>
                    <!-- End SmartWizard Content -->
					
					<!-- Starting the modal -->
					<div id="TaskModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
									</button>
									<h4 class="modal-title" id="myModalLabel2">Assign Task</h4>
								</div>
								<div class="modal-body">
									<h4>Assign Task To:</h4>
									<select id="TaskUser" class="form-control" tabindex="-1">
										<?php
											while($Row_Users = $R_Users->fetch_array(MYSQLI_ASSOC)){
												if ($UserID == $Row_Users['user_id']) {
													echo '<option selected value="' . $Row_Users['user_id'] . '">' . $Row_Users['Name'] . '</option>';
												}
												else {
													echo '<option value="' . $Row_Users['user_id'] . '">' . $Row_Users['Name'] . '</option>';
												}
											}
										?>
									</select>
									<h4>Task Comment: </h4><h5 id="TText"></h5>
									<input id="CommentInput" class="form-control col-md-6 col-xs-12" placeholder="Additional Comment (Optional)">
								</div>
								<br/><br/>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-success" data-dismiss="modal" onclick="AddTask()">Create Task</button>
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
		<?php
		}
		?>
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
	<!-- jquery.inputmask -->
    <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="../vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
	<!-- Select2 -->
    <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
	<!-- Switchery -->
    <script src="../vendors/switchery/dist/switchery.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../build/js/custom.min.js"></script>
	<!-- PNotify -->
    <script src="../vendors/pnotify/dist/pnotify.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
	
	<!-- Setting up the page -->
	<script>
	<!-- Select2 -->
      $(document).ready(function() {
        $(".select2_multiple").select2({
          placeholder: "Comprehensive Items",
          allowClear: true
        });
      });
    <!-- /Select2 -->
	FValue = $('#Financing').is(':checked') ? 1 : 0;
		if (FValue == 1){
		}	
		else{
			$('#FinanceInfo').hide();
		}
	CValue = $('#Comprehensive').is(':checked') ? 1 : 0;
		if (CValue == 1){
		}	
		else{
			$('#ComprehensiveItems').hide();
		}
	TValue = $('#TaxExempt').is(':checked') ? 1 : 0;
		if (TValue == 1){
		}	
		else{
			$('#TaxExemptInfo').hide();
		}
	</script>
    <!-- jQuery Smart Wizard -->
    <script>
	var TaskSet = 0;
	var TaskText;
	var MCode;
      $(document).ready(function() {
        $('#wizard').smartWizard({
		  selected: <?php echo $CurrentStep - 1 ?>,
		  onLeaveStep:leaveAStepCallback,
          onFinish:onFinishCallback,
		  labelFinish: 'Submit',
		  hideButtonsOnDisabled: true
		});
		Missing = '';
		MaxStep = <?php echo $CurrentStep ?>;
		
	  function leaveAStepCallback(obj, context){
		  var step_num = obj.attr('rel'); // get the current step number
		  if (context.fromStep > context.toStep) {	//Previous Button?
            Missing = '';
			return true;
        } else {									//Next Button?
			if (MaxStep < context.toStep){			//Are we going to a step higher than we've been on before?
				if (validateSteps(step_num)){		//Is this highest step valid?  If so, return true.  Otherwise, don't do anything
					$("#TText").text(TaskText);
					
						if (TaskSet == 1){
							MaxStep = context.toStep;
							UpdateData(MaxStep, "IsDate");
							TaskSet = 0;
							return true;
						}
						else {
							$('#TaskModal').modal('show');
							return false;
						}
				}
			}
			else {									//This is called when we're clicking the "Next" Button but it's on a lower steps
				return true;
			}
        }
      }
	  var Program = $('#ProgramNum option:selected').val();
	  $('#ProgramNum').click(function() {
		Program = $('#ProgramNum option:selected').val();
	  });
      function onFinishCallback(){
       if(validateAllSteps()){
       }
      }  
	  var isStepValid = true;
	  
	  function validateSteps(stepnumber){
        // validate step 1
        if(stepnumber == 1){
			TaskText = "Follow up with seller regarding project signature.  ";
			MCode = 2;
			RequiredCheck("ProjectName", "Project Name");
			RequiredCheck("Address", "Primary Address");
			RequiredCheck("City", "City");
			RequiredCheck("State", "State");
			RequiredCheck("ZipCode", "Zip Code");
			RequiredCheck("name", "Contact Name");
			RequiredCheck("telephone", "Contact Phone");
			RequiredCheck("Cost", "Project Value");
			RequiredCheck("KWH", "kWh Savings");
			RequiredCheck("Incentive", "Incentive");
			RequiredCheck("ProgramNum", "Program");
			if ((Program !== 9) && (Program !== 10) && (Program !== 11)){
				RequiredCheck("ElectricBillNum", "Electric Bill Account #");
			}
			RequiredCheck("SquareFootage", "Square Footage");
			RequiredCheck("FacilityType", "Facility Type");
			if (FValue == 1){
				RequiredCheck("FinanceType", "Finance Type");
				RequiredCheck("FinanceTerm", "Finance Term");
				RequiredCheck("FinanceAmount", "Finance Amount");
			}
			else {
				RemoveCheck("Finance Type");
				RemoveCheck("Finance Term");
				RemoveCheck("Finance Amount");
			}
			if (CValue == 1){
				RequiredCheck("ComprehensiveList", "Comprehensive Item(s)");
			}
			else {
				RemoveCheck("Comprehensive Item");
			}
			if (TValue == 1){
				RequiredCheck("ExemptionType", "Exemption Type");
			}
			else {
				RemoveCheck("Exemption Type");
			}
			LockButton();
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
		if(stepnumber == 2){
			TaskText = "Prepare project for production.  ";
			MCode = 3;
			RequiredCheck("DateSigned", "Date Signed");
			LockButton();
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
		if(stepnumber == 3){
			TaskText = "Complete project production.  ";
			MCode = 4;
			RequiredCheck("UtilityApprovalDate", "Utility Approval Date");
			LockButton();
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
		if(stepnumber == 4){
			TaskText = "Process finals for installed project.  ";
			MCode = 5;
			RequiredCheck("PMWalkDate", "Date of PM Walk");
			RequiredCheck("MaterialsOrderedDate", "Date Materials Ordered");
			RequiredCheck("InstallDate", "Scheduled Install Date");
			LockButton();
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
		if(stepnumber == 5){
			if ((Program == 1) || (Program == 3) || (Program == 4)|| (Program == 7)){
				RequiredCheck("ProgramID", "Program ID (Found at \"In Development\")");
			}
			TaskText = "Submit invoice for project.  ";
			MCode = 6;
			RequiredCheck("SignedFinalsRecieved", "Signed Finals Recieved");
			LockButton();
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
		if(stepnumber == 6){
          // Your step validation logic
          // set isStepValid = false if has errors
		  return isStepValid;
        }
        // ...      
      }
	  function RequiredCheck(ID, ErrorName){
		  if ((document.getElementById(ID).value == '') || (document.getElementById(ID).value == 0)){
				if (Missing.indexOf(ErrorName) >= 0){
					//Do nothing, it's in there
				}
				else {
					Missing = Missing.concat("- ",ErrorName,"\n");
				}
			}
			else {
				RemoveCheck(ErrorName);
			}
	  }
	  function RemoveCheck(ErrorName){
		Missing = Missing.replace("- "+ErrorName+"\n", "");
	  }
	  function LockButton(){
			if (Missing.length >= 1 ){
				isStepValid = false;
				new PNotify({
					title: 'Missing Information!',
					text: 'The following fields require an input before this project can move to the next status:\n' + Missing,
					styling: 'bootstrap3'
				});
			}
			else {
				isStepValid = true;
			}
	  }  
      function validateAllSteps(){
        var isStepValid = true;
        // all step validation logic     
        return isStepValid;
      } 	

        $('.buttonNext').addClass('btn btn-success');
        $('.buttonPrevious').addClass('btn btn-primary');
        $('.buttonFinish').addClass('btn btn-default');
		
		
      });
    </script>
    <!-- /jQuery Smart Wizard -->
	
	<!-- jquery.inputmask -->
    <script>
      $(document).ready(function() {
        $(":input").inputmask();
      });
    </script>
    <!-- /jquery.inputmask -->
	
	<!-- Ajax Submits -->
	<script>
	$InputSource = $('#ProjectName');
	$('input').change(function() {
		ID = $(this).attr('name');
		Content = $(this).val();
		if(($(this).attr('required')=="required") && Content == ""){
			new PNotify({
					title: 'Data Error!',
					text: 'You cannot delete that field.',
					styling: 'bootstrap3',
					type: 'notice',
					delay: 3000
				});
		}
		else{
			UpdateData(ID, Content);
		}
	});
	$('input[type=checkbox]').change(function() {
		ID = $(this).attr('name');
		Content = $(this).is(':checked') ? 1 : 0;
		UpdateData(ID, Content);
	});
	$('select').change(function() {
		ID = $(this).attr('name');
		Content = $(this).val();
		UpdateData(ID, Content);
    });
		
	function UpdateData(ID, Content){
		ID = ID;
		Content = Content;
		PID = <?php echo $Project_ID ?>;
		$.ajax({
			url: "EditProjectSave.php",
			type: "POST",
			data: {ID: ID, Content: Content, PID: PID},
			success: function(data){
				$InputSource = $('#' + ID);
				$InputSource.removeClass('requiredcolor');
				$InputSource.addClass('greenfade');
				setTimeout(function(){
				$InputSource.removeClass('greenfade');
				}, 200);
			},
			error: function(){
				$InputSource.addClass('redfade');
				setTimeout(function(){
				$InputSource.removeClass('redfade');
				}, 200);
				new PNotify({
					title: 'Data Error!',
					text: 'The project data was not submitted to the database.  \nIf this problem persists please email the site admin at mfox@energyresourcesusa.net',
					styling: 'bootstrap3',
					type: 'error'
				});
			}
		});
	}
	//Custom AJAX for the multiselect
	$('#ComprehensiveList').change(function() {
		
	});
	</script>
	<!-- /Ajax Submits -->
	<!-- Functionality with buttons -->
	<script>
	  $('#Financing').change(function() {
		FValue = $(this).is(':checked') ? 1 : 0;
		if (FValue == 1){
			$('#FinanceInfo').show(300);
		}	
		else{
			$('#FinanceInfo').hide(300);
		}
	  });
	  $('#Comprehensive').change(function() {
		CValue = $(this).is(':checked') ? 1 : 0;
		if (CValue == 1){
			$('#ComprehensiveItems').show(300);
		}	
		else{
			$('#ComprehensiveItems').hide(300);
		}
	  });
	  $('#TaxExempt').change(function() {
		TValue = $(this).is(':checked') ? 1 : 0;
		if (TValue == 1){
			$('#TaxExemptInfo').show(300);
		}	
		else{
			$('#TaxExemptInfo').hide(300);
		}
	  });
	</script>
		<!-- AJAX Submit for Tasks -->
	<script>
	function AddTask(){
		var Optional = $("#CommentInput").val();
		var Message = TaskText.concat(Optional);
		var Project = <? echo $Project_ID ?>;
		var UserID = <? echo $UserID ?>;
		var Recipient = $("#TaskUser").val();
		$.ajax({
			url: "AddTask.php",
			type: "POST",
			data: {Message: Message, Project: Project, UserID: UserID, Recipient: Recipient, MCode: MCode},
			success: function(data){
				TaskSet = 1;
				$(".buttonNext").click();//Clicking it again to fix a minor bug
			},
			error: function(){
			}
		});
	}
	$("#CommentInput").on("change paste keyup", function() {
		$("#TText").text(TaskText + $(this).val());
	});
	$(".requiredcolor").each(function() {
		if ($(this).val().length !== 0) {
			$(this).removeClass("requiredcolor");
		}
	});
	</script>
  </body>
</html>