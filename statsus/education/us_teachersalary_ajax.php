<?php
/******************************************
* @Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live Site URL For This Page: http://statestats.rand.org/stats/education/us_teachersalary.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "us_teachersalary";
$tablesnamearea = "usk12enroll_areas";



$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' ){
	
	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	if(isset($_REQUEST['counties'])){
		$counties = explode(',', $_REQUEST['counties']);
	}

	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
	
			
		$dataSqlAll = $admin->searchDistinctUniversalColoumArray($tablesnamearea , 'areast', $stateAlpaCode, ' order by areacounty ');	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_usk_enroll']))?$lang['lbl_choose_one_or_more_usk_enroll']:'').'</span>&nbsp;&nbsp;
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat_'.$stateAlpaCode.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#checkstate_checkallcat option").attr("selected", "selected");
							}else{
								jQuery("#checkstate_checkallcat option").removeAttr("selected");
							}
						});

						jQuery("#checkstate_checkallcat").change(function(){
							var counties = jQuery(this).val();
							var states = jQuery("#search_criteria_duplicates").val();
							if(counties!=""){
								loader_show();
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
								jQuery.ajax({
									url: "us_teachersalary_ajax.php",
									type: "post",
									data: "dbid='.$dbid.'&states="+states+"&counties="+counties,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery("#citiesDataLoad").html(obj.data);
											jQuery("#timePeriod").show();
											jQuery("#submitButtons").show();
										}
									}
								});
							}
						});
					});
					</script></p>
					<div class="table-div">';

		$data .= '<select name="countycode[]" id="checkstate_checkallcat" class="required" multiple >';
		$already = array();
		$countiesarray = array();
		foreach($dataSqlAll as $keyArea => $areaDetail){
			if($areaDetail['areacounty']!= '' && !in_array($areaDetail['areaco'], $already)){
				$already[] = $areaDetail['areaco'];
				$selected = '';
				$countiesarray[$areaDetail['areaco']] = $areaDetail['areacounty'];
				if(isset($counties) && in_array($areaDetail['areaco'], $counties)) {
					$selected = 'selected';
				}
				$data .= '<option value="'.$areaDetail['areaco'].'" '.$selected.'>'.$areaDetail['areacounty'].'</option>';	
			}
		}
			
		$data .= '</select></div></div>';

	}

	if(isset($_REQUEST['counties'])){
		$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesname , 'Category');
		if(mysql_num_rows($resultSectors)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_select_one_or_more_cats']))?$lang['lbl_select_one_or_more_cats']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><div class="table-div"><select name="category[]" class="required" multiple>';
			while($detailRow = mysql_fetch_assoc($resultSectors)){
				$data .= '<option value="'.$detailRow['Category'].'" >'.$detailRow['Category'].'</option>';
			}
			$data .= '</select></div></div></div>';
		}

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_schools_districts']))?$lang['lbl_choose_one_or_more_schools_districts']:'').'</span>&nbsp;&nbsp;</p><div class="table-div">';

		foreach($counties as $cc => $county){
			  
			   $countyDistricts = $admin->searchUniversalArray($tablesnamearea , array('areaco' => $county, 'areaType' => 'District'), ' order by areaagency ');
			   $districts = array();
			   if(mysql_num_rows($countyDistricts)>0){
					$data .= '<div><p>County&nbsp;<b>'.$countiesarray[$county].'</b></p></div>';
					while($district = mysql_fetch_assoc($countyDistricts)){
						$data .= '<div><p><i>District</i> <b>'.$district['areaname'].'</b></p><select name="areacode[]" class="required" multiple>
						<option value="'.$district['areacode'].'">District Totals</option>';
						
						$districtSchools = $admin->searchLikeFrontUniversal($tablesnamearea , 'areacode', $district['areacode'],' order by areaname ');
						if(mysql_num_rows($districtSchools)>0){
							while($school = mysql_fetch_assoc($districtSchools)){
								if($school['areacode']!=$district['areacode'])
								$data .= '<option value="'.$school['areacode'].'">'.$school['areaname'].' (city: '.$school['areacity'].')</option>';
							}
						}
						$data .= '</select></div>';
					}		
					
			   }


		}

		$data .= '</div></div>';

	}

} else {
	$error = 1;
	$errorMSG = "Please choose state & school type first";
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>