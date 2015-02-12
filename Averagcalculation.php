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

$tableArray=array('foreclose');

//echo "<pre>";print_r($tableArray);echo "</pre>"; die;


foreach($tableArray as $key => $table){

	//$sql   = "SELECT * FROM `".$table."` LIMIT 0,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 10000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 20000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 30000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 40000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 50000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 60000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 70000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 80000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 90000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 100000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 110000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 120000,10000";
	//$sql   = "SELECT * FROM `".$table."` LIMIT 130000,10000";
	$sql   = "SELECT * FROM `".$table."` LIMIT 140000,10000";

	$result = mysql_query($sql);
	while($row = mysql_fetch_assoc($result)){		
		set_time_limit(0);
		foreach($row as $column => $val){		
			$column		= trim($column);
			$monthCol=substr($column,0,1);
			if($monthCol == 'M' && $val!='' && $val!='NA') {
				$actualData[$row['id']][] = str_replace(',', '', $val);				
			}			
		}		
	}
}

echo "<pre>";print_r($actualData);echo "</pre>";

foreach($actualData as $key => $dataAll){

	if(!empty($dataAll)) {
		//$avg = number_format(array_sum($dataAll)/count($dataAll),5);
		//echo $sql = "update foreclose set Avg = '".mysql_real_escape_string($avg)."' where id='".$key."' ";
		//mysql_query($sql) or die(mysql_error());	
		//echo '<br>';
	}
		
}

include_once $basedir."/include/footerHtml.php";
?>