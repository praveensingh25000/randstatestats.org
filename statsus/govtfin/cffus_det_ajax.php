<?php
/******************************************
* @Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/govtfin/cffus_det.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

global $dbDatabase;


$tablesname				= "cffus";
$tablesnamecats			= "cff_agency";
$tablesnameagencies		= "cff_programs";
$tablesnamecountyarea	= "usareas";

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

//common Sector
if(isset($_POST['states']) && $_POST['states']!='' && isset($_POST['category']) && $_POST['category']!=''){

	/*$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesnamecats , 'catname', 'catcode');
		if(mysql_num_rows($resultSectors)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><div class="table-div"><select name="category[]" class="required" multiple>';
			while($detailRow = mysql_fetch_assoc($resultSectors)){
				$data .= '<option value="'.$detailRow['catcode'].'" >'.$detailRow['catname'].'</option>';
			}
			$data .= '</select></div></div></div>';
		}	 */
	
	$statesArray = explode(';',$_POST['states']);

	$stateDetail = $admin->getRowUniversal('states', 'state', $statesArray[0]);

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').' in '.$stateDetail['statename'].'</span>&nbsp;&nbsp;</p></div>';


	foreach($statesArray as $stateKey => $stateAlpaCode){

		$stateDetail = $admin->getRowUniversal('states', 'state', $stateAlpaCode);

		$searchStr = $stateAlpaCode;
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];	
		
		$lblcities = $stateAlpaCode;
		if(isset($lang['lbl_counties_in'])){ 
			
			$lblcities = $lang['lbl_counties_in']." ".$lblcities;
		} 

		$data .= '<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >
		<option value="'.$stateCode.'">'.$stateDetail['statename'].'</option>
		';

		$file = fopen($DOC_ROOT."counties/".$stateAlpaCode."","r") or exit("Unable to open file!");
		while(!feof($file)){
		  $strarray = explode(' ',preg_replace( '/\s+/', ' ', trim(fgets($file))));

		  if(isset($strarray[1]) && isset($strarray[0])){
			$data .= '<optgroup label="'.$strarray[0].'">';
			$data .= '<option value="'.$strarray[1].'">'.$strarray[0].' County</option>';
			$countyCitiesResult = $admin->searchLikeOneEndUniversal($tablesnamecountyarea , 'areacode', (int)$strarray[1], ' order by areaname ');
			if(mysql_num_rows($countyCitiesResult)>0){
				while($rowDet = mysql_fetch_assoc($countyCitiesResult)){
					if($rowDet['areacode'] != (int)$strarray[1])
					$data .= '<option value="'.$rowDet['areacode'].'">'.$rowDet['areaname'].' County</option>';
				}
			}
			$data .= '</optgroup>';
		  }
		}
			
		$data .= '</select></div>';		
	}
	
	$categoryArray = explode(';', $_POST['category']);
	$agencies = array();
	foreach($categoryArray as $key => $category){
		if($category!=''){
			$sql = "SELECT DISTINCT(cffa.agency_code), cffa.agency_name FROM `cffus` as cf left join cff_agency as cffa on cf.Agency = cffa.agency_code WHERE cf.Area = '".(int)$stateCode."' AND `Category` = '".$category."'";
			$searchedDataResult = $dbDatabase->run_query($sql, $dbDatabase->conn);
			if(mysql_num_rows($searchedDataResult)>0){
				while($agency = mysql_fetch_assoc($searchedDataResult)){
					if($agency['agency_code']!='')
					$agencies[$agency['agency_code']] = $agency['agency_name'];
				}
			}
		}
	}

	if(count($agencies)>0){
		$data .= '<div class="form-div">
		<p><span class="choose">'.((isset($lang['lbl_choose_agencies']))?$lang['lbl_choose_agencies']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="select_all">&nbsp;Select All
		
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#select_all").change(function(){
				if(jQuery(this).is(":checked")) {
					jQuery(".selectall").attr("checked", "checked");
					jQuery(".selectall").attr("disabled", "disabled");
				} else {
					jQuery(".selectall").removeAttr("checked");
					jQuery(".selectall").removeAttr("disabled");
				}
			});
		});
		</script>
		</p>
		<div class="table-div"><table width="100%">';
		asort($agencies);
		$agenciesarray = array_chunk($agencies, 2, true);
		foreach($agenciesarray as $key => $agencies){
			$data .= '<tr>';
			foreach($agencies as $keya => $agency){
				$data .= '<td><input type="checkbox" value="'.$keya.'" class="required selectall" name="agency[]" >&nbsp;'.$agency.'</td>';
			}
			$data .= '</tr>';
		}
		$data .= '</div></div>';
	}
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>