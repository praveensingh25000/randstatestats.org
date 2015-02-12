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

if(isset($_GET['delete']) && $_GET['delete']!=''){
	$id	= (isset($_GET['delete']))? $_GET['delete']:'';

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$return = $admin->deleteJoinConditions($id);
	header("location: joinConditions.php?tab=3&id=".base64_encode($dbid)."");
}

if(isset($_POST['updatejoinconditions'])){
	
	$primary_table			= (isset($_POST['primary_table']))?trim($_POST['primary_table']):'';
	$foreign_table			= (isset($_POST['foreign_table']))?trim($_POST['foreign_table']):'';
	$primary_table_column	= (isset($_POST['primary_table_column']))?trim($_POST['primary_table_column']):'';
	$foreign_table_column	= (isset($_POST['foreign_table_column']))?trim($_POST['foreign_table_column']):'';
	$dbid					= (isset($_POST['dbid']))? $_POST['dbid']:'';

	$joinconditionid = $admin->insertJoinConditions($primary_table, $primary_table_column, $foreign_table, $foreign_table_column, $dbid);

	header("location: joinConditions.php?tab=3&id=".base64_encode($dbid)."");

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


$joinConditions = $admin->getAllJoinConditions($dbid);
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
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Join Conditions for '".stripslashes($databaseDetail['db_name'])."'"; } ?> </legend>
					
					
					<?php include("formNavigation.php"); ?>


					<?php if(isset($joinConditions) && count($joinConditions) >0){ ?>
					
					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
						<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Join Conditions</legend>
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
						<tbody>
							<tr>
								<th bgcolor="#eeeeee">Primary Table</th>
								<th bgcolor="#eeeeee">Primary Column</th>
								<th bgcolor="#eeeeee">Dependent Table</th>
								<th bgcolor="#eeeeee">Dependent Column</th>
								<th bgcolor="#eeeeee">Actions</th>
							</tr>
							<?php foreach($joinConditions as $keyJoin => $joinDetail){ ?>
							<tr>
								<td valign="top"><?php echo $joinDetail['primary_table']; ?></td>
								<td valign="top"><?php echo $joinDetail['primary_table_column']; ?></td>
								<td valign="top"><?php echo $joinDetail['foreign_table']; ?></td>
								<td valign="top"><?php echo $joinDetail['foreign_table_column']; ?></td>
								<td valign="top"><a href="joinConditions.php?id=<?php echo base64_encode($dbid); ?>&delete=<?php echo $joinDetail['id']; ?>" onclick="javascript: return confirm('Are you sure you want to delete it');">Delete</a></td>
							</tr>
							<?php } ?>

						</tbody>
						</table>
					</fieldset><br/>
					<?php } ?>
					
					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Add Join Condition</legend>
						<form method="post" action="" id="frmTimeInterval">
							<div class="pB30">
								<label class="pB10" style="display:block"><b>Select Primary & Dependent Key Tables</b></label>
								<div class="clear">
									<div class="left pL10">
										<label class="pB10" style="display:block"><b>Primary Table</b></label>
										<select name="primary_table" id="primary_table" class="required">
											<option  value="">-- Select Primary Table --</option>
											<?php foreach($allDbTables as $tablekey => $tableDetail){ ?>
											<option  value="<?php echo $tableDetail['table_name']; ?>"><?php echo $tableDetail['table_name']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="left pL10">
										<label class="pB10" style="display:block"><b>Dependent Table</b></label>
										<select name="foreign_table" id="foreign_table" class="required">
											<option  value="">-- Select Dependent Table --</option>
											<?php foreach($allDbTables as $tablekey => $tableDetail){ ?>
											<option  value="<?php echo $tableDetail['table_name']; ?>"><?php echo $tableDetail['table_name']; ?></option>
											<?php } ?>
										</select>
									</div>

									<div class="left pL10">
										<label class="" style="display:block">&nbsp;</label>
										<input type="button" id="continueJoinAdd" value="Continue" class="submitbtn" >
									</div>

									<script type='text/javascript'>
									// <![CDATA[
									jQuery(document).ready(function(){

										jQuery('#primary_table').click(function(){
											jQuery('#joinConditionsColumns').hide();
											jQuery('#submitButtons').hide();
											jQuery('#joinConditionsColumns').html('');
										});
										jQuery('#foreign_table').click(function(){
											jQuery('#joinConditionsColumns').hide();
											jQuery('#submitButtons').hide();
											jQuery('#joinConditionsColumns').html('');
										});

										jQuery('#continueJoinAdd').click(function(){
											
											var primary_table = jQuery('#primary_table').val();
											var foreign_table = jQuery('#foreign_table').val();
											if(primary_table != '' &&  foreign_table != ''){

												jQuery('#joinConditionsColumns').show();
												jQuery.ajax({
													url: "getJoinTableColumns.php",
													data: "primary_table="+primary_table+"&foreign_table="+foreign_table+"&dbid=<?php echo $dbid; ?>",
													success: function(returnedhtml){
														jQuery('#joinConditionsColumns').html(returnedhtml);
														jQuery('#submitButtons').show();
													}
												});
											} else {
												alert("Please select both tables first");
											}
										});
									});

									// ]]>
									</script>

								</div>
								<div class="clear"></div>
							</div>

							<div id="joinConditionsColumns" style="display:none;">Loading....
							</div>

							<div class="submit1 submitbtn-div" id="submitButtons" style="display:none;">
								<label for="submit" class="left">
								<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
									<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
									<input type="submit" value="Submit" name="updatejoinconditions" class="submitbtn" >
									<?php } ?>
								</label>
								
							</div>

						</form>
					</fieldset>


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


