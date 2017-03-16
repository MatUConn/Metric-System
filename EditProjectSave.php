<?php
//Am I proud of this mess of switch statements?  No.  No I'm not...I'll switch to something OO later on.  No pun intended.  
// Config File Include
require ('priv_includes/config.inc.php');
// App Core Include
require ('priv_includes/core.inc.php');

$ID = $mysqli->real_escape_string(trim($_POST['ID']));
$Content = $mysqli->real_escape_string(trim($_POST['Content']));
$PID = $mysqli->real_escape_string(trim($_POST['PID']));
$UserID = $sow_user->getUserID();
$Audit;
$Query;
$CurrentDate = date('Y-m-d H:i:s');
switch ($ID) {
    case ProjectName:
        $ColumnName = "project_name";
		$Query = "PA";
        break;
	case ProjectStatus:
        $ColumnName = "project_status";
		$Query = "PA";
        break;
	case BusinessName:
        $ColumnName = "business_name";
		$Query = "PA";
        break;
	case Address:
        $ColumnName = "address1";
		$Query = "PA";
        break;
	case Address2:
        $ColumnName = "address2";
		$Query = "PA";
        break;
	case City:
        $ColumnName = "city";
		$Query = "PA";
        break;
	case State:
		$Content = strtoupper($Content);
        $ColumnName = "state";
		$Query = "PA";
        break;
	case ZipCode:
        $ColumnName = "zip";
		$Query = "PA";
        break;
	case name:
        $ColumnName = "contact_name";
		$Query = "PA";
        break;
	case phone:
		$remove = array("(", ")", " ", "-");
		$Content = str_replace($remove, "", $Content);
        $ColumnName = "contact_phone";
		$Query = "PA";
        break;
	case email:
        $ColumnName = "contact_email";
		$Query = "PA";
        break;
	case Salesperson:
        $ColumnName = "salesperson_id";
		$Query = "PA";
        break;
	case Cost:
        $ColumnName = "Project_Cost";
		$Query = "PA";
        break;
	case KWH:
        $ColumnName = "KWH_Savings";
		$Query = "PA";
        break;
	case Incentive:
        $ColumnName = "Incentive";
		$Query = "PA";
        break;
	case ProgramNum:
        $ColumnName = "program";
		$Query = "PA";
        break;
	case ProgramID:
        $ColumnName = "program_id";
		$Query = "PA";
        break;
	case ElectricBillNum:
        $ColumnName = "ElectricBillAcct";
		$Query = "PA";
        break;
	case UtilitiesApprovalRequest:
        $ColumnName = "UtilitiesApprovalRequest";
		$Query = "PA";
        break;
	case SquareFootage:
        $ColumnName = "area_sqft";
		$Query = "AA";
        break;
	case HeatingFuelType:
        $ColumnName = "HeatingFuelType";
		$Query = "PA";
        break;
	case FacilityType:
        $ColumnName = "FacilityType";
		$Query = "PA";
        break;
	case Financing:
        $ColumnName = "Financing";
		$Query = "PN";
        break;
	case DeadBool:
        $ColumnName = "DeadBool";
		$Query = "PN";
        break;
	case Comprehensive:
        $ColumnName = "Comprehensive";
		$Query = "PN";
        break;
	case ComprehensiveListAdd:
		$Query = "CA";
        break;
	case ComprehensiveListDelete:
		$Query = "CD";
        break;
	case TaxExempt:
        $ColumnName = "Tax_Exempt";
		$Query = "PN";
        break;
	case ExemptionType:
        $ColumnName = "ExemptionType";
		$Query = "PA";
        break;
	case FinanceType:
        $ColumnName = "FinanceType";
		$Query = "PA";
        break;
	case FinanceAmount:
        $ColumnName = "FinanceAmount";
		$Query = "PA";
        break;
	case FinanceTerm:
        $ColumnName = "FinanceTerm";
		$Query = "PA";
        break;
	case DateSigned:
        $ColumnName = "DateSigned";
		$Query = "PA";
        break;
	case UtilityApprovalDate:
        $ColumnName = "UtilityApprovalDate";
		$Query = "PA";
        break;
	case PMWalkDate:
        $ColumnName = "PMWalkDate";
		$Query = "PA";
        break;
	case MaterialsOrderedDate:
        $ColumnName = "MaterialsOrderedDate";
		$Query = "PA";
        break;
	case InstallDate:
        $ColumnName = "InstallDate";
		$Query = "PA";
        break;
	case PMChanges:
        $ColumnName = "PMChanges";
		$Query = "PN";
        break;
	case ProjectManager:
        $ColumnName = "pm_id";
		$Query = "PA";
        break;
	case SignedFinalsRecieved:
        $ColumnName = "SignedFinalsRecieved";
		$Query = "PA";
        break;
	case ValueChanged:
        $ColumnName = "ValueChanged";
		$Query = "PN";
        break;
	case PaidDate:
        $ColumnName = "PaidDate";
		$Query = "PA";
        break;
	case 2:
        $DateColumn = "MovedToWSP";
		$PStatus = "With Seller/Proposed";
		$Query = "PD";
        break;
	case 3:
        $DateColumn = "MovedToSNTP";
		$PStatus = "Signed; Not To Production";
		$Query = "PD";
        break;
	case 4:
        $DateColumn = "MovedToInProduction";
		$PStatus = "In Production";
		$Query = "PD";
        break;
	case 5:
        $DateColumn = "MovedToInstalled";
		$PStatus = "Installed";
		$Query = "PD";
        break;
	case 6:
        $DateColumn = "MovedToInvoiced";
		$PStatus = "Invoiced";
		$Query = "PD";
        break;
}
switch ($Query) {
    case PA:
		$Q_Update_Info = "UPDATE project
				SET $ColumnName = '$Content', LastChanged = '$CurrentDate'
				WHERE project_ID = $PID
				LIMIT 1";
        break;
	case PN:
		$Q_Update_Info = "UPDATE project
				SET $ColumnName = $Content, LastChanged = '$CurrentDate'
				WHERE project_ID = $PID
				LIMIT 1";
        break;
	case AA:
		$Q_Update_Info = "UPDATE audit
				SET $ColumnName = '$Content'
				WHERE project_id = $PID
				LIMIT 1";
        break;
	case PD:
		$Q_Update_Info = "UPDATE project
				SET $DateColumn = '$CurrentDate', project_status = '$PStatus', LastChanged = '$CurrentDate'
				WHERE project_ID = $PID
				LIMIT 1";
        break;
	case CA:
		$Q_Update_Info = "INSERT INTO ComprehensiveItems (Project_ID, Item_ID) 
						VALUES('$PID', '$Content')";
        break;
	case CD:
		$Q_Update_Info = "DELETE FROM ComprehensiveItems
						WHERE Project_ID = '$PID'
						AND Item_ID = '$Content'";
        break;
}
$mysqli->query($Q_Update_Info);
					
$Q_Update_Members = "INSERT INTO ProjectMembers (Project_ID, User_ID)
					VALUES($PID, $UserID)";
$mysqli->query($Q_Update_Members);
