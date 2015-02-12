<?php
/******************************************
* @Modified on Feb 28,June 24, 2013
* @Package: Rand
* @Developer: sandeep kumar,Praveen Singh
* @live Site URL For This Page: http://statestats.rand.org/stats/govtfin/local_govt_spending.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user  = new user();

$tablesname     = "local_govt_spending";
$tablesnamearea = "local_govt_areas";
$tablesnamecat  = "local_govt_spending_cats";

$dbname = $dbsource = $description = $miscellaneous = $popcontent = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['local_govt_spending'] = $_POST;

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
			header('location: local_govt_spendingData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
		header('location: local_govt_spendingData.php');
		exit;
		}
	}
}

$dbid = 81;
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

$statesResult = $admin->searchLikeUniversal($tablesnamearea , 'areatype', '0', 'order by areaname');
if(mysql_num_rows($statesResult)>0){
	while($rowState = mysql_fetch_assoc($statesResult)){
		$popcontent .= '<p>'.$rowState['areaname']."</p>";
		$filtervaluesJsonArray[] = array('id' => $rowState['areastate'], 'name' => $rowState['areaname']);
	}
}
$filtervaluesJson = json_encode($filtervaluesJsonArray);
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
				<!-- form -->
				<input type="hidden" name="session_setter" value="local_govt_spending">
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_choose_state_govt_spending'])){ echo $lang['lbl_choose_state_govt_spending']; } ?> </span>&nbsp;&nbsp;
					</p>
					<div class="table-div">
						<div id="" class="wdthpercent38 left">
							<input value="" type="text" id="search_criteria_duplicates" name="us_states"/>&nbsp;&nbsp;&nbsp;
						</div>
						<div id="" class="pL20 left">
							<a href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontent; ?>');">See a list</a>
						</div>						
					</div>
				</div>

				<?php
				$resultSector = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat2');
				$allSectors = $dbDatabase->getAll($resultSector );
				?>
				<!-- form -->
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_choose_category_local_govt'])){ echo $lang['lbl_choose_category_local_govt']; } ?></span>&nbsp;&nbsp;<input type="checkbox" id="all_categories" name="allcat" value="all" />&nbsp;All</span>&nbsp;&nbsp;
					</p>
					<div class="table-div">

						<select size="4" name="cat2" id="cat2" class="required">
							<?php foreach($allSectors as $keySector => $detailSector){ 
								if(trim($detailSector['Cat2']) != ''){ ?>
									<option value="<?php echo $detailSector['Cat2']; ?>"><?php echo $detailSector['Cat2']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>	
						
						 <script type="text/javascript">
							$(document).ready(function() {	
								$("#all_categories").click(function(){
									if($(this).is(":checked")){
										$("#cat2").attr("disabled", "true");
										$("#cat2 option").removeAttr("selected");
									} else {
										$("#cat2").removeAttr("disabled");
									}
								});								
							});
						</script>

					</div>
				  </div>
				<!-- /form -->

				
				<!-- form -->
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_choose_govt_type_spending'])){ echo $lang['lbl_choose_govt_type_spending']; } ?></span>&nbsp;&nbsp;
					</p>
					<div class="table-div">
						<select size="4" name="sector" id="sector" class="required">
							<option value="county">Counties</option>
							<option value="city">Cities/Townships</option>
							<option value="district">Special Districts</option>
							<option value="School">Independent School Districts/Educ. Service Agency </option>
						</select>
					</div>
				  </div>
				<!-- /form -->

				<div class="pB10" id="citiesDataLoad">
				</div>

				<script type="text/javascript">
				$(document).ready(function() {	

					$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true,tokenLimit:1,
						
						onAdd: function (item) {
							var states = jQuery('#search_criteria_duplicates').val();
							var sector = jQuery('#sector').val();
							var all1   = jQuery('#cat2').val();

							if($("#all_categories").is(":checked")){
								var all2   = jQuery('#all_categories').val();
							} else {
								var all2   = 'none';
							}
							if(all1!='' && all1 != null){
								cat2 = all1; 
							} else { 
								cat2 = all2;
							}							
							
							if(states!='' && states != null && sector!='' && sector != null && cat2!='' && cat2 != null){
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: '<?php echo URL_SITE; ?>/statsus/govtfin/local_govt_spending_ajax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&states='+states+'&sector='+sector+"&cat2="+cat2,
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
							}
						},
						onDelete: function (item) {
							var states = jQuery('#search_criteria_duplicates').val();
							var sector = jQuery('#sector').val();
							var all1   = jQuery('#cat2').val();
							
							if($("#all_categories").is(":checked")){
								var all2   = jQuery('#all_categories').val();
							} else {
								var all2   = 'none';
							}
							if(all1!='' && all1 != null){
								cat2 = all1; 
							} else { 
								cat2 = all2;
							}	

							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();
							jQuery.ajax({
								url: '<?php echo URL_SITE; ?>/statsus/govtfin/local_govt_spending_ajax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states+'&sector='+sector+"&cat2="+cat2,
								success: function(dataresult){
									loader_unshow();
									var obj = jQuery.parseJSON(dataresult);
									if(obj.error == 0){
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
						 }					
					  });		
				   });
					
				   $('#sector').change(function(){
						var states = jQuery('#search_criteria_duplicates').val();					
						var sector = jQuery('#sector').val();
						var all1   = jQuery('#cat2').val();
						
						if($("#all_categories").is(":checked")){
							var all2   = jQuery('#all_categories').val();
						} else {
							var all2   = 'none';
						}
						if(all1!='' && all1 != null){
							cat2 = all1; 
						} else { 
							cat2 = all2;
						}	

						if(states!='' && states != null && sector!='' && sector != null && cat2!='' && cat2 != null){
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();
							jQuery.ajax({
								url: '<?php echo URL_SITE; ?>/statsus/govtfin/local_govt_spending_ajax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states+'&sector='+sector+"&cat2="+cat2,
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
						} 
					});

					$('#cat2').change(function(){
						var states = jQuery('#search_criteria_duplicates').val();
						var sector = jQuery('#sector').val();
						var all1   = jQuery('#cat2').val();
						
						if($("#all_categories").is(":checked")){
							var all2   = jQuery('#all_categories').val();
						} else {
							var all2   = 'none';
						}

						if(all1!='' && all1 != null){
							cat2 = all1; 
						} else { 
							cat2 = all2;
						}	
				
						if(states!='' && states != null && sector!='' && sector != null && cat2!='' && cat2 != null){
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();
							jQuery.ajax({
								url: '<?php echo URL_SITE; ?>/statsus/govtfin/local_govt_spending_ajax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states+'&sector='+sector+"&cat2="+cat2,
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
						} 
					});	
					
					$('#all_categories').change(function(){
						var states = jQuery('#search_criteria_duplicates').val();
						var sector = jQuery('#sector').val();
						var all1   = jQuery('#cat2').val();
						
						if($("#all_categories").is(":checked")){
							var all2   = jQuery('#all_categories').val();
						} else {
							var all2   = 'none';
						}
						if(all1!='' && all1 != null){
							cat2 = all1; 
						} else { 
							cat2 = all2;
						}	
				
						if(states!='' && states != null && sector!='' && sector != null && cat2!='' && cat2 != null){
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();
							jQuery.ajax({
								url: '<?php echo URL_SITE; ?>/statsus/govtfin/local_govt_spending_ajax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states+'&sector='+sector+"&cat2="+cat2,
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
						} 
					});				
				</script>

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
		