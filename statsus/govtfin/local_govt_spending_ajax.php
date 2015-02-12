<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: sandeep kumar
#live Site URL For This Page: http://statestats.rand.org/stats/govtfin/local_govt_spending.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "local_govt_spending";
$tablesnamearea = "local_govt_areas";
$tablesnamecat = "local_govt_spending_cats";

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

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['sector']) && $_REQUEST['sector']!='' && isset($_REQUEST['cat2']) && $_REQUEST['cat2']!='' && $_REQUEST['cat2']!='none') {

	if(isset($_REQUEST['cat2']) && trim($_REQUEST['cat2']) == 'all'){
		$columnsArray = array('Area' => $_REQUEST['states']);
	} else {
		$columnsArray     = array('Area' => $_REQUEST['states'],'Cat2' => trim($_REQUEST['cat2']));
	}

	$tableDetailArray_res  = $admin->searchLikeUniversalArrayAll($tablesname , $colounname='Category', $columnsArray, $orderby = 'order by Category', $limit = '');
	$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {		
	
		$data .= '<div class="form-div">
					  <p><span class="choose">'.((isset($lang['lbl_select_category_govt_spending']))?$lang['lbl_select_category_govt_spending']:'').' : '.ucwords($_REQUEST['cat2']).'</span>&nbsp;&nbsp;</p>';

	    $data .= '<div class="table-div"><select name="category[]" id="" class="required" multiple >';

		while($catDetail = mysql_fetch_assoc($tableDetailArray_res)){
			
			$catDetailOne  = $admin->getRowUniversal($tablesnamecat, $column='catcode', $value=$catDetail['Category']);
			
			if(!empty($catDetailOne)) {
				$data .= '<option value="'.$catDetailOne['catcode'].'">'.$catDetailOne['catname'].'</option>';
			}			
		}
		$data .= '</select></div></div>';
	}

	if(isset($_REQUEST['sector']) && $_REQUEST['sector'] == 'county'){
		$columnsArray2 = array('0' => $_REQUEST['sector'],'1' => 'Borough');	
	} else if(isset($_REQUEST['sector']) && $_REQUEST['sector'] == 'city') {
		$columnsArray2 = array('0' => $_REQUEST['sector'],'1' => 'town');
	} else if(isset($_REQUEST['sector']) && $_REQUEST['sector'] == 'district'){
		$columnsArray2 = array('0' => $_REQUEST['sector']);
	} else {
		$columnsArray2 = array('0' => $_REQUEST['sector']);
	}

	$columnsstr        = 'areaname,areacode,areacounty';
	$searchcolumnname1 = 'areastate';
	$searchcolumnname2 = 'areaname';
	$columnsArray1     = array('0' => $_REQUEST['states']);

	$tableDetailArray_res1  = $admin->searchDistinctUniversalArrayAll($tablesnamearea ,$columnsstr, $searchcolumnname1, $searchcolumnname2, $columnsArray1, $columnsArray2, $orderby = 'order by areaname', $limit = '');
	$totaltableDetailArray = mysql_num_rows($tableDetailArray_res1);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

		$data .= '<div class="form-div">
					  <p><span class="choose">'.((isset($lang['lbl_choose_one_more']))?$lang['lbl_choose_one_more']:'').'<b>'.ucwords($_REQUEST['sector']).'.</b></span>&nbsp;&nbsp;</p>';
		
		$data .= '<div class="table-div"><select name="areacode[]" class="required" multiple >';

		$county='';
		while($areaDetail = mysql_fetch_assoc($tableDetailArray_res1)){	
			if(isset($_REQUEST['sector']) && $_REQUEST['sector'] != 'county'){
				$county='('.$areaDetail['areacounty'].')';
			}
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' '.$county.'</option>';				
		}
		$data .= '</select></div></div>';
	}
} else {
	$error = 1;
	$errorMSG = "Please choose state first";
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>