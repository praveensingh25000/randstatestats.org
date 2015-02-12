<?php
/******************************************
* @Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/farmincomeUS.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

global $dbDatabase;

$tablesname				= "persincUS";
$tablesnamecat			= "persincUS_cats";
$tablesnamearea			= "states";
$tablesnamecountyarea	= "fips";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$popcontentCat = $data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//common Sector
if(isset($_POST['us_states']) && $_POST['us_states']!=''){

	$resultSectors = $admin->getTableDataUniversal($tablesnamecat , ' order by catname ');
		if(mysql_num_rows($resultSectors)>0){

		$data .= '<div class="form-div">
			<p>
				<span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;
				<a id="viewallCategory" href="javascript:;">See a list</a>
				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("#viewallCategory").click(function(){
						window.open("'.$URL_SITE.'/allValues.php?table='.$tablesnamecat.'&column=catname", "popUpWindow","height=400, width=400, left=300, top=100, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes");
					});
				});
				</script>
			</p>';

		$data .='<div class="table-div"><div class="table-div"><select name="category[]" class="required" multiple>';
			while($detailRow = mysql_fetch_assoc($resultSectors)){
				$data .= '<option value="'.$detailRow['catcode'].'" >'.$detailRow['catname'].'</option>';
			}
			$data .= '</select></div></div></div>';
		}
	
	$statesArray = explode(';',$_POST['us_states']);

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').'</span>&nbsp;&nbsp;</p></div>';


	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];	
		
		$lblcities = $stateAlpaCode;
		if(isset($lang['lbl_counties_in'])){ 
			
			$lblcities = $lang['lbl_counties_in']." ".$lblcities;
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'.</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		if($stateAlpaCode != 'US'){
			$data .='<option value="'.$stateCode.'000"> State totals</option>';
		}

		$file = fopen($DOC_ROOT."counties/".$stateAlpaCode."","r") or exit("Unable to open file!");
		while(!feof($file)){
		  $strarray    = explode(' ',preg_replace( '/\s+/', ' ', trim(fgets($file))));
		  $countycode  = end($strarray);
		  $countyArray = array_pop($strarray);
		  $countyname  = implode(" ",$strarray);		  
     	  $data .= '<option value="'.$countycode.'">'.$countyname.'</option>';		 
		  
		}
		
		$data .= '</select></div></div>';		
	}
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>