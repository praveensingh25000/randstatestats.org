<?php
/******************************************
* @Modified on March 3, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* Dependent on: statsus/economics/usconstCO.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " usconst";
$tablesnamearea = "usconst_areas";
$tablesnamecat = "usconst_cats";

//$datareader = new datareader();
$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;


$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}


if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_or_more_cat']))?$lang['lbl_select_one_or_more_cat']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><table width="100%">';
		$catarray = array_chunk($dataCat,3);			
		
		foreach($catarray as $CatKey => $cats){
			$data .= '<tr>';
			foreach($cats as $CatKey => $catDetail){
				$data .= '<td><input type="checkbox" name="catcode[]" class="required" value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</td>';	
			}
			$data .= '</tr>';
		}
		$data .= '</table></div></div>';

	}
	

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];	
		
		$lblcities = $stateAlpaCode;
		if(isset($lang['lbl_cities_in'])){ 
			
			$lblcities = $lang['lbl_cities_in'];
			$lblcities = str_replace("#CINCODE#", $stateAlpaCode, $lblcities); 
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		$file = fopen($DOC_ROOT."counties/".$stateAlpaCode."","r") or exit("Unable to open file!");
		while(!feof($file)){
		  $strarray = explode(' ',preg_replace( '/\s+/', ' ', trim(fgets($file))));
		  if(isset($strarray[1]) && isset($strarray[0]))
		  $data .= '<option value="'.$strarray[1].'">'.$strarray[0].' County</option>';
		}
			
		$data .= '</select></div></div>';

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lang['lbl_msa_in'].' In '.$stateAlpaCode.'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		
		require_once($DOC_ROOT.'/include/msa.php');
		
		if(isset($StateMSAs) && $StateMSAs[$stateCode])	{
			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($StateMSAs[$stateCode] as $value => $code) {
			  $data .= '<option value="'.$code.'">'.$value.'</option>';
			}
				
			$data .= '</select></div></div>';
		}
	}

	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>