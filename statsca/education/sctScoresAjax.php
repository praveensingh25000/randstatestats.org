<?php
/******************************************
* @Modified on Oct 28, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: New Database
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "ca_sct_scores";
$tablenamecounties = "ca_counties";

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


if($_REQUEST['states']!='') {

	$countiesArray = explode(';', $_REQUEST['states']);
	

	foreach($countiesArray as $keyS => $countyid){
		
		if(strlen($countyid)<2){
			$countyid  = "0".$countyid;
		}

		$countyDetail = $admin->getRowUniversal($tablenamecounties, 'areacode', $countyid);

		$data .= '<div class="form-div pT10">
					<p><span class="choose">'.((isset($lang['lbl_select_schools_in_counties']))?str_replace('#COUNTYNAME#', $countyDetail['areaname'], $lang['lbl_select_schools_in_counties']):'').'</span>
				</p>';


		$data .= '<select id="schooldistrict" name="schooldistrict[]" class="schooldistrict required" multiple >';
		
		$schools = $admin->searchDistinctUniversalColoumOneArray($tablesname , "distinct(school_number), school_name", 'county_number', (int)$countyid, ' order by school_name');

		if(mysql_num_rows($schools)>0){
			while($schoolDetail = mysql_fetch_assoc($schools)){
				$data .= '<option value="'.$schoolDetail['school_number'].'" >'.$schoolDetail['school_name'].'</option>';
			}
		}

		$data.="</select>";	
						
		$data .= '</div>';


				
	
	}
	
	$countyDetail = $admin->getRowUniversal($tablenamecounties, 'areacode', $countyid);

	$data .= '<div class="form-div pT10">
				<p><span class="choose">'.((isset($lang['lbl_select_categories']))?$lang['lbl_select_categories']:'').'</span>
				&nbsp;<input type="checkbox" id="checkallcats">&nbsp;All
				</span>

				<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("#checkallcats").click(function(){
						if(jQuery( this ).is( ":checked" )){
							jQuery(".catchecks").attr("checked","checked");
						} else {
							jQuery(".catchecks").removeAttr("checked");
						}
					});
				});
				</script>

			</p>';
	
	$categories = $admin->getDistinctColumnValuesUniversal($tablesname , "category");

	if(mysql_num_rows($categories)>0){
		while($catDetail = mysql_fetch_assoc($categories)){
			$data .= '<input type="checkbox" name="category[]" class="required catchecks" value="'.$catDetail['category'].'" >'.$catDetail['category'].'<br/>';
		}
	}
	$data .= '</div>';

			
} else {
	$error = 1;
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>