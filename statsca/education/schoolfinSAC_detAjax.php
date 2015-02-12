<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: statsca/education/schoolfinSAC_det.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname			= "schoolfinSAC";
$tablesnamecat		= "schoolfinSAC_cats";
$tablesnamecounty	= "ca_counties";
$tableDistricts		= "cd_new";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['cats']) && $_REQUEST['cats']!= ''){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_select_fund_selected']))?$lang['lbl_select_fund_selected']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

	$cats = explode(';', $_REQUEST['cats']);
	foreach($cats as $keyc => $catdet){
		$data .= '<label>'.$catdet.'</label><br/>';
	}
	$data .= '</div></div>';

	$arrayselectedtype = array();
	if(isset($_REQUEST['type'])){
		$arrayselectedtype = explode(';', $_REQUEST['type']);
	}

	$arrayfunds = array('1' => 'Expenditures', '2' => 'Revenues', '3' => 'Assets', '4' => 'Liabilities', '5' => 'Balance sheet');	
	if(count($arrayfunds)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_account_type']))?$lang['lbl_select_account_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		foreach($arrayfunds as $keyfund => $catD){
			$selected = '';
			if(in_array($keyfund, $arrayselectedtype)){
				$selected = 'checked = "checked"';
			}
			$data .= '<input type="checkbox" name="vartype[]" class="required vartype" value="'.$keyfund.'" '.$selected.'/>&nbsp;'.$catD.'&nbsp;<br/>';	
		}

		$data .= "<input type='hidden' value='' id='vartypet' />
		<script type='text/javascript'>   
		jQuery(document).ready(function(){
			
			jQuery('.vartype').click(function(){
				var allVals = '';
				jQuery('input[name=\"vartype[]\"]:checked').each(function() {
					allVals += $(this).val() + ';';
				});
				$('#vartypet').val(allVals);

				var areacode = '';
				$('#areacode option:selected').each(function(){
					areacode += $(this).val() + ';';
				});
				$('#varareacode').val(areacode);

				if(areacode != ''){
					var states = jQuery('#search_criteria_duplicates').val();
					var cats = jQuery('#search_criteria_duplicates_cats').val();
					var varareacode = jQuery('#varareacode').val();
					var vartypet = jQuery('#vartypet').val();
					loader_show();
					jQuery('#timePeriod').hide();
					jQuery('#submitButtons').hide();
					jQuery.ajax({
						url: '".URL_SITE."/statsca/education/schoolfinSAC_detAjax.php',
						type: 'post',
						data: 'dbid=".$dbid."&states='+states+'&cats='+cats+'&areacode='+varareacode+'&type='+vartypet,
						success: function(dataresult){
							loader_unshow();
							var obj = jQuery.parseJSON(dataresult);

							if(obj.error == '0'){
								jQuery('#cities_data_load').html(obj.data);
								jQuery('#timePeriod').show();
								jQuery('#submitButtons').show();
							}else {
								jQuery('#cities_data_load').html('');
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
							}
						}
					});
				}
			});
		});
		</script>";

		$data .= '</div></div>';

	}

	$arrayselectedarea = array();
	if(isset($_REQUEST['areacode'])){
		$arrayselectedarea = explode(';', $_REQUEST['areacode']);
	}

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_geographic_school_fin']))?$lang['lbl_select_geographic_school_fin']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;

		$columnsArray = array('co' => (int)$stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, 'order by cdname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		

		$countDetail = $admin->getRowUniversal($tablesnamecounty, 'areacode', $stateAlpaCode);
		
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$countDetail['areaname'].' Districts.</span>&nbsp;&nbsp;</p>
						<div class="table-div">';
			$data .= "<script type='text/javascript'>   
			jQuery(document).ready(function(){
				
				jQuery('#areacode').change(function(){
					var allVals = '';
					jQuery('input[name=\"vartype[]\"]:checked').each(function() {
						allVals += $(this).val() + ';';
					});
					$('#vartypet').val(allVals);

					var areacode = '';
					$('#areacode option:selected').each(function(){
						areacode += $(this).val() + ';';
					});
					$('#varareacode').val(areacode);

					if(allVals != ''){
						var states = jQuery('#search_criteria_duplicates').val();
						var cats = jQuery('#search_criteria_duplicates_cats').val();
						var varareacode = jQuery('#varareacode').val();
						var vartypet = jQuery('#vartypet').val();
						loader_show();
						jQuery('#timePeriod').hide();
						jQuery('#submitButtons').hide();
						jQuery.ajax({
							url: '".URL_SITE."/statsca/education/schoolfinSAC_detAjax.php',
							type: 'post',
							data: 'dbid=".$dbid."&states='+states+'&cats='+cats+'&areacode='+varareacode+'&type='+vartypet,
							success: function(dataresult){
								loader_unshow();
								var obj = jQuery.parseJSON(dataresult);

								if(obj.error == '0'){
									jQuery('#cities_data_load').html(obj.data);
									jQuery('#timePeriod').show();
									jQuery('#submitButtons').show();
								}else {
									jQuery('#cities_data_load').html('');
									jQuery('#timePeriod').hide();
									jQuery('#submitButtons').hide();
								}
							}
						});
					}
				});
			});
			</script>";

			$data .= '<input type="hidden" value="" id="varareacode">
			<select name="areacode[]" id="areacode" class="required" multiple >';

			

			foreach($dataSqlAll as $keyArea => $areaDetail){
				if($areaDetail['cdcode']!= (int)$stateAlpaCode)	{
					$selected = '';
					if(in_array($areaDetail['cdcode'], $arrayselectedarea)){
						$selected = 'selected = "selected"';
					}

					$areaname = ucwords(strtolower($areaDetail['cdname']));
					$data .= '<option value="'.$areaDetail['cdcode'].'" '.$selected.'>'.$areaname.'</option>';
				}
			}
				
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}

	
}

