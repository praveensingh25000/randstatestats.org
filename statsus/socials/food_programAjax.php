<?php
/******************************************
* @Created  : Sept 04, 2013
* @Package  : RAND
* @Developer: Praveen Singh
* @url		: http://randstatestats.org
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "federal_food_program_participation_benefits";

$admin = new admin();

$levelArrayDetailMain = $relatedprogram = $relateddepartment = $array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting one more grades
if(isset($_REQUEST['program']) && $_REQUEST['program']!=''){
	
	$program = $_REQUEST['program'];
	$programArray = explode(',',$program);
	foreach($programArray as $stateKey => $pname){		
		$relatedCounty_res = $admin->searchDistinctUniversalColoumOneArray($tablesname, $displaycolumnnamestr='DISTINCT(Category)' ,$columnname='Program', $columnnamevalue=$pname ,'order by Category');
		$relateddepartment[$pname] = $dbDatabase->getAll($relatedCounty_res);
	}

	if(!empty($relateddepartment)) {	

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_please_choose_category']))?$lang['lbl_please_choose_category']:'').'</span></p>';
		
		foreach($relateddepartment as $KeyLevel => $categoryDetailAll){		

			$data .= '<p><span class="fontbld font14">'.$KeyLevel.'</span></p>';

			$data .= '<div class="table-div"><table width="auto">';

			foreach($categoryDetailAll as $keymainAll => $Category){

				$data.='<tr><td align="left">
						<input value="'.$Category['Category'].'" type="checkbox" name="Category[]" class="deparment_level_disable required" />
						</td>
						<td align="left">'.$Category['Category'].'</td>
						<td align="left">&nbsp;</td>';				
			    $data .= '</tr>';
			}
			$data .= '</table></div>';				
		   
		}
		$data .= '</div>';
		
	} else {
		$error = 1;
	}
} else {
	$error = 1;
}

//echo "<pre>";print_r($relatedprogram);echo "</pre>"; die;

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>