<?php
//	These queries will need to be consolidated for performance sake.  They are all being put here separately to ensure that they work as we want them to.  
//	I'm keeping them separate because I anticipate changes being made to the metrics and having them separate will make it easier to change later.  
//**Top Modules**//
//Gross Revenue
$Q_Get_Projects = "SELECT SUM(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND DeadBool = 0
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$GrossRevenueYTD = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$GrossRevenue = number_format(round($GrossRevenueYTD[0]));

//Earned Revenue
$Q_Get_Projects = "SELECT SUM(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MovedToInstalled IS NOT NULL
	AND DeadBool = 0
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$EarnedRevenueYTD = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$EarnedRevenue = number_format(round($EarnedRevenueYTD[0]));

//Total Projects Signed
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND DeadBool = 0
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$ProjectsSignedYTD = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$TotalProjectsSigned = $ProjectsSignedYTD[0];

//Largest Signed Deal
$Q_Get_Largest_Deal = "SELECT MAX(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND DeadBool = 0
	AND Deleted = 0";
$R_Get_Largest_Deal = $mysqli->query($Q_Get_Largest_Deal);
$LargestDeal = $R_Get_Largest_Deal->fetch_array(MYSQLI_NUM);
$LargestSignedDeal = number_format(round($LargestDeal[0]));

//Projects Created
$Q_Get_Projects = "SELECT COUNT(*), MONTHNAME(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	FROM project
	WHERE YEAR(CreateDate) = YEAR(NOW())
	AND MONTH(CreateDate) = MONTH(NOW())
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$ProjectsCreatedThisMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$ProjectsCreated = $ProjectsCreatedThisMonth[0];
$LastMonthName = $ProjectsCreatedThisMonth[1];

//Projects Created Change
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(CreateDate) = YEAR(NOW())
	AND MONTH(CreateDate) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$ProjectsCreatedLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$ProjectsCreatedChange = $ProjectsCreatedLastMonth[0];

//Projects Signed
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(NOW())
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$ProjectsSignedThisMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$ProjectsSigned = $ProjectsSignedThisMonth[0];

//Projects Signed Change
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$ProjectsSignedLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$ProjectsSignedChange = $ProjectsSignedLastMonth[0];

//Total In Sales
$Q_Get_Projects = "SELECT SUM(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(NOW())
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$TotalInSalesThisMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
if ($TotalInSalesThisMonth[0] == ''){
	$TotalInSales = 0;
}
else {
	$TotalInSales = number_format(round($TotalInSalesThisMonth[0]));
}

//Total In Sales Change
$Q_Get_Projects = "SELECT SUM(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$TotalInSalesLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$TotalInSalesChange = number_format(round($TotalInSalesLastMonth[0]));

//Average Project Cost
$Q_Get_Projects = "SELECT AVG(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(NOW())
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$AverageCostLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
if ($AverageCostLastMonth[0] == ''){
	$AverageProjectCost = 0;
}
else {
	$AverageProjectCost = number_format(round($AverageCostLastMonth[0]));
}

//Average Project Cost Change
$Q_Get_Projects = "SELECT AVG(Project_Cost)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$AverageCostThisMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$AverageProjectCostChange = number_format(round($AverageCostThisMonth[0]));

//In State Projects
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(NOW())
	AND state = 'CT'
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$InStateThisMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$InStateProjects = $InStateThisMonth[0];

//In State Projects Change
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND state = 'CT'
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$InStateLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$InStateProjectsChange = $InStateLastMonth[0];

//Out Of State Projects
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(NOW())
	AND state != 'CT'
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$OutOfStateLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$OutOfStateProjects = $OutOfStateLastMonth[0];

//Out Of State Projects Change
$Q_Get_Projects = "SELECT COUNT(*)
	FROM project
	WHERE YEAR(DateSigned) = YEAR(NOW())
	AND MONTH(DateSigned) = MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH))
	AND state != 'CT'
	AND Deleted = 0";
$R_Get_Projects = $mysqli->query($Q_Get_Projects);
$OutOfStateLastMonth = $R_Get_Projects->fetch_array(MYSQLI_NUM);
$OutOfStateProjectsChange = $OutOfStateLastMonth[0];

//**Programs Performance**//

//Can turn this into a function and iterate through a while loop on index.  For now, I'm keeping it as 7 separate queries
$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program = '1'
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$Program1 = $R_Get_Program->fetch_array(MYSQLI_NUM);

$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program = '2'
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$Program2 = $R_Get_Program->fetch_array(MYSQLI_NUM);

$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program = '3'
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$Program3 = $R_Get_Program->fetch_array(MYSQLI_NUM);

$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program = '7'
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$Program7 = $R_Get_Program->fetch_array(MYSQLI_NUM);

$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program = '9'
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$Program9 = $R_Get_Program->fetch_array(MYSQLI_NUM);

$Q_Get_Program = "SELECT SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now()),Project_Cost,0)) AS A,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-1,Project_Cost,0)) AS B,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-2,Project_Cost,0)) AS C,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-3,Project_Cost,0)) AS D,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-4,Project_Cost,0)) AS E,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-5,Project_Cost,0)) AS F,
	SUM(IF(YEAR(DateSigned)*12+MONTH(DateSigned) = YEAR(Now())*12+MONTH(Now())-6,Project_Cost,0)) AS G
	FROM project
	WHERE program NOT IN (1,2,3,7,9)
	AND Deleted = 0";
$R_Get_Program = $mysqli->query($Q_Get_Program);
$ProgramOther = $R_Get_Program->fetch_array(MYSQLI_NUM);
	
//**Top Salespeople**//
$Q_Top_Salespeople = "SELECT SUM(p.project_cost) as cost, p.salesperson_id, CONCAT(c.first_name, ' ', c.last_name) AS 'full_name'
	FROM project p
	JOIN contact c ON c.contact_id = p.salesperson_id
	WHERE DateSigned BETWEEN DATE(DATE_ADD(NOW(), INTERVAL -1 MONTH)) AND NOW()
	AND Deleted = 0
	GROUP BY salesperson_id
	ORDER BY cost DESC
	LIMIT 5";
$R_Top_Salespeople = $mysqli->query($Q_Top_Salespeople);

//**Sales Breakdown**//

?>