if(isset($_POST['areacode']) && isset($_POST['type'])){
	require_once('fundcodes_sac.php');
	$areacode = $_POST['areacode'];
	$areacodeArray = explode(';',$areacode);
	$areacodes = implode(',', $areacodeArray);
	$areacodes = substr($areacodes, 0, -1);

	$areatype = explode(';', $_POST['type']);


	foreach($areatype as $keysele => $idselect){
		if($idselect!=''){
			$data .= '<div class="form-div">
					<p><span class="choose">Select '.$arrayfunds[$idselect].' categories.</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
			$fundcodesArray = $fundCodes[$idselect];
			$catids = '';
			foreach($fundcodesArray as $keyf => $catid){
			  $catids .= "'".$catid."',";
			}
			$catids = substr($catids, 0, -1);

			$catids1 = '';
			foreach($cats as $keyf1 => $catid1){
			  $catids1 .= "'".$catid1."',";
			}
			$catids1 = substr($catids1, 0, -1);

			$sql = "SELECT DISTINCT(sc.catname), sc.catcode FROM `schoolfinSAC` as s, `schoolfinSAC_cats` as sc WHERE s.Cat1 in( ".$catids1." ) and sc.catcode = s.Category and s.CDS in (".$areacodes.") and s.Category in (".$catids.") order by sc.catname";
			$searchedDataResult = $dbDatabase->run_query($sql);
			if(mysql_num_rows($searchedDataResult)>0){
				$data .= '<select name="category[]" class="required" multiple>';
				while($catdet = mysql_fetch_assoc($searchedDataResult)){
					 $data .= '<option value="'.$catdet['catcode'].'">'.$catdet['catname'].'</option>';
				}
				$data .= '</select>';
			} else {
				$data .= '<p>No categories available.</p>';
			}
			$data .= '</div></div>';
		}
	}

	$data .= '<div class="table-div">
	<p><input type="checkbox" value="01" name="areacode[]" id="CO">&nbsp;<label for="CO">Include county totals</label><br/>
	<input type="checkbox" value="0" name="areacode[]" id="ST">&nbsp;<label for="ST">Include state totals</label>
	</div>';

}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>