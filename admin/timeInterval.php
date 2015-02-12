<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['updatetimeinterval'])){
	
	
	$time_format	= (isset($_POST['time_format']))?trim(addslashes($_POST['time_format'])):'SY-EY';
	$columns		= (isset($_POST['columns']))?serialize($_POST['columns']):'';
	$embed_y		= (isset($_POST['embed_y']))?'Y':'N';
	$embed_m		= (isset($_POST['embed_m']))?'Y':'N';
	$embed_q		= (isset($_POST['embed_q']))?'Y':'N';

	$year_as		= (isset($_POST['year_as']))?$_POST['year_as']:'columns';
	$month_as		= (isset($_POST['month_as']))?$_POST['month_as']:'columns';
	$quater_as		= (isset($_POST['quater_as']))?$_POST['quater_as']:'columns';


	if($time_format != 'SM-SY-EM-EY'){
		$month_as = '';
	}

	if($time_format != 'SQ-SY-EQ-EY'){
		$quater_as = '';
	}
	

	$dbid			= (isset($_POST['dbid']))? $_POST['dbid']:'';

	if($dbid>0){
		$timeintervalid = $admin->updateTimeInterval($time_format, $embed_y, $embed_m, $embed_q, $year_as, $month_as, $quater_as, $columns, $dbid);
		header("location: timeInterval.php?tab=6&id=".base64_encode($dbid)."");
	}
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid, true);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		

		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		$allDbTables = $admin->getDatabaseTables($dbid);
		
		$tableIds = "";
		foreach($allDbTables as $tablekey => $tabledetail){
			$tableIds .= $tabledetail['table_name'].",";
		}
		
		$tableIds = substr($tableIds, 0, -1);

		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(empty($allDbTables))
		{
			$_SESSION['msgerror'] = "Please associate tables to '$dbname' ";
			header('location:'.URL_SITE.'/admin/database.php?tab=3&action=edit&id='.base64_encode($dbid));
		}
		if(isset($allDbTables[0]['table_name']))
		{
			$table = stripslashes($allDbTables[0]['table_name']);
		}
		else
		{
			 $table		= stripslashes($databaseDetail['table_name']);	// have to remove this When all data of DB tables goes to datasebae_tables
		}
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
}

if(isset($_GET['table']) && $_GET['table']!=''){		// if only database is selected.
	 $table = trim(stripslashes(base64_decode($_GET['table'])));	
}

$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$syear = $eyear = $smonth = $emonth = $tabletobeused = '';

if(isset($timeIntervalSettings['columns'])){
	$columns = unserialize($timeIntervalSettings['columns']);

	$syear	= (isset($columns['syear']))?$columns['syear']:'';
	$eyear	= (isset($columns['eyear']))?$columns['eyear']:'';
	$smonth	= (isset($columns['smonth']))?$columns['smonth']:'';
	$emonth = (isset($columns['emonth']))?$columns['emonth']:'';

	$squater = (isset($columns['squater']))?$columns['squater']:'';
	$equater = (isset($columns['equater']))?$columns['equater']:'';

	$tabletobeused = (isset($columns['tablename']))?$columns['tablename']:'';

}


