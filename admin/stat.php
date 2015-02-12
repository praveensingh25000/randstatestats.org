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

$dbname = $db_geographic = $db_dataseries = $db_nextupdate = $db_datasource = $db_periodicity = $db_url = '';

$is_static_form = 'N';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));	
	$databaseDetail = $admin->getDatabase($dbid);
	
	if(!empty($databaseDetail)){
		
		$dbname			= stripslashes($databaseDetail['db_name']);
		$db_geographic	= stripslashes($databaseDetail['db_geographic']);
		$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
		$db_nextupdate	= stripslashes($databaseDetail['db_nextupdate']);
		$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
		$db_datasource	= stripslashes($databaseDetail['db_datasource']);
		$db_url			= stripslashes($databaseDetail['url']);
		$is_static_form = $databaseDetail['is_static_form'];
		
	}
}

if(isset($_POST['updatedb'])){

	$db_geographic	= (isset($_POST['db_geographic']))?trim(addslashes($_POST['db_geographic'])):'';
	$db_dataseries	= (isset($_POST['db_dataseries']))?trim(addslashes($_POST['db_dataseries'])):'';
	$db_nextupdate	= (isset($_POST['db_nextupdate']))?trim(addslashes($_POST['db_nextupdate'])):'';
	$db_datasource	= (isset($_POST['db_datasource']))?trim(addslashes($_POST['db_datasource'])):'';
	$db_periodicity	= (isset($_POST['db_periodicity']))?trim(addslashes($_POST['db_periodicity'])):'Other';
	
	$dbid			= $_POST['dbid'];

	$return = $admin->updateForm($db_geographic, $db_dataseries, $db_nextupdate, $db_datasource, $db_periodicity, $dbid);

	if($return >0 ){	
		$_SESSION['msgsuccess'] = 'Records has been updated';
	}

	header('location:'.URL_SITE.'/admin/stat.php?id='.base64_encode($dbid));
	exit;
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
				<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php echo "Edit Stats Of '".$databaseDetail['db_name']."'"; ?> </legend>
				
			
				<div id="generalDetails" >
					<form id="frmAllCat" name="frmAllCat" method="post">
					<p>Geographic Coverage<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_geographic" name="db_geographic" class="required" value="<?php if(isset($db_geographic)){ echo $db_geographic; } ?>"/>
					</div>

					<p>Periodicity</p>
					<div style="padding: 10px 0;">
						<select name="db_periodicity">
							<option value="">-- Select --</option>
							<option value="Monthly" <?php if(isset($db_periodicity) && $db_periodicity=='Monthly'){ echo "selected"; } ?>>Monthly</option>
							<option value="Quaterly" <?php if(isset($db_periodicity) && $db_periodicity=='Quaterly'){ echo "selected"; } ?>>Quaterly</option>
							<option value="Semi-Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Semi-Annually'){ echo "selected"; } ?>>Semi-Annually</option>
							<option value="Annually" <?php if(isset($db_periodicity) && $db_periodicity=='Annually'){ echo "selected"; } ?>>Annually</option>
							<option value="Other" <?php if(isset($db_periodicity) && $db_periodicity=='Other'){ echo "selected"; } ?>>Other</option>
						</select>
					</div>

					<p>Data Series</p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_dataseries" name="db_dataseries" class="" value="<?php if(isset($db_dataseries)){ echo $db_dataseries; } ?>"/>
					</div>

					<p>Next Update</p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_nextupdate" name="db_nextupdate" class="" value="<?php if(isset($db_nextupdate) && $db_nextupdate!='0000-00-00'){ echo $db_nextupdate; } ?>"/><br/>(Format. YYYY-MM-DD)
					</div>

					<p>Data Source<em>*</em></p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_datasource" name="db_datasource" class="required" value="<?php if(isset($db_datasource)){ echo $db_datasource; } ?>"/>
					</div>
					
					<?php if($is_static_form == "Y"){ ?>
					
					<p>Form Url</p>
					<div style="padding: 10px 0;">
						<input type="text" id="db_url" name="db_url" class="required" value="<?php if(isset($db_url)){ echo $db_url; } ?>"/>
					</div>

					<?php } ?>
					

					<div class="submit1 submitbtn-div">
						<label for="submit" class="left">
						<?php if(isset($_GET['id']) && $_GET['id']!=''){?>
							<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
							<input type="submit" value="Submit" name="updatedb" class="submitbtn" >
							<?php } else { ?>
							<input type="submit" value="Submit" name="adddb" class="submitbtn" >
							<?php } ?>
							
						</label>
						<label for="reset" class="right">
							<input type="reset" id="reset" class="submitbtn">
						</label>
					</div>
					</form>
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


