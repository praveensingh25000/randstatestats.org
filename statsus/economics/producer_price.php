<?php
/******************************************
* @Modified on Aug 06, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$popcontentindustry = $popcontentSeasonal = $popcontentItems = $popcontentAreas = $filterSeasonalvaluesJson = $filterItemvaluesJson = $filterAreavaluesJson = $dbname = $dbsource = $description = $miscellaneous = '';

$filterAreavaluesJsonArrayAll = $filterAreavaluesJsonArray = $filterSeasonalvaluesJsonArray = $filterItemsvaluesJsonArray = $databaseCategories = $databaseRelatedDatabases = array();

$tablesname				= "producer_prices";
$tablesnameareas		= "producer_price_industry";
$tablesnameitems	    = "producer_price_product";

$admin = new admin();
$user  = new user();

if(isset($_POST['getresults'])){

	$_SESSION['producer_price'] = $_POST;

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
			header('location: producer_priceData.php?dbc='.$dbnameurl.'');			
			exit;
		} else {
		header('location: producer_priceData.php');
		exit;
		}
	}
}

$dbid = 194;
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

$related_DB_cat = $admin->getDatabaseCategories($dbid);
$related_DB		= $admin->getAllRelatedDatabases($dbid,$related_DB_cat['category_id']);

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//For Industry Detail
$industry_res  = $admin->getDistinctColumnValuesUniversal($tablesnameareas , $column='industry_name', $columns = "industry_code", $limit = "");
$industryDetail = $dbDatabase->getAll($industry_res);
foreach($industryDetail as $key => $value) {
	$popcontentindustry .= '<p>'.str_replace("'","",$value['industry_name'])."</p>";
	$filterAreavaluesJsonArray[] = array('id' => $value['industry_code'], 'name' => $value['industry_name']);
	
}
$filterAreavaluesJson = json_encode($filterAreavaluesJsonArray);
//echo "<pre>";print_r($filterAreavaluesJsonArray);echo "</pre>";
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
					<input type="hidden" name="session_setter" value="producer_price">				
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_enter_industry'])){ echo $lang['lbl_please_enter_industry']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="left">
								<input class="required" type="text" id="search_criteria_duplicates_industry" name="industry" />
							</div>
							<div class="left">&nbsp;&nbsp;<a id="viewallitems" href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontentindustry; ?>');">See a list</a></div>						
							<div class="clear"></div>
						</div>
					</div>

					<script type="text/javascript">
					$(document).ready(function() {							
					$("#search_criteria_duplicates_industry").tokenInput( <?php echo $filterAreavaluesJson ; ?>, { 
						preventDuplicates: true,
						tokenLimit: 1,
					
						onAdd: function (item) {

								var industry      = jQuery('#search_criteria_duplicates_industry').val();
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();

								jQuery.ajax({								
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&industry='+industry,
									url: URL_SITE+'/statsus/economics/producer_priceAjax.php',

									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery('#citiesDataLoad').html(obj.data);
											jQuery('#timePeriod').show();
											jQuery('#submitButtons').show();
										} else {
											jQuery('#citiesDataLoad').html('');
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();
										}
									}
								});							

					},
					onDelete: function (item) {

							var industry      = jQuery('#search_criteria_duplicates_industry').val();
							jQuery.ajax({
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&industry='+industry,
								url: URL_SITE+'/statsus/economics/producer_priceAjax.php',

								success: function(dataresult){
									loader_unshow();
									var obj = jQuery.parseJSON(dataresult);

									if(obj.error == "0"){
										jQuery('#citiesDataLoad').html(obj.data);
										jQuery('#timePeriod').show();
										jQuery('#submitButtons').show();
									}else{
										jQuery('#citiesDataLoad').html('');
										jQuery('#timePeriod').hide();
										jQuery('#submitButtons').hide();
									}
								}
							});	
						}
					});										
					});							
					</script>
					<!-- /form -->

					<div id="citiesDataLoad" class="pB10"></div>

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
						
						<div class="submitbtn-div" id="submitButtons" style="display:none;">
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