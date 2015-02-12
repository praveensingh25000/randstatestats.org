<?php
/******************************************
* @Modified on July 17, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesnameitems = $popcontentSeasonal = $popcontentItems = $popcontentAreas = $filterSeasonalvaluesJson = $filterItemvaluesJson = $filterAreavaluesJson = $dbname = $dbsource = $description = $miscellaneous = '';

$categoryIDArray = $filterAreavaluesJsonArray = $filterSeasonalvaluesJsonArray = $filterItemsvaluesJsonArray = $databaseCategories = $databaseRelatedDatabases = array();

$tablesname				= "consumer_prices";
$tablesnameareas		= "consumer_prices_areas";
$tablesnameitems	    = "consumer_prices_energy_items";

$admin = new admin();
$user  = new user();

if(isset($_POST['getresults'])){

	$_SESSION['consumer_prices'] = $_POST;

	if(isset($_REQUEST['dbnameurl']) && $_REQUEST['dbnameurl']!='') {
		$dbnameurl = $_REQUEST['dbnameurl'];
	}	
	$user_id		=	$_POST['user_id'];	
	$userDetail		=	$user->getUser($user_id);	
	$validity_on	=	$admin->Validity(trim($userDetail['id']),trim($userDetail['email']));
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: '.URL_SITE.'/plansubscriptions.php');
		exit;
	} else {
		if(isset($dbnameurl) && $dbnameurl!='') {
			header('location: consumer_pricesData.php?dbc='.$dbnameurl.'');			
			exit;
		} else {
		header('location: consumer_pricesData.php');
		exit;
		}
	}
}

$dbid = 201;
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){

	$dbname						= stripslashes($databaseDetail['db_title']);
	$dbsource					= stripslashes($databaseDetail['db_datasource']);
	$description				= stripslashes($databaseDetail['db_description']);
	$miscellaneous				= stripslashes($databaseDetail['db_misc']);
	$nextupdate					= stripslashes($databaseDetail['db_nextupdate']);
	$table						= stripslashes($databaseDetail['table_name']);
	$db_geographic				= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity				= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries				= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource				= stripslashes($databaseDetail['db_source']);
	$dateupdated				= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink			= stripslashes($databaseDetail['db_sourcelink']);
	$db_url						= stripslashes($databaseDetail['url']);
	$form_type					= 'two_stage';

}else{
	header('location: databases.php');
}

$categoryDetail_res 		= $admin->getAllDatabaseCategories($dbid);
$categoryDetailArray		= $dbDatabase->getAll($categoryDetail_res);
if(!empty($categoryDetailArray)){
	foreach($categoryDetailArray as $categoryDetail){
		if($categoryDetail['cat_type'] =='p'){
			$categoryIDArray[] = $categoryDetail['category_id'];
		}
	}
}

$related_DB_cat = $admin->getDatabaseCategories($dbid);
$related_DB		= $admin->getAllRelatedDatabases($dbid,$related_DB_cat['category_id']);

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//For Seasonal Detail
$seasonaldetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $column='cat1', $columns = "", $limit = "");
$totalSeasonalDetail = $dbDatabase->getAll($seasonaldetailages_res);
foreach($totalSeasonalDetail as $key => $value){
	$popcontentSeasonal .= '<p>'.$value['cat1']."</p>";
	$filterSeasonalvaluesJsonArray[] = array('id' => $value['cat1'], 'name' => $value['cat1']);
}
$filterSeasonalvaluesJson = json_encode($filterSeasonalvaluesJsonArray);

//For Items Detail
$itemsdetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesnameitems , $column='item_code', $columns = "item_name", $limit = "");
$totalItemDetail = $dbDatabase->getAll($itemsdetailages_res);
foreach($totalItemDetail as $key => $value){
	$popcontentItems .= '<p>'.str_replace("'",'',$value['item_name'])."</p>";
	$filterItemsvaluesJsonArray[] = array('id' => $value['item_code'], 'name' => $value['item_name']);
}
$filterItemvaluesJson = json_encode($filterItemsvaluesJsonArray);

//For Area Detail
$areadetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesnameareas , $column='area_code', $columns = "area_name", $limit = "");
$totalAreaDetail = $dbDatabase->getAll($areadetailages_res);
foreach($totalAreaDetail as $key => $value) {	
	$popcontentAreas .= '<p>'.str_replace("'","",$value['area_name'])."</p>";
	$filterAreavaluesJsonArray[] = array('id' => $value['area_code'], 'name' => $value['area_name']);
}
$filterAreavaluesJson = json_encode($filterAreavaluesJsonArray);

//echo "<pre>";print_r($categoryIDArray);echo "</pre>";
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2><br />

		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->

			<form method="post" id="frmPost" name="frmPost" action="<?php echo $URL_SITE.$_SERVER['PHP_SELF']?>" novalidate="novalidate">
				
					<!-- form -->
					<input type="hidden" name="session_setter" value="consumer_prices">
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_enter_seasonal_code'])){ echo $lang['lbl_enter_seasonal_code']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="left">
								<table>
									<tbody>
										<tr>
										<?php
										foreach($filterSeasonalvaluesJsonArray as $values) { ?>
											<td>
												<input class="required" type="checkbox" id="search_criteria_duplicates_cat1" value="<?php echo $values['id']?>" name="cat1[]" />&nbsp;<b><?php echo $values['name']?></b>
											<td>
										<?php } ?>
										</tr>
									<tbody>
								</table>				
							</div>											
							<div class="clear"></div>
						</div>
					</div>
					<!-- /form -->

					<!-- form -->					
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_enter_service_item'])){ echo $lang['lbl_enter_service_item']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="left">
								<input class="required" type="text" id="search_criteria_duplicates_items" name="items" />
							</div>
							<div class="left">&nbsp;&nbsp;<a id="viewallitems" href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontentItems; ?>');">See a list</a></div>						
							<div class="clear"></div>
						</div>
					</div>

					<script type="text/javascript">
					$(document).ready(function() {	
						$("#search_criteria_duplicates_items").tokenInput(<?php echo $filterItemvaluesJson; ?>, {	
							preventDuplicates: true,												
						});		
					});					
					</script>
					<!-- /form -->

					<!-- form -->
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_enter_service_areas'])){ echo $lang['lbl_enter_service_areas']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="left">
								<input class="required" type="text" id="search_criteria_duplicates_areas" name="areas" />
							</div>
							<div class="left">&nbsp;&nbsp;<a id="viewallareas" href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontentAreas; ?>');">See a list</a></div>						
							<div class="clear"></div>
						</div>
					</div>

					<script type="text/javascript">
					$(document).ready(function() {	
						$("#search_criteria_duplicates_areas").tokenInput(<?php echo $filterAreavaluesJson; ?>, {	
							preventDuplicates: true,												
						});		
					});					
					</script>
					<!-- /form -->


					<!-- form -->
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_choose_CPIU_index_percent'])){ echo $lang['lbl_please_choose_CPIU_index_percent']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="left">
								<input class="required" type="radio" id="search_cpiu" name="cpiu" value="percent" />&nbsp;&nbsp;<b>CPI-U Index</b>
							</div>
							<div class="wdthpercent80 right">
								<input class="required" type="radio" id="search_cpiu" name="cpiu" value="cpiu" />&nbsp;&nbsp;<b>Percent</b>
							</div>						
							<div class="clear"></div>
						</div>
					</div>
					<!-- /form -->

					<!---------------- TIME INTERVAL SETTINGS -------------------->
					<div class="bottom-submit">	
						<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SY-EY'){							
							$time_format = $timeIntervalSettings['time_format'];
							$columns = unserialize($timeIntervalSettings['columns']);
						?>

							<h4>Please choose a time period.</h4>
							<br />
							
							<div class="time-select">
								<label for="smonth">Start Year</label>
								<br />
							   <select id="syear" size="1" name="syear">
								<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
								<?php } ?>
								</select>
							</div>
						   
							<div class="time-select">
								<label for="smonth">End Year</label>
								<br />
								<select id="eyear" size="1" name="eyear">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="clear"> </div>

						<?php } ?>

						<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY'){ 	
								
							$time_format = $timeIntervalSettings['time_format'];
							$columns = unserialize($timeIntervalSettings['columns']);	
							
							$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
							
						?>
							<h4>Please choose a time period.</h4>
							<br />
							<div class="time-select">
								<label for="smonth">Start Month</label>
								<br />
							   <select id="smonth" size="1" name="smonth">
									<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['smonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
									<?php } ?>
								
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">Start Year</label>
								<br />
							   <select id="syear" size="1" name="syear">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">End Month</label>
								<br />
								<select id="emonth" size="1" name="emonth">
									<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['emonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">End Year</label>
								<br />
								<select id="eyear" size="1" name="eyear">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="clear"> </div>

						<?php } ?>

						<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY'){ 					
							$time_format = $timeIntervalSettings['time_format'];
							$columns = unserialize($timeIntervalSettings['columns']);						
							$quaters = array(1 => "1st", 2 => "2nd", 3 => "3rd", 4 =>"4th");			
						?>
							
								<h4>Please choose a time period.</h4>
								<br />
								<div class="time-select">
									<label for="smonth">Start Quarter</label>
									<br />
									<select id="squater" size="1" name="squater">
										<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['squater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="time-select">
									<label for="smonth">Start Year</label>
									<br />
									<select id="syear" size="1" name="syear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="time-select">
									<label for="smonth">End Quarter</label>
									<br />
									<select id="equater" size="1" name="equater">
										<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['equater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="time-select">
									<label for="smonth">End Year</label>
									<br />
									<select id="eyear" size="1" name="eyear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="clear"> </div>

						<?php } ?>
						
						<div class="submitbtn-div" id="submitButtons">
							<input onclick="javascript: return checkLoginUser('<?php echo $form_type;?>','<?php echo $checksubmitType; ?>','<?php echo $db_url;?>','0');" value="Submit" name="getresults" class="submitbtn" type="submit">
							<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >					
							<?php if(isset($_GET['dbc']) && $_GET['dbc']!='') { ?>
							<input value="<?php echo $_GET['dbc'];?>" name="dbnameurl" type="hidden">	
							<?php } ?>							
							<?php if(isset($user_id) && $user_id!='') { ?>
							<input value="<?php echo $user_id;?>" name="user_id" type="hidden">	
							<?php } ?>
							<input type="submit" class="right" name="" value="Reset">
						</div>
						
					</div>
					<!---------------- TIME INTERVAL SETTINGS -------------------->
				
				</form>

			</div>

	</section>

	<!-- RELATED_DB -->
	<?php 
	if(isset($related_DB) && mysql_num_rows($related_DB) > 0) { ?>
	<section id="inner-sidebar">
	   <?php require_once $basedir."/relatedForms.php"; ?>
   </section>
   <?php } ?>
   <!-- RELATED_DB -->

</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>