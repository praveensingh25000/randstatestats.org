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
if(isset($_GET['show']) && $_GET['show'] == 'tables'){
	$tablesResult = $admin->showAllTables();
	$total = $db->count_rows($tablesResult);
	$tables = $db->getAll($tablesResult);
}

if(isset($_GET['show']) && $_GET['show'] == 'columns' && isset($_GET['table']) && $_GET['table'] != ''){
	$tablename = trim($_GET['table']);
	$tablesResultColumn = $admin->showColumns($tablename);
	$totalColumns = $db->count_rows($tablesResultColumn);
	$tableColumns = $db->getAll($tablesResultColumn);
}

header('location: databases.php');
exit;
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
			<a href="<?php URL_SITE;?>/admin/databases.php">Show All Forms</a>

			<?php if(isset($total) && $total >0){ ?>
			<table cellspacing="0" cellpadding="2" border="1" class="collapse" id="grid_view">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee">TableName</th>
						<th bgcolor="#eeeeee">Actions</th>
					</tr>
					<?php if($tables >0){ 
						foreach($tables as $key => $tablename){
							$name = trim($tablename['Tables_in_ideanin1_demo']);
							if($name == 'admins' || $name == 'asylees20022011'){
					?>
						<tr>
							<td align="left"><font size="2"><?php echo $tablename['Tables_in_ideanin1_demo']; ?></font></td>
							<td align="left"><a href="?show=columns&table=<?php echo $name; ?>">Show Columns</a></td>
						</tr>

					<?php } } }?>

				</tbody>
			</table>
			<?php } ?>

			<?php if(isset($totalColumns) && $totalColumns >0){ ?>

			<a href="modify.php?table=<?php echo $tablename; ?>&add">Add Column</a>

			<table cellspacing="0" cellpadding="2" border="1" class="collapse" id="grid_view">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee">Name</th>
						<th bgcolor="#eeeeee">Type</th>
						<th bgcolor="#eeeeee">Null</th>
						<th bgcolor="#eeeeee">Key</th>
						<th bgcolor="#eeeeee">Default</th>
						<th bgcolor="#eeeeee">Extra</th>
					</tr>
					<?php if($tableColumns >0){ 
						foreach($tableColumns as $key => $columnDetail){
							
					?>
						<tr>
							<td align="left"><?php echo $columnDetail['Field']; ?></td>
							<td align="left"><?php echo $columnDetail['Type']; ?></td>
							<td align="left"><?php echo $columnDetail['Null']; ?></td>
							<td align="left"><?php echo $columnDetail['Key']; ?></td>
							<td align="left"><?php echo $columnDetail['Default']; ?></td>
							<td align="left"><?php echo $columnDetail['Extra']; ?></td>
						</tr>

					<?php } }?>

				</tbody>
			</table>
			<?php } ?>


		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>



