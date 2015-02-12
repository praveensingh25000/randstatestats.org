<?php
/******************************************
* @Modified on Feb 27, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://50.62.142.193
* Dependent on: statsus/community/us_hatecrimes.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "us_hatecrimes";

$data = $errorMSG = '';
$error = 0;

//$datareader = new datareader();
$admin = new admin();

$array= $allSelectedCategory = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['agtype']) && $_REQUEST['agtype']!='') {
	
	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_please_select_bias_category']))?$lang['lbl_please_select_bias_category']:'').' </span>&nbsp;&nbsp;<input type="checkbox" id="checkall">&nbsp;All
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkall").click(function(){							
							var checkedd = $("#checkall").is(":checked");
							if(checkedd){
								jQuery(".allcheck option").attr("selected","selected");
							} else {
								jQuery(".allcheck option").removeAttr("selected");
							}
						});
					});
					</script>
				</p>';
		
			$data .='<div class="table-div"><select size="5" name="cat1[]" class="allcheck required" multiple >';
			foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'</option>';	
			}
			$data .= '</select></div></div>';

	}

	$agtype = $_REQUEST['agtype'];
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	foreach($statesArray as $stateKey => $stateAlpaCode){
		
		$searchStr = $stateAlpaCode;
		$columnname = "Area";;
		
		$dataSqlAllResult = $admin->searchDistinctUniversalColoumLikeArray($tablesname ,$displaycolumnnamestr='Category', $columnname, $stateAlpaCode, $orderby = '');
		
		if(mysql_num_rows($dataSqlAllResult) > 0){	
			
			while($categoryDetail = mysql_fetch_assoc($dataSqlAllResult)) {

				if(isset($agtype) && $agtype == 'all') {
					$allSelectedCategory['all'][$categoryDetail['Category']]=$categoryDetail['Category'];
				} else {
					$agtypeStr	 = stristr($categoryDetail['Category'], $agtype);
					if($agtypeStr != ''){
						$allSelectedCategory[$agtype][$categoryDetail['Category']]=$categoryDetail['Category'];
					}
				}
			}
		if(!empty($allSelectedCategory)){
			$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_agencies_cities_counties']))?$lang['lbl_select_agencies_cities_counties']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallCategory">&nbsp;All
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallCategory").click(function(){							
							var checkedd = $("#checkallCategory").is(":checked");
							if(checkedd){
								jQuery(".allCategory option").attr("selected","selected");
							} else {
								jQuery(".allCategory option").removeAttr("selected");
							}
						});
					});
					</script>
				</p></div>';
			
			$data .= '<select name="Category[]" class="allCategory required" multiple >';

			//if(!empty($allSelectedCategory)){
				foreach($allSelectedCategory[$agtype] as $areaDetail){
					$data .= '<option value="'.$areaDetail.'">'.$areaDetail.'</option>';
				}
			
				$data .= '</select></div></div>';
			}else {
				$error = 1;
			}
		} else {
			$error = 1;
		}
	}

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>