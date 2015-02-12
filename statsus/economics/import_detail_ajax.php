<?php
/******************************************
* @Modified on May 13, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/economics/import_detail.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

global $dbDatabase;

require_once('comgroup.php');

$tablesname				= "importdet_qtr_";
$tablesnamecat			= "import_cats";
$tablesnamecomm			= "import_commodities";
$tablesnamecitycodes	= "ctycodes";

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
if(isset($_POST['comgroups']) && $_POST['comgroups']!=''){

	$comgroups = explode(';', $_POST['comgroups']);

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_commodity']))?$lang['lbl_choose_commodity']:'').'</span>&nbsp;&nbsp;</p>
					<label for="commodity[]" generated="true" class="error" style="display:none;">This field is required.</label>
					</div>';
	
	$true = 0;
	foreach($comgroups as $keycomp => $group){
		
		$groupDetail = $comAgg[$group];
		$resultSectors = $admin->searchLikeUniversalEqual($tablesnamecomm , 'com_group_id', $group, ' order by com_name ');
		if(mysql_num_rows($resultSectors)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">Group: '.$groupDetail.'</span>&nbsp;&nbsp;</p>
			<div class="table-div">';
			while($detailRow = mysql_fetch_assoc($resultSectors)){
				$data .= '<input type="checkbox" name="commodity[]" class="required" value="'.$detailRow['com_id'].'" >'.$detailRow['com_name'].'<br/>';
				$true = 1;
			}
			$data .= '</div></div>';
		}
	}
	
	if($true == 1){
		$resultCats = $admin->getTableDataUniversal($tablesnamecat ,' order by catname ');
		if(mysql_num_rows($resultCats)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_choose_category']))?$lang['lbl_choose_category']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div">';
			while($detailRow = mysql_fetch_assoc($resultCats)){
				$data .= '<input class="required" name="category[]" type="checkbox" value="'.$detailRow['catcode'].'" >'.$detailRow['catname'].'<br/>';
			}
			$data .= '<label for="category[]" style="display:none;"generated="true" class="error">This field is required.</label></div></div>';
		}

		$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_choose_origin']))?$lang['lbl_choose_origin']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><select size="5" multiple="" class="required" name="area[]" id="D6">
		<option value="99"> U.S.
		</option><option value="31"> Anchorage Alaska
		</option><option value="13"> Baltimore M.D.
		</option><option value="04"> Boston Mass.
		</option><option value="09"> Buffalo N.Y.
		</option><option value="16"> Charleston S.C.
		</option><option value="39"> Chicago Ill.
		</option><option value="41"> Cleveland Ohio
		</option><option value="29"> Columbia-Snake
		</option><option value="55"> Dallas/Fort Worth, Texas
		</option><option value="38"> Detroit Mich.
		</option><option value="36"> Duluth Minn.
		</option><option value="24"> El Paso Tex.
		</option><option value="33"> Great Falls Mont.
		</option><option value="32"> Honolulu Hawaii
		</option><option value="53"> Houston Tex.
		</option><option value="23"> Laredo Tex.
		</option><option value="27"> Los Angeles
		</option><option value="52"> Miami Fla.
		</option><option value="37"> Milwaukee Wis.
		</option><option value="35"> Minneapolis Minn.
		</option><option value="19"> Mobile Ala.
		</option><option value="20"> New Orleans La.
		</option><option value="10"> New York City N.Y.
		</option><option value="26"> Nogales Ariz.
		</option><option value="14"> Norfolk Va.
		</option><option value="07"> Ogdensburg N.Y.
		</option><option value="34"> Pembina N. Dak.
		</option><option value="11"> Philadelphia Pa.
		</option><option value="21"> Port Arthur Tex.
		</option><option value="01"> Portland Maine
		</option><option value="05"> Providence R.I.
		</option><option value="25"> San Diego
		</option><option value="28"> San Francisco
		</option><option value="49"> San Juan Puerto Rico
		</option><option value="17"> Savannah Ga.
		</option><option value="30"> Seattle Wash.
		</option><option value="70"> Shipments Individually
		</option><option value="02"> St. Albans Vt.
		</option><option value="45"> St. Louis Mo.
		</option><option value="18"> Tampa Fla.
		</option><option value="60"> Vessels Own Power
		</option><option value="51"> Virgin Islands
		</option><option value="54"> Washington D.C.
		</option><option value="15"> Wilmington N.C.
		</option></select><br/><label for="D6" generated="true" style="display:none;" class="error">This field is required.</label></div></div>';


		$resultCats = $admin->getTableDataUniversal($tablesnamecitycodes ,' where ccode not in (5684,5686,5688,5690)order by country ');
		if(mysql_num_rows($resultCats)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_choose_destinations']))?$lang['lbl_choose_destinations']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><select name="country[]" class="required" multiple>';
			while($detailRow = mysql_fetch_assoc($resultCats)){
				$data .= '<option value="'.$detailRow['ccode'].'" >'.$detailRow['country'].'</option>';
			}
			$data .= '</select><br/><label for="country" generated="true" style="display:none;" class="error">This field is required.</label></div></div>';
		}
	} else {
		$error = 1;
		$errorMSG = 'No matches found.';
	}
		
	
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>