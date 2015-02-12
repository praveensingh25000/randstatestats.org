<?php
/******************************************
* @Modified on Feb 28, 2013, Mar 12,2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* Dependent on: statsus/energyenv/airquality_us.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " airqual_us";
$tablesnamearea = "airqual_us_areas";

//$datareader = new datareader();
$admin = new admin();

$dataSqlCatArray = $dataSqlAll = $datasqlallareaArray = $array= array();

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

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category');
	if(mysql_num_rows($dataSqlCat)>0){
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			$dataSqlCatArray[$catDetail['Category']] = array('id' => $catDetail['Category'],'name' => $catDetail['Category']);
		}
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	
	foreach($statesArray as $stateKey => $stateAlpaCodeone){
		
		$searchStr = $stateAlpaCodeone;
		$column = "areast";		
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCodeone, 'order by areaname');
		
		if(mysql_num_rows($dataSqlAllResult) > 0){
			
			$found = 1;
			$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);
			
			foreach($dataSqlAll as $keyArea => $areaDetail){
				$datasqlallareaArray[$stateAlpaCodeone][$areaDetail['areacity']] = array('id' => $areaDetail['areacode'],'name' => $areaDetail['areacity']);
			}
	   }
	}
}

if(!empty($dataSqlCatArray) && !empty($datasqlallareaArray)) {
	
	if(!empty($dataSqlCatArray)) {

		$data .= '<div class="form-div">
			  <p>
				<span class="choose">'.((isset($lang['lbl_select_catregories_air_quality']))?$lang['lbl_select_catregories_air_quality']:'').' &nbsp;&nbsp;<input type="checkbox" id="allcategories" />&nbsp;All</span>&nbsp;&nbsp;
			  
				 <script type="text/javascript">
					$(document).ready(function() {	
						$("#allcategories").click(function(){
							if($(this).is(":checked")){
								$("#categoriesselect option").attr("selected", "selected");
							} else {
								$("#categoriesselect option").removeAttr("selected");
							}
						});
						jQuery("#categoriesselect").click(function(){
							jQuery("#allcategories").removeAttr("checked");
						});
					});
				</script>
			</p>';

		$data .= '<div class="table-div"><select name="category[]" id="categoriesselect" class="required" MULTIPLE>';
		
		foreach($dataSqlCatArray as $catDetail) {
			$data .= '<option value="'.$catDetail['id'].'">'.$catDetail['name'].'</option>';	
		}

		$data .= '</select></div></div>';
	}

	if(!empty($datasqlallareaArray)) {

		foreach($datasqlallareaArray as $stateAlpaCode => $areasAll){

			$data .= '<div class="form-div">
					  <p><span class="choose">'.$stateAlpaCode.':&nbsp;&nbsp;<input type="checkbox" id="checkallareacode_'.$stateAlpaCode.'">&nbsp;All</span>					
					  
					  <script type="text/javascript">
					  jQuery(document).ready(function(){
						jQuery("#checkallareacode_'.$stateAlpaCode.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".areacodeAll_'.$stateAlpaCode.' option").attr("selected", "selected");
							}else{
								jQuery(".areacodeAll_'.$stateAlpaCode.' option").removeAttr("selected");
							}
						});
						jQuery(".areacodeAll_'.$stateAlpaCode.'").click(function(){
							jQuery("#checkallareacode_'.$stateAlpaCode.'").removeAttr("checked");
						});
					});
					</script>
				</p>';

			$data .= '<div class="table-div"><select name="areacode[]" class="areacodeAll_'.$stateAlpaCode.' required" multiple >';
			
			foreach($areasAll as $keyAreas => $areas){
				$data .= '<option value="'.$areas['id'].'">'.ucfirst($areas['name']).'</option>';	
			}
			
			$data .= '</select></div></div></div>';
		}

	}
} else {

	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>