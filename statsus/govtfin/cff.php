<?php
/******************************************
* @created on Feb 28, 2013
* @Package: Rand
* @Developer: sandeep kumar
* @live URL: http://statestats.rand.org/stats/govtfin/cff.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesname					= "cffus";
$tablesnamecat				= "cff_programs";
$tablesnamearea				= "cff_agency";
$tablesnamecountyarea		= "usareas";
$tablestates				= "us_states";

$admin = new admin();
$user = new user();

$dbname	= $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['cff'] = $_POST;

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
			header('location: cffData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
		header('location: cffData.php');
		exit;
		}
	}
}

if(isset($_POST['all'])){

	$_SESSION['cff'] = $_POST;

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
			header('location: cffData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
		header('location: cffData.php');
		exit;
		}
	}
}

$dbid = 79;
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

$allstateToName_res  = $admin->getTableDataUniversal($tablestates);
$stateToName = $dbDatabase->getAll($allstateToName_res);

foreach($stateToName as $key => $value)
$filtervaluesJsonArray[] = array('id' => $value['id'], 'name' => $value['statename']);
$filtervaluesJson = json_encode($filtervaluesJsonArray);

//categpry Detail
$allCategoryDetail_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $columnname='Category', $columnnamevalue='', $orderby = '');
$totalallCategoryDetailArray = $dbDatabase->getAll($allCategoryDetail_res);
foreach($totalallCategoryDetailArray as $stateKey => $totalallCategoryDetails) {
	$totalallCategoryDetail[$totalallCategoryDetails['Category']] = $totalallCategoryDetails['Category'];
}

//echo "<pre>";print_r($totalallCategoryDetail);echo "</pre>";
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

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
					<!-- form -->
					<input type="hidden" name="session_setter" value="cff">
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_use_this_button_summary'])){ echo $lang['lbl_use_this_button_summary']; } ?> </span>
						</p>
						<div class="table-div">
							<input id="all_data" onclick="javascript: return checkLoginUser('<?php echo $form_type;?>','<?php if(isset($_SESSION['user'])) echo "true"; else echo 'false'; ?>','<?php echo $db_url;?>','0');" value="Total Spending in U.S. States, All Categories" name="all" class="submitbtn" type="submit">		
						</div>
					</div>
					<!-- /form -->
					
					<!-- form -->
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_select_more_areas_categories'])){ echo $lang['lbl_select_more_areas_categories']; } ?> </span>
						</p>
						<div class="table-div">
							<input type="text" id="search_criteria_duplicates" name="us_states"/>
						</div>
					</div>
					<!-- /form -->

					<!-- form -->
					<?php if(!empty($totalallCategoryDetail)){?>
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_select_more_categories'])){ echo $lang['lbl_select_more_categories']; } ?> </span>
						</p>
						<div class="pL10 pB10">
						    <?php foreach($totalallCategoryDetail as $categoryDetail) { ?>				
								<input class="on_click_all" type="checkbox" value="<?php echo $categoryDetail;?>" name="Category[]">
								<label for="C1"><?php echo ucwords($categoryDetail);?></label>			
								<div id="" class="clear pB5"></div>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
					<!-- /form -->
					
					<div class="pB10" id="cities_data_load"></div>

					<script type="text/javascript">
					$(document).ready(function() {							
						
						$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, {		
							preventDuplicates: true,
							
							onAdd: function (item) {
								var states = jQuery('#search_criteria_duplicates').val();
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsus/govtfin/cffAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&states='+states,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){											
											jQuery('#cities_data_load').html(obj.data);
											jQuery('#timePeriod').show();
											jQuery('#submitButtons').show();
										}
									}
								});
							},
							onDelete: function (item) {
								var states = jQuery('#search_criteria_duplicates').val();
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsus/govtfin/cffAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&states='+states,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);
										
										if(obj.error == 0){											
											jQuery('#cities_data_load').html(obj.data);
											jQuery('#timePeriod').show();
											jQuery('#submitButtons').show();
										}
									}
								});
							}
						
						});
			
					});
					
					</script>

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
					<script type="text/javascript">
						$(document).ready(function() {							
							$("#main_button_click").click(function(){				
								jQuery(".on_click_all").addClass('required');
							});
						});
						</script>
					
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