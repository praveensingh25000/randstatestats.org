<?php
/******************************************
* @Modified on Jan 10, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$admin = new admin();

$array= array();

if(isset($_REQUEST['table']) && $_REQUEST['table']!='' && isset($_REQUEST['column']) && $_REQUEST['column']!='' && isset($_REQUEST['q']) && $_REQUEST['q']!=''){
	
	$table		= $_REQUEST['table'];
	$column		= $_REQUEST['column'];
	$searchStr	= $_REQUEST['q'];
	
	$searchedData = $admin->searchDistinctLikeUniversal($table , $column, $searchStr, ' order by '.$column);
	
	if(mysql_num_rows($searchedData)>0){

		$arrayAll = array();

		while($dataSearched = mysql_fetch_assoc($searchedData)){			
			if(!array_key_exists($dataSearched[$column], $arrayAll)){
				$arrayAll[$dataSearched[$column]] = stripslashes($dataSearched[$column]);
			}
		}

		foreach($arrayAll as $key => $value){

			$columarray=explode(' ',$value);		 

			if($table == "gdp_by_state_1997_2012" || $table == 'federal_food_programs' || $table == 'federal_food_program_participation_benefits' || $table == 'foreclose' || $table == 'foreclose1' || $table == 'houseprice' || $table == 'houseprice1' || $table == 'greenhouse_gas_ghg_emissionsfrom_co2_by_state' || $table == 'imm_cbsa_country' || $table == 'legal_resident_status_by_country_of_birth_and_msa' || $table == 'social_security_maximum_taxable_earnings'  || $table == 'bridge_inventory_total_deficient_obsolete' || $table == 'use_of_mammography' || $table == 'refugees_by_country_of_nationality' || $table == 'child_vaccinations_by_state_and_local_area'|| $table == 'healthrisk_cats'){			

				$array[] = array('id' => $value, 'name' => $value);

			} else if($table == 'stateexpend' || $table == 'legal_resident_status_by_gender_age_marital_occ'){

				$array[] = array('id' => $value, 'name' => ucwords(strtolower($value)));

			} else if($table == 'percentage_of_persons_who_are_physically_active'){

				$array[] = array('id' => $value, 'name' => ucwords(strtolower($value)));

			} else if(!empty($columarray) && count($columarray) > 1 && strlen($columarray[0])=='2') {

				$name=trim(strtoupper($columarray[0]).' '.ucwords(strtolower($columarray[1])));

				$array[] = array('id' => $value, 'name' => $name);

			} else if(!empty($columarray) && (in_array('PD',$columarray) || in_array('pd',$columarray))) {

				$array[] = array('id' => $value, 'name' => $value);

			} else if(!empty($columarray) && (in_array('AIDS',$columarray) || in_array('HIV',$columarray))){

				$array[] = array('id' => $value, 'name' => $value);

			} else if($table =='states'){

				if($value!='Puerto Rico' && $value!='Virgin  islands'){
					$array[] = array('id' => $value, 'name' => ucwords(strtolower($value)));
				}				

			} else {
				$array[] = array('id' => $value, 'name' => ucwords(strtolower($value)));
			}
		}
	}	
}
echo json_encode($array);
?>