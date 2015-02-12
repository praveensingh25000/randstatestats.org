<?php
/******************************************
* @Created on May 24, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://randstatestats.org
* @live URL: http://tx.rand.org/stats/popdemo/popraceageUSdet_census.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesname					= "uspopest";
$tablesnamearea				= "uspopest_areas";

$admin  = new admin();
$user   = new user();

$dbname	= $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['popestUSdetsort'] = $_POST;

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
			header('location: popestUSdetsortData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
		header('location: popestUSdetsortData.php');
		exit;
		}
	}
}

$dbid = 165;
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

//$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamearea);
//$stateToName = $dbDatabase->getAll($allCategoryDetail_res);

//foreach($stateToName as $key => $value)
//$filtervaluesJsonArray[] = array('id' => $value['statecode'], 'name' => $value['statename']);
//$filtervaluesJson = json_encode($filtervaluesJsonArray);

//echo "<pre>";print_r($filtervaluesJson);echo "</pre>";
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

			<br class="clear" />

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
				<!---------------- TIME INTERVAL SETTINGS -------------------->
				<div class="bottom-submit">	
					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SY-EY'){							
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);
						?>

						<h4>
							<?php if(isset($lang['lbl_please_choose_year'])){ echo $lang['lbl_please_choose_year'];} ?>&nbsp;&nbsp;&nbsp;
							<select id="syear" size="1" name="syear">
							<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</h4>									

					<?php } ?>	
					
					<!---------------- TIME INTERVAL SETTINGS -------------------->

					<!-- form -->
					<input type="hidden" name="session_setter" value="popestUSdetsort">
					<div class="form-div">				  
						<h4>
							<span class="">
								<?php if(isset($lang['lbl_largest_by_city'])){ echo $lang['lbl_largest_by_city'];} ?>
							</span>			   
							
							<span class="pL40">
								<select size="1" id="largest_by_number" name="largest_by_number">					
									<option value="10">10</option>	
									<option value="20">20</option>	
									<option value="30">30</option>	
									<option value="40">40</option>	
									<option value="50">50</option>	
								</select>
							</span>
							<span class="">&nbsp;&nbsp;&nbsp;<b>OR</b>&nbsp;&nbsp;&nbsp;</span>	
							<span class=""><input class="digits" placeholder="enter number" type="text" id="largest_by_name" name="largest_by_name"/></span>											
						</h4>		
					</div>
					<!-- /form -->
					
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
			
			</form>			
		</div>

	</section>

	<!-- RELATED_DB -->
	<?php 
	if(mysql_num_rows($related_DB) > 0) { ?>
	<section id="inner-sidebar">
	   <?php require_once $basedir."/relatedForms.php"; ?>
   </section>
   <?php } ?>
   <!-- RELATED_DB -->

</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>