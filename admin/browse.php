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

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid,true);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);		

		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		$allDbTables = $admin->getDatabaseTables($dbid);

		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(empty($allDbTables))
		{
			$_SESSION['msgerror'] = "Please associate tables to '$dbname' ";
			header('location:'.URL_SITE.'/admin/associatedTables.php?tab=3&id='.base64_encode($dbid));
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

if(isset($_POST['updatedata']) && count($_POST['columns'])>0){
	$tablecolumn = trim($_POST['tablecolumn']);
	$table = stripslashes($_POST['tableName']);
	$tablecolumnvalue = trim($_POST['tablecolumnvalue']);
	$where = "`".$tablecolumn."` = '".$tablecolumnvalue."'";
	$columns = $_POST['columns'];
	$return = $admin->updateColumnsUniversal($table, $columns, $where);
	if($return){
		$_SESSION['msgsuccess'] = 'Data updated successfully';
	} else {
		$_SESSION['msgerror'] = 'Could not update data due to some technical reasons';
	}
	header('location: browse.php?tab=4&id='.base64_encode($dbid).'&table='.base64_encode($table));
	exit;
}

if(isset($_POST['updaterowcoldata']) && isset($_POST['submitactionType'])) {

	$submitactionType = trim($_POST['submitactionType']);
	$table = stripslashes($_POST['tablename']);
	$dbid = $_POST['dbid'];

	switch($submitactionType){
		case 'addRow':
			$return = $admin->addrowUniversal($table);			
			break;
		case 'addCol':
			$return = $admin->addColUniversal($table);		
			break;			
		default:
			break;
	}
	if($submitactionType == 'addRow') {	
		if($return > 0){
			$_SESSION['msgsuccess'] = 'Row has been added successfully';
		} else {
			$_SESSION['msgerror'] = 'Could not added Row due to some technical reasons';
		}
	}
	if($submitactionType == 'addCol') {	
		if($return){
			$_SESSION['msgsuccess'] = 'Coloum has been added successfully';
		} else {
			$_SESSION['msgerror'] = 'Could not added Coloum due to some technical reasons';
		}
	}
	header('location: browse.php?tab=4&id='.base64_encode($dbid).'&table='.base64_encode($table));
	exit;
}

//echo "<pre>";print_r($_POST);echo "</pre>";die;


if(isset($_GET['action'])){
	$action = trim($_GET['action']);
	if(isset($_GET['tablecolumn'])) $tablecolumn = trim($_GET['tablecolumn']);
	if(isset($_GET['tablename'])) $table = stripslashes(base64_decode($_GET['tablename']));
	if(isset($_GET['value'])) $tablecolumnvalue = trim($_GET['value']);

	switch($action){
		case 'delete':
			$return = $admin->deleteUniversal($table, $tablecolumn, $tablecolumnvalue);
			if($return){
				$_SESSION['msgsuccess'] = 'Selected row deleted successfully';
			} else {
				$_SESSION['msgerror'] = 'Could not delete due some technical error';
			}
			header('location: browse.php?tab=4&id='.base64_encode($dbid).'&table='.base64_encode($table));
			break;
		case 'edit':
			$table = stripslashes(base64_decode($_GET['tablename']));
			$rowDetail = $admin->getRowUniversal($table, $tablecolumn, $tablecolumnvalue);
			break;
		case 'addRow':
			$table = stripslashes(base64_decode($_GET['tablename']));
			$rowDetail_res = $admin->showColumns($table);
			$rowDetail	   = $dbDatabase->getAll($rowDetail_res);			
			break;		
		default:
			break;
	}
}

$browseSQL = $admin->getDataSQL($table); 
$browseResult = $dbDatabase->run_query($browseSQL);


//$tablename = trim($databaseDetail['table_name']);
$tablesResultColumn = $admin->showColumns($table);
$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
$tableColumns = $dbDatabase->getAll($tablesResultColumn);
$_GET['tab'] = 4;
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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Edit Database '".$databaseDetail['db_name']."'"; } else { echo "Add Database"; } ?> </legend>
				
				<?php include("formNavigation.php"); ?>

				<?php if(isset($allDbTables) and !empty($allDbTables)) { ?>
				<div id="" class="">
					<?php
					foreach($allDbTables as $tables_all){?>
						<a class="<?php if((isset($_GET['table']) && base64_encode(stripslashes($tables_all['table_name']))==$_GET['table']) || ($table==stripslashes($tables_all['table_name']))) { ?> active <?php } ?>" href="<?php echo URL_SITE; ?>/admin/browse.php?tab=4&id=<?php echo base64_encode($dbid);?>&table=<?php echo base64_encode(stripslashes($tables_all['table_name']));?>"><?php echo ucfirst(stripslashes($tables_all['table_name']));?></a>

						<?php if((isset($_GET['table']) && base64_encode(stripslashes($tables_all['table_name']))==$_GET['table']) || ($table==stripslashes($tables_all['table_name']))) { ?>
						<span class="font10 pL5">
							<a <?php if(isset($_GET['action']) && ($_GET['action'] == 'addRow')) { ?> class="sub" <?php } ?> href="<?php echo URL_SITE; ?>/admin/browse.php?tab=4&id=<?php echo base64_encode($dbid);?>&action=addRow&tablename=<?php echo base64_encode(stripslashes($tables_all['table_name']));?>"> Add Row </a>&nbsp;
							<a <?php if(isset($_GET['action']) && ($_GET['action'] == 'addCol')) { ?> class="sub" <?php } ?> href="<?php echo URL_SITE; ?>/admin/browse.php?tab=4&id=<?php echo base64_encode($dbid);?>&action=addCol&tablename=<?php echo base64_encode(stripslashes($tables_all['table_name']));?>"> Add Coloum </a>
						</span>
						<?php } ?>
						&nbsp;|&nbsp;
					<?php } ?>					
				</div><hr>
				<?php }	?>
	
				<div id="" class="">
					<?php
					if(isset($_GET['action']) && ($_GET['action'] == 'addRow') && !empty($rowDetail)) { ?>
					
						<div id="" class="">
							<form action="" id="addRowFrmData" name="addRowFrmData" method="post">
								<p class="pB10">Add Row to <strong><?php echo $table; ?></strong></p>
								<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%" style="border-collapse: collapse;">

									<?php						
									foreach($rowDetail as $columnkey => $rowkey) {
									if($columnkey!='0'){?>
									<tr>
										<td align="left">
											<strong><?php echo ucfirst($rowkey['Field']); ?></strong>
										</td>
										<td align="left">
											<input type="text" class="required" value="" name="rows[<?php echo $rowkey['Field']; ?>]" />
										</td>
									</tr>
									<?php } } ?>

									<tr>
										<td align="left">&nbsp;</td>
										<td align="left">
											<div class="submit1 submitbtn-div">
												<label for="submit" class="left">						
													<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>											
													<input type="hidden" value="<?php echo $table; ?>" name="tablename"/>
													<input type="hidden" value="<?php echo $_GET['action']; ?>" name="submitactionType"/>										
													<input type="submit" value="Submit" name="updaterowcoldata" class="submitbtn" >										
												</label>
												<label for="reset" class="right">
													<input onclick="javascript:window.history.go(-1)" type="button" id="button" value="Back" class="submitbtn">
												</label>
											</div>
										</td>
									</tr>									
								</table>
							</form>
						</div>
					<?php } else if(isset($_GET['action']) && ($_GET['action'] == 'addCol')) { ?>
						<div id="" class="">
							<form action="" id="frmaddcolTable" name="frmaddcolTable" method="post">
								<p class="pB10">Add Coloum to <strong><?php echo $table; ?></strong></p>
								<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%">
									<tr>
										<th bgcolor="#eeeeee">Name</th>
										<th bgcolor="#eeeeee">Type</th>
										<th bgcolor="#eeeeee">Length</th>
										<th bgcolor="#eeeeee">Null</th>
										<th bgcolor="#eeeeee">Actions</th>
									</tr>
									<tr>					
										<td align="left">
											<input onchange="javascript: checkColoumName('<?php echo $table; ?>');"id="columnname" type="text" style="width:150px" value="" name="columnname" class="required" />	
										</td>

										<td align="left">
											<select class="required" name="field_type" class="column_type">
												<option selected value="INT">INT</option>
												<option value="VARCHAR">VARCHAR</option>
												<option value="TEXT">TEXT</option>
												<option value="DATE">DATE</option>
												<optgroup label="NUMERIC">
													<option value="TINYINT">TINYINT</option>
													<option value="SMALLINT">SMALLINT</option>
													<option value="MEDIUMINT">MEDIUMINT</option>
													<option value="INT">INT</option>
													<option value="BIGINT">BIGINT</option>
													<option disabled="disabled">-</option>
													<option value="DECIMAL">DECIMAL</option>
													<option value="FLOAT">FLOAT</option>
													<option value="DOUBLE">DOUBLE</option>
													<option value="REAL">REAL</option>
													<option disabled="disabled">-</option>
													<option value="BIT">BIT</option>
													<option value="BOOLEAN">BOOLEAN</option>
													<option value="SERIAL">SERIAL</option>
												</optgroup>
												<optgroup label="DATE and TIME">
													<option value="DATE">DATE</option>
													<option value="DATETIME">DATETIME</option>
													<option value="TIMESTAMP">TIMESTAMP</option>
													<option value="TIME">TIME</option>
													<option value="YEAR">YEAR</option>
												</optgroup>
												<optgroup label="STRING">
													<option value="CHAR">CHAR</option>
													<option value="VARCHAR">VARCHAR</option>
													<option disabled="disabled">-</option>
													<option value="TINYTEXT">TINYTEXT</option>
													<option  value="TEXT">TEXT</option>
													<option  value="MEDIUMTEXT">MEDIUMTEXT</option>
													<option  value="LONGTEXT">LONGTEXT</option>
													<option  disabled="disabled">-</option>
													<option  value="BINARY">BINARY</option>
													<option  value="VARBINARY">VARBINARY</option>
													<option  disabled="disabled">-</option>
													<option  value="TINYBLOB">TINYBLOB</option>
													<option  value="MEDIUMBLOB">MEDIUMBLOB</option>
													<option  value="BLOB">BLOB</option>
													<option  value="LONGBLOB">LONGBLOB</option>
													<option  disabled="disabled">-</option>
													<option  value="ENUM">ENUM</option>
													<option  value="SET">SET</option>
												</optgroup>				
											</select>			
										</td>

										<td align="left">
											<input type="text" style="width:150px" value="" name="columnlength" class="" />				
										</td>

										<td align="left">
											<input type="checkbox" value="YES" name="null">
										</td>

										<th bgcolor="#eeeeee">
											<div class="submit1 submitbtn-div">
												<label for="submit" class="left">						
													<input type="hidden" value="<?php echo $dbid; ?>" name="dbid" />				
													<input type="hidden" value="<?php echo $table; ?>" name="tablename" />
													<input type="hidden" value="<?php echo $_GET['action']; ?>" name="submitactionType" />	
													<input id="updaterowcoldata" type="submit" value="Submit" name="updaterowcoldata" class="submitbtn" />	
												</label>
												<label for="reset" class="right">
													<input onclick="javascript:window.history.go(-1)" type="button" id="button" value="Back" class="submitbtn">
												</label>
											</div>
										</th>
									</tr>
								</table>
							</form>
						</div>

					<?php } else if(isset($_GET['action']) && $_GET['action'] == 'edit' && isset($rowDetail) && !empty($rowDetail) && isset($tablecolumn)){ ?>
						
						<form id="editFrmData" name="editFrmData" method="post">
						<p>Edit Data Of <strong><?php echo $table; ?></strong></p>
						<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%" style="border-collapse: collapse;">
							<?php foreach($rowDetail as $column => $value){ 
								if($column!=$tablecolumn){
							?>
								<tr>
									<td align="left"><strong><?php echo ucfirst($column); ?></strong></td>
									<td align="left">
									<input type="text" class="required" value="<?php echo $value; ?>" name="columns[<?php echo $column; ?>]" />
									</td>
								</tr>

							<?php } }?>

								<tr>
									<td align="left">&nbsp;</td>
									<td align="left">
										<div class="submit1 submitbtn-div">
											<label for="submit" class="left">
											
												<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
												<input type="hidden" value="<?php echo $tablecolumn; ?>" name="tablecolumn"/>
												<input type="hidden" value="<?php echo $table; ?>" name="tableName"/>
												<input type="hidden" value="<?php echo $tablecolumnvalue; ?>" name="tablecolumnvalue"/>
												<input type="submit" value="Submit" name="updatedata" class="submitbtn" >
										
											</label>
											<label for="reset" class="right">
												<!-- <input type="reset" id="reset" class="submitbtn"> -->
											</label>
										</div>
									</td>
								</tr>
						</table>
						</form>		

					<?php } else { ?>
					<p>Data Of <strong><?php echo $table; ?></strong></p>
					<div style="height: 395px;overflow-y:scroll;border: 1px solid #cccccc;width: 650px;">
						<?php if(isset($totalColumns) && $totalColumns >0){ ?>
						<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%" style="border-collapse: collapse;">
							<thead>
								<tr>
								<?php if($tableColumns >0){ 

									$primaryidColumn = '';
									foreach($tableColumns as $key => $columnDetail){
										if($key == 0 ){
											$primaryidColumn = $columnDetail['Field'];
										}
								?>
									<th bgcolor="#eeeeee"><?php echo $columnDetail['Field']; ?></td>
								<?php } ?>
									<th bgcolor="#eeeeee">Actions</td>
								<?php }?>
								</tr>
							</thead>
							<tbody>
								
								<?php if(mysql_num_rows($browseResult)){ 
								while($rowDetail = mysql_fetch_assoc($browseResult)){
								?>
									<tr>
									<?php 
									foreach($rowDetail as $keyB => $value){
									?>
									<td align="left"><?php echo $value; ?></td>
									<?php } ?>
									<td align="left"><a href="?action=edit&tablecolumn=<?php echo $primaryidColumn; ?>&value=<?php echo $rowDetail[$primaryidColumn]; ?>&tablename=<?php echo base64_encode($table); ?>&id=<?php echo base64_encode($dbid); ?>">Edit</a>&nbsp;&nbsp;<a href="?action=delete&tablecolumn=<?php echo $primaryidColumn; ?>&value=<?php echo $rowDetail[$primaryidColumn]; ?>&id=<?php echo base64_encode($dbid); ?>&tablename=<?php echo base64_encode($table); ?>" onclick="javascript: return confirm('Are you sure you want to delete this?');">Delete</a></td>
									</tr>
								<?php } ?>

								<?php } ?>
							</tbody>
						</table>
					<?php } ?>
					</div>
					<?php } ?>

				</div>
				

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