?>


 <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Time Interval Settings for '".stripslashes($databaseDetail['db_name'])."'"; } ?> </legend>
					
					<?php include('formNavigation.php'); ?>


					<form method="post" action="" id="frmTimeInterval">
						<div class="pB10">
							<label class="pB10" style="display:block"><b>Time Interval Format</b></label>
							<input type="radio" class="required" value="SY-EY" name="time_format" <?php if(isset($timeIntervalSettings['time_format']) && $timeIntervalSettings['time_format']=='SY-EY'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Start Year - End Year&nbsp;<br/>
							<input type="radio" class="required" value="SM-SY-EM-EY" name="time_format" <?php if(isset($timeIntervalSettings['time_format']) && $timeIntervalSettings['time_format']=='SM-SY-EM-EY'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Start Month & Year - End Month & Year&nbsp;<br/>

							<input type="radio" class="required" value="SQ-SY-EQ-EY" name="time_format" <?php if(isset($timeIntervalSettings['time_format']) && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Start Quarter & Year - End Quarter & Year&nbsp;<br/>

							<label for="time_format" generated="true" class="error" style="display:none;">This field is required.</label>

							<script type='text/javascript'>
							// <![CDATA[
							jQuery(document).ready(function(){
								jQuery('input:radio[name="time_format"]').change(function(){
									//jQuery('#timeIntervalColumns').show();
									var time_format = jQuery(this).val();
									
									jQuery('#timeIntervalColumns').show();
									if(time_format == 'SM-SY-EM-EY'){
										jQuery('#monthformat').show();
										jQuery('#yearsas').show();
										jQuery('#monthsas').show();
										jQuery('#quatersas').hide();
										jQuery('#quaterformat').hide();
										
									} else if(time_format == 'SQ-SY-EQ-EY'){
										jQuery('#monthformat').hide();
										jQuery('#yearsas').show();
										jQuery('#monthsas').hide();
										jQuery('#quatersas').show();
										jQuery('#quaterformat').show();
									} else {
										
										jQuery('#quaterformat').hide();
										jQuery('#monthformat').hide();
										jQuery('#yearsas').show();
										jQuery('#monthsas').hide();
										jQuery('#quatersas').hide();

									}
								});
							});

							// ]]>
							</script>
						</div>
						
						<div  id="yearsas" <?php if(isset($timeIntervalSettings['time_format']) && ($timeIntervalSettings['time_format']=='SM-SY-EM-EY' || $timeIntervalSettings['time_format']=='SY-EY' || $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY')){ ?>  style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> >
							<div class="pB10">
								<label class="pB10" style="display:block"><b>Years As: <em>(Check your table to see how years have been listed)</em></b></label>
								<input type="radio" class="required" id="yearColumn" value="columns" name="year_as" <?php if(isset($timeIntervalSettings['years_as']) && $timeIntervalSettings['years_as']=='columns'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Columns&nbsp;<br/>
								<input type="radio" class="required" value="rows" name="year_as" <?php if(isset($timeIntervalSettings['years_as']) && $timeIntervalSettings['years_as']=='rows'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Rows&nbsp;<br/>

								<label for="year_as" generated="true" class="error" style="display:none;">This field is required.</label>

								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('input:radio[name=year_as]').change(function(){
										if(jQuery(this).val() == 'columns'){
											jQuery('#yearsAsColumns').show();
											jQuery('#yearsAsRows').hide();
										} else {
											jQuery('#yearsAsRows').show();
											jQuery('#yearsAsColumns').hide();
										}
									});
								});
								</script>
							</div>

							<div class="pB10" <?php if(isset($timeIntervalSettings['years_as']) && $timeIntervalSettings['years_as']=='columns'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="yearsAsColumns">
								<label class="pB10" style="display:block"><b>Table Whose Columns To Be Used As Years</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									$checked='';
									if(isset($columns) && isset($columns['yearsascolumns']) && $columns['yearsascolumns'] == $tabledetail['table_name']){ 
										$checked = 'checked="checked"';
									}
								?>
									<input type="radio" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[yearsascolumns]" <?php echo $checked; ?>/>&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;
								<?php
								}
								?>
								<br/>
								<label for="columns[yearsascolumns]" generated="true" class="error" style="display:none;">This field is required.</label>
							</div>

							<div class="pB10" <?php if(isset($timeIntervalSettings['years_as']) && $timeIntervalSettings['years_as']=='rows'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="yearsAsRows" >

								<label class="pB10" style="display:block"><b>Table Whose Column Values To Be Used As Years</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									
									$checked = '';
									if(isset($columns) && isset($columns['yearsasrows']) && is_array($columns['yearsasrows']) && isset($columns['yearsasrows']['columns']) && isset($columns['yearsasrows']['columns'][$tabledetail['table_name']])){ 
										$checked =  "checked='checked'"; 
									}
								?>
									<input id="tableYears_<?php echo $tablekey;?>" type="radio" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[yearsasrows]" <?php echo $checked; ?>/>&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;
									<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery('#tableYears_<?php echo $tablekey;?>').click(function(){
											jQuery('.tablesYears').hide();
											jQuery('#tableYears_columns<?php echo $tablekey;?>').show();
										});
									});
									</script>
								<?php
								}
								?>
								<br/>
								
								<div>
									<?php
									foreach($allDbTables as $tablekey => $tabledetail){
										$display='none';
										if(isset($columns) && isset($columns['yearsasrows']) && is_array($columns['yearsasrows']) && isset($columns['yearsasrows']['columns']) && isset($columns['yearsasrows']['columns'][$tabledetail['table_name']])){ 
											$display='block';
										}

										$array_clm_res = $admin->showColumns($tabledetail['table_name']);
										echo '<div style="display:'.$display.';" id="tableYears_columns'.$tablekey.'" class="tablesYears">
										<label class="pT10 pB10" style="display:block"><b>Select '.$tabledetail['table_name'].' Column</b></label>
										';
										while($data = mysql_fetch_assoc($array_clm_res))
										{
											
											if($data['Extra']!='auto_increment')
											{
												$checked = '';
												
												if(isset($columns) && isset($columns['yearsasrows']) && is_array($columns['yearsasrows']) && isset($columns['yearsasrows']['columns']) && isset($columns['yearsasrows']['columns'][$tabledetail['table_name']]) && $columns['yearsasrows']['columns'][$tabledetail['table_name']] == $data['Field']){ 
													$checked = 'checked="checked"';
												}

												echo "<input class='' type='radio' value='".$data['Field']."' name='columns[yearsasrows][columns][".$tabledetail['table_name']."]' ".$checked.">&nbsp;&nbsp;<label class='mL10'>".$data['Field']."</label>&nbsp;&nbsp;";
	
											}
										}
										echo '</div>';
									 } 
									 ?>

									 <div style="clear:both">&nbsp;</div>
								</div>
							</div>
						</div>





						<div <?php if(isset($timeIntervalSettings['time_format']) && ($timeIntervalSettings['time_format']=='SM-SY-EM-EY')){ ?>  style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="monthsas">
							<div class="pB10">
								<label class="pB10" style="display:block"><b>Months As:<em>(Check your table to see how months have been listed)</em></b></label>
								<input type="radio" class="required" value="columns" name="month_as" <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['months_as']=='columns'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Columns&nbsp;<br/>
								<input type="radio" class="required" value="rows" name="month_as" <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['months_as']=='rows'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Rows&nbsp;<br/>

								<label for="year_as" generated="true" class="error" style="display:none;">This field is required.</label>

								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('input:radio[name=month_as]').change(function(){
										if(jQuery(this).val() == 'columns'){
											jQuery('#monthsAsColumns').show();
											jQuery('#monthsAsRows').hide();
										} else {
											jQuery('#monthsAsRows').show();
											jQuery('#monthsAsColumns').hide();
										}
									});
								});
								</script>

							</div>

							<div class="pB10"  <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['months_as']=='columns'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="monthsAsColumns">

								<label class="pB10" style="display:block"><b>Table Whose Columns To Be Used As Months</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									$checked='';
									if(isset($columns) && isset($columns['monthsascolumns']) && $columns['monthsascolumns'] == $tabledetail['table_name'] && $timeIntervalSettings['time_format']=='SM-SY-EM-EY'){ 
										$checked = 'checked="checked"';
									}

								?>
									<input type="radio" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[monthsascolumns]" <?php echo $checked; ?> />&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;
								<?php
								}
								?>
								<br/>
								<label for="columns[monthsascolumns]" generated="true" class="error" style="display:none;">This field is required.</label>
							</div>

							<div class="pB10" <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['months_as']=='rows'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="monthsAsRows">
								<label class="pB10" style="display:block"><b>Table Whose Column Values To Be Used As Months</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									$checked='';
									if(isset($columns) && isset($columns['monthsasrows']) && is_array($columns['monthsasrows']) && isset($columns['monthsasrows']['columns']) && isset($columns['monthsasrows']['columns'][$tabledetail['table_name']]) && $timeIntervalSettings['time_format']=='SM-SY-EM-EY'){ 
										$checked='checked="checked"';
									}
								?>
									<input type="radio" id="tableMonths_<?php echo $tablekey;?>" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[monthsasrows]" <?php echo $checked; ?> />&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;

									<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery('#tableMonths_<?php echo $tablekey;?>').click(function(){
											jQuery('.tableMonths').hide();
											jQuery('#tableMonths_columns<?php echo $tablekey;?>').show();
										});
									});
									</script>

								<?php
								}
								?>
								<br/>

								<div>
									<?php
									foreach($allDbTables as $tablekey => $tabledetail){
										$display='none';
										if(isset($columns) && isset($columns['monthsasrows']) && is_array($columns['monthsasrows']) && isset($columns['monthsasrows']['columns']) && isset($columns['monthsasrows']['columns'][$tabledetail['table_name']])){ 
											$display='block';
										}

										$array_clm_res = $admin->showColumns($tabledetail['table_name']);
										echo '<div style="display:'.$display.';" id="tableMonths_columns'.$tablekey.'" class="tableMonths">
										<label class="pT10 pB10" style="display:block"><b>Select '.$tabledetail['table_name'].' Column</b></label>
										';
										while($data = mysql_fetch_assoc($array_clm_res))
										{
											
											if($data['Extra']!='auto_increment')
											{
												$checked = '';
												
												if(isset($columns) && isset($columns['monthsasrows']) && is_array($columns['monthsasrows']) && isset($columns['monthsasrows']['columns']) && isset($columns['monthsasrows']['columns'][$tabledetail['table_name']]) && $columns['monthsasrows']['columns'][$tabledetail['table_name']] == $data['Field'] && $timeIntervalSettings['time_format']=='SM-SY-EM-EY'){ 
													$checked = 'checked="checked"';
												}
												echo "<input class='' type='radio' value='".$data['Field']."' name='columns[monthsasrows][columns][".$tabledetail['table_name']."]' ".$checked.">&nbsp;&nbsp;<label class='mL10'>".$data['Field']."</label>&nbsp;&nbsp;";
	
											}
										}
										echo '</div>';
									 } 
									 ?>

									 <div style="clear:both">&nbsp;</div>
								</div>

							</div>

						</div>






						<div <?php if(isset($timeIntervalSettings['time_format']) && ($timeIntervalSettings['time_format']=='SQ-SY-EQ-EY')){ ?>  style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="quatersas">
							<div class="pB10">
								<label class="pB10" style="display:block"><b>Quarter As:<em>(Check your table to see how quarters have been listed)</em></b></label>
								<input type="radio" class="required" value="columns" name="quater_as" <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['quaters_as']=='columns' && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Columns&nbsp;<br/>
								<input type="radio" class="required" value="rows" name="quater_as" <?php if(isset($timeIntervalSettings['months_as']) && $timeIntervalSettings['quaters_as']=='rows' && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ echo "checked"; } ?>>&nbsp;&nbsp;&nbsp;Rows&nbsp;<br/>

								<label for="year_as" generated="true" class="error" style="display:none;">This field is required.</label>

								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('input:radio[name=quater_as]').change(function(){
										if(jQuery(this).val() == 'columns'){
											jQuery('#quatersAsColumns').show();
											jQuery('#quatersAsRows').hide();
										} else {
											jQuery('#quatersAsRows').show();
											jQuery('#quatersAsColumns').hide();
										}
									});
								});
								</script>

							</div>

							<div class="pB10"  <?php if(isset($timeIntervalSettings['quaters_as']) && $timeIntervalSettings['quaters_as']=='columns'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="quatersAsColumns">

								<label class="pB10" style="display:block"><b>Table Whose Columns To Be Used As Quarters</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									$checked='';
									if(isset($columns) && isset($columns['quatersascolumns']) && $columns['quatersascolumns'] == $tabledetail['table_name'] && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ 
										$checked = 'checked="checked"';
									}

								?>
									<input type="radio" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[quatersascolumns]" <?php echo $checked; ?> />&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;
								<?php
								}
								?>
								<br/>
								<label for="columns[quatersascolumns]" generated="true" class="error" style="display:none;">This field is required.</label>
							</div>

							<div class="pB10" <?php if(isset($timeIntervalSettings['quaters_as']) && $timeIntervalSettings['quaters_as']=='rows'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="quatersAsRows">
								<label class="pB10" style="display:block"><b>Table Whose Column Values To Be Used As Quarters</b></label>
								<?php
								foreach($allDbTables as $tablekey => $tabledetail){
									$checked='';
									if(isset($columns) && isset($columns['quatersasrows']) && is_array($columns['quatersasrows']) && isset($columns['quatersasrows']['columns']) && isset($columns['quatersasrows']['columns'][$tabledetail['table_name']]) && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ 
										$checked='checked="checked"';
									}
								?>
									<input type="radio" id="tableQuaters_<?php echo $tablekey;?>" class="required" value="<?php echo $tabledetail['table_name']; ?>" name="columns[quatersasrows]" <?php echo $checked; ?> />&nbsp;&nbsp;<?php echo $tabledetail['table_name']; ?>&nbsp;&nbsp;

									<script type="text/javascript">
									jQuery(document).ready(function(){
										jQuery('#tableQuaters_<?php echo $tablekey;?>').click(function(){
											jQuery('.tableQuaters').hide();
											jQuery('#tableQuaters_columns<?php echo $tablekey;?>').show();
										});
									});
									</script>

								<?php
								}
								?>
								<br/>

								<div>
									<?php
									foreach($allDbTables as $tablekey => $tabledetail){
										$display='none';
										if(isset($columns) && isset($columns['quatersasrows']) && is_array($columns['quatersasrows']) && isset($columns['quatersasrows']['columns']) && isset($columns['quatersasrows']['columns'][$tabledetail['table_name']])){ 
											$display='block';
										}

										$array_clm_res = $admin->showColumns($tabledetail['table_name']);
										echo '<div style="display:'.$display.';" id="tableQuaters_columns'.$tablekey.'" class="tableQuaters">
										<label class="pT10 pB10" style="display:block"><b>Select '.$tabledetail['table_name'].' Column</b></label>
										';
										while($data = mysql_fetch_assoc($array_clm_res))
										{
											
											if($data['Extra']!='auto_increment')
											{
												$checked = '';
												
												if(isset($columns) && isset($columns['quatersasrows']) && is_array($columns['quatersasrows']) && isset($columns['quatersasrows']['columns']) && isset($columns['quatersasrows']['columns'][$tabledetail['table_name']]) && $columns['quatersasrows']['columns'][$tabledetail['table_name']] == $data['Field'] && $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY'){ 
													$checked = 'checked="checked"';
												}
												echo "<input class='' type='radio' value='".$data['Field']."' name='columns[quatersasrows][columns][".$tabledetail['table_name']."]' ".$checked.">&nbsp;&nbsp;<label class='mL10'>".$data['Field']."</label>&nbsp;&nbsp;";
	
											}
										}
										echo '</div>';
									 } 
									 ?>

									 <div style="clear:both">&nbsp;</div>
								</div>

							</div>

						</div>
						

						

						<div <?php if(isset($timeIntervalSettings['time_format']) && ($timeIntervalSettings['time_format']=='SM-SY-EM-EY' || $timeIntervalSettings['time_format']=='SY-EY' || $timeIntervalSettings['time_format']=='SQ-SY-EQ-EY') ){ ?>  style="display:block;" <?php } else { ?> style="display:none;" <?php } ?> id="timeIntervalColumns" class="pB10 " style="display:none;">
							<div id="yearformat">
								<label class="pB10 " style="display:block" ><b>Start Year</b></label>
								<input type="text" class="digits yearchange" name="columns[syear]" value="<?php echo $syear; ?>" id="syear"/><br/>
								<label class="pB10 " style="display:block" ><b>End Year</b></label>
								<input type="text" class="digits yearchange" name="columns[eyear]" value="<?php echo $eyear; ?>" id="eyear"/><br/>
							</div>

							<div id="monthformat" <?php if(isset($timeIntervalSettings['time_format']) && $timeIntervalSettings['time_format'] != 'SM-SY-EM-EY'){ ?> style="display:none;" <?php } ?> >
								<label class="pB10 " style="display:block" ><b>Start month</b></label>

								<select name="columns[smonth]" class="required">
									<?php for($i=1;$i<=12;$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i==1 || $i == $smonth){ echo "selected"; } ?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
								
								<label class="pB10 " style="display:block" ><b>End Month</b></label>
								<select name="columns[emonth]" class="required">
									<?php for($i=1;$i<=12;$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i==12 || $i == $emonth){ echo "selected"; } ?>><?php echo $i; ?></option>
									<?php } ?>
								</select><br/>

							</div>
							
							<div id="quaterformat" <?php if(isset($timeIntervalSettings['time_format']) && $timeIntervalSettings['time_format'] != 'SQ-SY-EQ-EY'){ ?> style="display:none;" <?php } ?> >
								<label class="pB10 " style="display:block" ><b>Start Quarter</b></label>

								<select name="columns[squater]" class="required">
									<?php for($i=1;$i<=4;$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i==1 || $i == $squater){ echo "selected"; } ?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
								
								<label class="pB10 " style="display:block" ><b>End Quarter</b></label>
								<select name="columns[equater]" class="required">
									<?php for($i=1;$i<=4;$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i==4 || $i == $equater){ echo "selected"; } ?>><?php echo $i; ?></option>
									<?php } ?>
								</select><br/>

							</div>

						</div>	
						

						<script type='text/javascript'>
						// <![CDATA[
						jQuery(document).ready(function(){

							jQuery('.yearchange').change(function(){
								var syear = jQuery('#syear').val();
								var eyear = jQuery('#eyear').val();
								
								if(parseInt(eyear) < parseInt(syear)){
									alert("Start year should be less than end year");
									var eyear = jQuery('#eyear').val('');
								}
							});
						});

						// ]]>
						</script>
			

						<div class="pB10">
							<label class="pB10" ><b>Embed Y With Column Name (Year):&nbsp;&nbsp;</b></label>
							<input type="checkbox" value="Y" name="embed_y" <?php if(isset($timeIntervalSettings['embed_y']) && $timeIntervalSettings['embed_y']=='Y'){ echo "checked"; } ?>>
						</div>

						<div class="pB10">
							<label class="pB10" ><b>Embed M With Column Name (Month):&nbsp;&nbsp;</b></label>
							<input type="checkbox" value="M" name="embed_m" <?php if(isset($timeIntervalSettings['embed_m']) && $timeIntervalSettings['embed_m']=='Y'){ echo "checked"; } ?>>
						</div>

						<div class="pB10">
							<label class="pB10" ><b>Embed Q With Column Name (Quarter):&nbsp;&nbsp;</b></label>
							<input type="checkbox" value="Y" name="embed_q" <?php if(isset($timeIntervalSettings['embed_q']) && $timeIntervalSettings['embed_q']=='Y'){ echo "checked"; } ?>>
						</div>

						<div class="submit1 submitbtn-div">
							<label for="submit" class="left">
							<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
								<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
								<input type="submit" value="Submit" name="updatetimeinterval" class="submitbtn" >
								<?php } ?>
							</label>
							<label for="reset" class="right">
								<input type="reset" id="reset" class="submitbtn">
							</label>
						</div>

					</form>


				</fieldset>
			</div>

		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


