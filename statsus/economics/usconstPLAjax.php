<?php
/******************************************
* @Modified on March 3, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* Dependent on: statsus/economics/usconstPL.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname     = " usconst";
$tablesnamearea = "usconst_areas";
$tablesnamecat  = "usconst_cats";

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


if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && !isset($_REQUEST['county'])) {

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').'</span>&nbsp;&nbsp;</p></div>';

	if(isset($_SESSION['countiesArray'])) {	
		unset($_SESSION['countiesArray']);
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];	
		
		$lblcities = $stateAlpaCode;
		if(isset($lang['lbl_cities_in'])){ 
			
			$lblcities = $lang['lbl_counties_in']. " ".$stateAlpaCode;
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="area[]" id="area" class="required" multiple >';

		$file = fopen($DOC_ROOT."counties/".$stateAlpaCode."","r") or exit("Unable to open file!");

		$countiesArray = array();
		while(!feof($file)){

		   $strarray = explode(' ',preg_replace( '/\s+/', ' ', trim(fgets($file))));

		   if(!empty($strarray) & isset($strarray[0]) && $strarray[0]!='') {

			   if(count($strarray)== '4') {				   
				    $data .= '<option value="'.$strarray[3].'">'.$strarray[0].' '.$strarray[1].' '.$strarray[2].' County</option>';
					$_SESSION['countiesArray'][$strarray[3]]  = $strarray[0].' '.$strarray[1].' '.$strarray[2];					
			   } else if(count($strarray)== '3') {
					$data .= '<option value="'.$strarray[2].'">'.$strarray[0].' '.$strarray[1].' County</option>';
					$_SESSION['countiesArray'][$strarray[2]]  = $strarray[0].' '.$strarray[1];				
			   } else {
				   $data .= '<option value="'.$strarray[1].'">'.$strarray[0].' County</option>';
				   $_SESSION['countiesArray'][$strarray[1]]  = $strarray[0];
			   }
			}
		}
			
		$data .= '</select></div></div>';		

		$data .= '<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#area").change(function(){
				  var vale = jQuery(this).val();
				  var states = jQuery("#search_criteria_duplicates").val();
				  jQuery("#citiesDataLoadAreas").html("");
				  loader_show();
				  var countiesArray = '.$countiesArray.';
				 
				  jQuery.ajax({
						url: "'.URL_SITE.'/statsus/economics/usconstPLAjax.php",
						type: "post",
						data: "dbid='.$dbid.'&states="+states+"&county="+vale,
						success: function(dataresult){
							loader_unshow();
							var obj = jQuery.parseJSON(dataresult);

							if(obj.error == "0"){
								jQuery("#citiesDataLoadAreas").html(obj.data);
								jQuery("#timePeriod").show();
								jQuery("#submitButtons").show();
							} else {
								jQuery("#citiesDataLoadAreas").html("");
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
							}
						}
					});
				});
			});
		</script>';		
	}
} else if(isset($_REQUEST['states']) && isset($_REQUEST['county'])) {

		$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
		$dataCat = $dbDatabase->getAll($dataSqlCat);	
		
		if(!empty($dataCat)){
			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_one_or_more_cat']))?$lang['lbl_select_one_or_more_cat']:'').'</span>&nbsp;&nbsp;<input type="checkbox" name="allcat" value="" id="allcat">&nbsp;All
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#allcat").change(function(){
								var checked = jQuery(this).is(":checked");
								if(checked){
									jQuery(".catall").attr("checked", "checked");
								} else {
									jQuery(".catall").removeAttr("checked");
								}
							});
						});
						</script>
						</p>
						<div class="table-div"><table width="100%">';
			$catarray = array_chunk($dataCat,3);			
			
			foreach($catarray as $CatKey => $cats){
				$data .= '<tr>';
				foreach($cats as $CatKey => $catDetail){
					$data .= '<td><input type="checkbox" name="catcode[]" class=" catall required" value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</td>';	
				}
				$data .= '</tr>';
			}
			$data .= '</table></div></div>';

		}
		
		include($DOC_ROOT.'/include/stateCountiesPlaces.php');
		$counties = explode(',',$_REQUEST['county']);
		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_select_one_or_more_areas']))?$lang['lbl_select_one_or_more_areas']:'').'</span>&nbsp;&nbsp;</p></div>';

		foreach($counties as $key => $county){
				
				$statecode = substr($county, 0, 2);
				$countyco  = substr($county, 2);
				
				if(isset($StateCountyPlaces[$statecode]) && isset($StateCountyPlaces[$statecode][$countyco])){

					if(isset($_SESSION['countiesArray'])) {					
					   $data .= '<div class="form-div"><p><span class="choose">'.$_SESSION['countiesArray'][$county].'</span>&nbsp;&nbsp;</p><div class="table-div">';
				    }

					$data .= '<select name="areacode[]" id="area" class="required" multiple >
							  <option value="'.$county.'"> County totals</option>';	
					foreach($StateCountyPlaces[$statecode][$countyco] as $countys => $code){
						     $data .= '<option value="'.$code.'">'.$countys.'</option>';
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