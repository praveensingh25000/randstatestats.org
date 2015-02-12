<?php
/******************************************
* @Modified on August 14, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$actualData = array();

$tableArray=array();

//echo "<pre>";print_r($tableArray);echo "</pre>"; die;

//$sql   = "SELECT * FROM `houseprice_old` LIMIT 0,103467";
//$sql   = "SELECT * FROM `houseprice_old` LIMIT 103467,103467";
//$sql   = "SELECT * FROM `houseprice_old` LIMIT 206934,103467";
//$sql   = "SELECT * FROM `houseprice_old` LIMIT 310401,103467";
//$sql   = "SELECT * FROM `houseprice_old` LIMIT 413868,103467";
$sql   = "SELECT * FROM `houseprice_old` LIMIT 517335,103467";

$result = mysql_query($sql);
while($row = mysql_fetch_assoc($result)){

	set_time_limit(0);

	if($row['Area']!='' &&  $row['Area']!=null){

		$row['Area_Num']=$row['Area'];

		if(is_numeric($row['Area']) && strlen($row['Area']) != '5') {

			echo $sql_tl="SELECT areaname FROM `ca_counties_msa` WHERE areacode = ".trim($row['Area'])." ";
			echo '<br>';
			$result_t1 = mysql_query($sql_tl);
			$rowMiles  = mysql_fetch_assoc($result_t1);
			if(!empty($rowMiles)){
				$Area      = trim($rowMiles['areaname']);
			} else {
				echo $sql_tl="SELECT areaname FROM `fips` WHERE areacode = ".trim($row['Area'])." ";
				echo '<br>';
				$result_t1 = mysql_query($sql_tl);
				$rowMiles  = mysql_fetch_assoc($result_t1);
				if(!empty($rowMiles)){
					$Area      = trim($rowMiles['areaname']);
				}
			}			
		} else {
			$Area	   = trim($row['Area']);
		}	
	
		$row['Area_new']=$Area;
		
		//echo "<pre>";print_r($row);echo "</pre>";
		//echo "<pre>";print_r($row['Area_new']);echo "</pre>";

		echo '<h2>'.$row['id'].'</h2>';
		echo $sql = "insert into `houseprice` set Area = '".mysql_real_escape_string($row['Area_new'])."' , Category = '".mysql_real_escape_string($row['Category'])."', Units = '".mysql_real_escape_string($row['Units'])."',Yr = '".mysql_real_escape_string($row['Yr'])."',M01 = '".mysql_real_escape_string($row['M01'])."',M02 = '".mysql_real_escape_string($row['M02'])."',M03 = '".mysql_real_escape_string($row['M03'])."',M04 = '".mysql_real_escape_string($row['M04'])."',M05 = '".mysql_real_escape_string($row['M05'])."',M06 = '".mysql_real_escape_string($row['M06'])."',M07 = '".mysql_real_escape_string($row['M07'])."',M08 = '".mysql_real_escape_string($row['M08'])."',M09 = '".mysql_real_escape_string($row['M09'])."',M10 = '".mysql_real_escape_string($row['M10'])."',M11 = '".mysql_real_escape_string($row['M11'])."',M12 = '".mysql_real_escape_string($row['M12'])."',Total = '".($row['M01']+$row['M02']+$row['M03']+$row['M04']+$row['M05']+$row['M06']+$row['M07']+$row['M08']+$row['M09']+$row['M10']+$row['M11']+$row['M12'])."' ";
		echo "<br/>";echo "<br/>";
		mysql_query($sql) or die(mysql_error());	
	}
	
}
?>