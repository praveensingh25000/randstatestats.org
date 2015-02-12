<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";


$user = new user();

$sql = "select * from largestcounties2012bypop";
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_assoc($result)){

	$county = trim($row['County']);
	$state = trim($row['STATE']);
	$county_name = $row['CTYNAME'];
	$population = $row['TOT_POP']; 
	
	if(strlen($county) == 1){
		$county = "00".$county;
	} else if(strlen($county) == 2){
		$county = "0".$county;
	} 

	$areacode = $state."".$county;
	
	echo $sqlUpdate = "update uspopraceage1_census set Y2012 = '".$population."' where Area = '".$areacode."' and Category = '10' and Cat1 = 'All ages'";
	$resultUpdate = mysql_query($sqlUpdate) or die(mysql_error());
	echo "<br/>";
}

