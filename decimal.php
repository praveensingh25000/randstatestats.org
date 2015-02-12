<?php
/******************************************
* @Modified on April 1, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* This file will check the decimal settings set for forms on the backend.
********************************************/

$arrayfieldstr ='';

$decimal_settings = (isset($databaseDetail['decimal_settings']))?stripslashes($databaseDetail['decimal_settings']):'';

if($decimal_settings != ''){
	if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) {

		if(isset($dbname) && $dbname == 'Federal Budget-Receipts and Outlays'){

			echo str_replace('"','',number_format ($fieldValue));

		} else {

			echo 'NA'; 

		}

	} else if(is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField)) != 'zip' && trim(strtolower($keyField))!= 'age' && trim(strtolower($keyField))!= 'zip code' && trim(strtolower($keyField))!= 'establishment age'){

		if(isset($tablesname) && $tablesname == 'total_persons_naturalized'){

			echo str_replace('"','',number_format ($fieldValue, $decimal_settings));

		} else {
			
			$arrayfield = explode('.',$fieldValue);

			if(isset($arrayfield[1])){
				if($decimal_settings == 0){
					echo str_replace('"','',number_format(round($fieldValue)));
				} else {
					echo str_replace('"','',number_format ($fieldValue, $decimal_settings));
				}
			} else {
				if(is_numeric($fieldValue) && trim(strtolower($keyField)) == 'area') {
					echo str_replace('"','',$fieldValue);
				} else {
					echo str_replace('"','',number_format($fieldValue));
				}			
			}
		}

	} else {
		if (strpos($fieldValue, 'city') !== false && isset($tablesname) && $tablesname == 'uspopest') {
			echo $newstr = str_replace($search='(city)', $replace='', $str=$fieldValue);
		} else if (!is_numeric($fieldValue) && $fieldValue != 'NA' && isset($tablesname) && ($tablesname == 'graduates' || $tablesname == 'enrollrace' || $tablesname == 'leplang' || $tablesname == 'leppgm' || $tablesname == 'econdis' || $tablesname == 'student'  || $tablesname == 'staffsalary2' || $tablesname == 'superpay' || $tablesname == 'performancedis' || $tablesname == 'districtexpend' || $tablesname == 'stateexpend')) {			
			
			$arrayfield = explode(' ',trim($fieldValue));
			
			if(isset($arrayfield[1]) && count($arrayfield) > 1 && trim(strtolower($keyField)) != 'category' && trim(strtolower($keyField)) != 'program' && trim(strtolower($keyField)) != 'language' && trim(strtolower($keyField)) != 'grade' && trim(strtolower($keyField)) != 'cat1' && trim(strtolower($keyField)) != 'cat2') {
				$lastelsement	= array_pop($arrayfield);
				$arrayfieldstr	= implode(' ',$arrayfield);	
				if(strtolower($lastelsement) == 'isd'){
					echo str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.strtoupper(strtolower($lastelsement)));
				} else {
					echo str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.ucfirst(strtolower($lastelsement)));
				}
				
			} else {
				echo str_replace('"','',ucwords(strtolower($fieldValue)));
			}			
				
		} else {
			echo str_replace('"','',$fieldValue);
		}
	} 
} else {

	if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 
		echo 'NA'; 
	}
	else if (is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age'){ 	
		echo str_replace('"','',$fieldValue); 
	} else {
		echo str_replace('"','',$fieldValue); 
	}
}
?>