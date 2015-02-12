<?php
/******************************************
* @Modified on Aug 06, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$admin = new admin();

$tablesname				= "producer_prices";
$tablesnameareas		= "producer_price_industry";
$tablesnameitems	    = "producer_price_product";

$array = $relatedProduct = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting all cities
if(isset($_REQUEST['industry']) && $_REQUEST['industry']!='') {

	$industryArray = explode(';',$_POST['industry']);
		
	foreach($industryArray as $Key => $industrycode){

		$industrycodeone = substr($industrycode,0,3);
		$tableDetailArray_res  = $admin->searchLikeUniversal($tablesnameitems , $column='industry_code', $searchStr=$industrycode, $orderby = " order by product_name ");		
		//$tableDetailArray_res  = $admin->searchLikeFrontUniversal($tablesnameitems , $column='industry_code', $searchStr=$industrycodeone, $orderby = " order by product_name ");
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {
			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedProduct[$industrycode][] = array('id' => $tableDetail['product_code'],'name' => $tableDetail['product_name']);			
			}
		}	
	}
}

if(!empty($relatedProduct)) {

	$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_product']))?$lang['lbl_please_enter_product']:'').'</span></div>';
	
	foreach($relatedProduct as $industryKey => $industrycodeAll) {
		
		$industryDetail  = $admin->getRowUniversal($tablesnameareas, $column='industry_code', $industryKey);
		$industryName    = isset($industryDetail['industry_name'])?$industryDetail['industry_name']:'';
		
		//selecting Product according to industry code	
		$data .= '<div class="form-div">
				  <p><span class="choose">'.$industryName.'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallproduct'.$industryKey.'">&nbsp;All
				  
				  <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallproduct'.$industryKey.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".productAll'.$industryKey.' option").attr("selected", "selected");
							}else{
								jQuery(".productAll'.$industryKey.' option").removeAttr("selected");
							}
						});

						jQuery(".productAll'.$industryKey.'").change(function(){							
							jQuery("#checkallproduct'.$industryKey.'").removeAttr("checked");						
						});
					});
				  </script>				  
				  </p>';
				  
		$data .= '<select size="5" name="product[]" class="productAll'.$industryKey.' required" multiple >';
		foreach($industrycodeAll as $key => $product){				
			$data .= '<option value="'.trim($product['id']).'">'.trim($product['name']).'</option>';
		}				
		$data .= '</select></div>';		
	}
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>