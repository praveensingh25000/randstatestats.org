<?php
/******************************************
* @Modified on Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site: http://statestats.rand.org/stats/economics/avgwagenaicsUS_qtr.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user  = new user();

$tablesname				= "avgwage1US";
$tablesnamecat			= "avgwage1US_cats";
$tablesnamearea			= "states";
$tablesnamecountyarea	= "fips";

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['avgwageUS'] = $_POST;

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
			header('location: avgwageUSData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
			header('location: avgwageUSData.php');
			exit;
		}
	}
}

$dbid = 97;
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

$statetoname_res  = $admin->getTableDataUniversal($tablesnamearea);
$stateToName	  = $dbDatabase->getAll($statetoname_res);

foreach($stateToName as $key => $value){
	if($value['statename']!=NULL){
	$stateToNamefiltervaluesJsonArray[] = array('id' => $value['state'], 'name' => $value['statename']);
	}
}
$filtervaluesJsonState = json_encode($stateToNamefiltervaluesJsonArray);
?>

<!-- container -->
<section id="container">
	<section id="inner-content">
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
				<!-- form -->
				<input type="hidden" name="session_setter" value="avgwagenaicsUS">
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_enter_area_state'])){ echo $lang['lbl_enter_area_state']; } ?></span>&nbsp;&nbsp;
					</p>
					<div class="table-div">	

						<p><input id="us_states" class="required" type="input"  name="us_states"/></p>

						<script type="text/javascript">
						$(document).ready(function() {							
							$("#us_states").tokenInput(<?php echo $filtervaluesJsonState ; ?>, { 
								preventDuplicates: true,
								onAdd: function (item) {
										var ownership = jQuery('input[name="Cat2[]"]:checked').length;
										var area      = jQuery('#us_states').val();
										var Sector = jQuery('input[name=Sector]:checked').val();
										if(area == ""){
											//alert('Please select State.');
											return false;
										} else if(ownership == "0"){
											//alert('Please select Ownership.');
											return false;
										} else if(Sector == undefined){
											//alert('Please select industry sector.');
											return false;
										}else {
											
											var owner = '';
											jQuery("input:checkbox[name=Cat2[]]:checked").each(function(){
												var selval = jQuery(this).val();
												owner = owner+','+ selval;
											});
											loader_show();
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();

											jQuery.ajax({								
												type: 'post',
												data: jQuery("#frmPost").serialize(),
												url: URL_SITE+'/statsus/economics/avgwageUSAjax.php?dbid=<?php echo $dbid; ?>&Sector='+Sector+"&owner="+owner,
												
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
								},
								onDelete: function (item) {
									var ownership = jQuery('input[name="Cat2[]"]:checked').length;
									var area      = jQuery('#us_states').val();
									var Sector = jQuery('input[name=Sector]:checked').val();
									
									/*if(area == ""){
										//alert('Please select State.');
										return false;
									} else if(ownership == "0"){
										//alert('Please select Ownership.');
										return false;
									} else if(Sector == undefined){
										//alert('Please select industry sector.');
										return false;
									}else {*/
										
										var owner = '';
										jQuery("input:checkbox[name=Cat2[]]:checked").each(function(){
											var selval = jQuery(this).val();
											owner = owner+','+ selval;
										});
										loader_show();
										jQuery('#timePeriod').hide();
										jQuery('#submitButtons').hide();

										jQuery.ajax({								
											type: 'post',
											data: jQuery("#frmPost").serialize(),
											url: URL_SITE+'/statsus/economics/avgwageUSAjax.php?dbid=<?php echo $dbid; ?>&Sector='+Sector+"&owner="+owner,
											
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
									//}
								}
							});										
						});							
						</script>	
			
					</div>
				  </div>
				<!-- /form -->

				<?php
				global $dbDatabase;
				$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat2');
				
				if(mysql_num_rows($resultSectors)>0){
				?>
				<div class="form-div">
					<p>
						<span class="choose"><?php if(isset($lang['lbl_choose_type_ownership'])){ echo $lang['lbl_choose_type_ownership']; } ?></span>
					</p>
					<div class="table-div">
						<?php while($detailRow = mysql_fetch_assoc($resultSectors)){ ?>
						<input id="" class="checkbox_check required ownership" type="checkbox" value="<?=$detailRow['Cat2']?>" name="Cat2[]"/>&nbsp;<?=$detailRow['Cat2']?><br/>
						<?php } ?>
					</div>
				  </div>
				 <?php }?>

				 <div class="form-div">
					<p>
						<span class="choose"><?php if(isset($lang['lbl_choose_type_sector'])){ echo $lang['lbl_choose_type_sector']; } ?></span>
					</p>
					<div class=""><table width="100%"><tr>
						<?php 
						$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat1');
						$sectorsAll = $dbDatabase->getAll($resultSectors);	
						if(count($sectorsAll)>0){
							$sectorsAll = array_chunk($sectorsAll,round(count($sectorsAll)/2));
							foreach($sectorsAll as $keyOwn => $sectos){
						?>
							<td>
						<?php
							foreach($sectos as $keySect => $category){ if(strtolower(trim($category['Cat1'])) != 'unclassified'){ ?>
							<input id="" class="search_criteria_sectors required" type="radio" value="<?=$category['Cat1']?>" name="Sector" />&nbsp;<?=$category['Cat1']?><br/>
							<?php } } ?>
							 </td>
						<?php } }?>
						</tr></table>
					</div>
				</div>

				<?php unset($sectorsAll); ?>

				<div class="pB10" id="citiesDataLoad"></div>

				<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery(".ownership").click(function() {
					
						var ownership = jQuery('input[name="Cat2[]"]:checked').length;
						var area      = jQuery('#us_states').val();
						var Sector = jQuery('input[name=Sector]:checked').val();
						if(area == ""){
							//alert('Please select State.');
						} else if(ownership == "0"){
							//alert('Please select Ownership.');
						} else if(Sector == undefined){
							//alert('Please select industry sector.');
						}else {
							
							var owner = '';
							jQuery("input:checkbox[name=Cat2[]]:checked").each(function(){
								var selval = jQuery(this).val();
								owner = owner+','+ selval;
							});
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();

							jQuery.ajax({								
								type: 'post',
								data: jQuery("#frmPost").serialize(),
								url: URL_SITE+'/statsus/economics/avgwageUSAjax.php?dbid=<?php echo $dbid; ?>&Sector='+Sector+"&owner="+owner,
								
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

					jQuery(".search_criteria_sectors").click(function() {
					
						var ownership = jQuery('input[name="Cat2[]"]:checked').length;
						var area      = jQuery('#us_states').val();
						var Sector = jQuery('input[name=Sector]:checked').val();
					
						if(area == ""){
							//alert('Please select State.');
							return false;
						} else if(ownership == "0"){
							//alert('Please select Ownership.');
							return false;
						}  else if(Sector == ''){
							//alert('Please select industry sector.');
							return false;
						}else {
							
							var owner = '';
							jQuery("input:checkbox[name=Cat2[]]:checked").each(function(){
								var selval = jQuery(this).val();
								owner = owner+','+ selval;
							});
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();

							jQuery.ajax({								
								type: 'post',
								data: jQuery("#frmPost").serialize(),
								url: URL_SITE+'/statsus/economics/avgwageUSAjax.php?dbid=<?php echo $dbid; ?>&Sector='+Sector+"&owner="+owner,
								
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

	<?php 
	if(mysql_num_rows($related_DB)>0){ 
	?>
	<section id="inner-sidebar">
	   <?php require_once $basedir."/relatedForms.php"; ?>
   </section>
   <?php } ?>

</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>
		