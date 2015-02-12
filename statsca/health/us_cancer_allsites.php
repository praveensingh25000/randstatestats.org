<?php
/******************************************
* @Modified on Feb 13, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/popdemo/us_cancer_allsites.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesname					= "uscancerall";

$admin  = new admin();
$user   = new user();

$dbname	= $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['us_cancer_allsites'] = $_POST;

	if(isset($_SESSION['user']['email']) && $_SESSION['user']['email'] !='')					 
		$user_email=$_SESSION['user']['email'];
	else
		$user_email=$_SESSION['user']['username'];

	$userDetail		=	$user->selectUserProfile($user_email);	
	$validity_on	=	$admin->Validity($userDetail['id'],$user_email);
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: '.URL_SITE.'/plansubscriptions.php');
		exit;
	} else {
		header('location: us_cancer_allsites_data.php');
		exit;
	}
}

$dbid = 108;
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	$dbname			= stripslashes($databaseDetail['db_title']);
	$dbsource		= stripslashes($databaseDetail['db_datasource']);
	$description	= stripslashes($databaseDetail['db_description']);
	$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	$nextupdate		= stripslashes($databaseDetail['db_nextupdate']);
	$table			= stripslashes($databaseDetail['table_name']);
	$db_geographic	= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource	= stripslashes($databaseDetail['db_source']);
	$dateupdated	= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink	= stripslashes($databaseDetail['db_sourcelink']);
}else{
	header('location: databases.php');
}

$related_DB = $admin->getAllDatabaseRelatedDatabases($dbid);

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesname);
$already = array();

while($value = mysql_fetch_assoc($allCategoryDetail_res)){
	if(!in_array($value['area'], $already))
	$filtervaluesJsonArray[] = array('id' => $value['area'], 'name' => $value['area']);

	$already[] = $value['area'];
}
$filtervaluesJson = json_encode($filtervaluesJsonArray);

//echo "<pre>";print_r($filtervaluesJson);echo "</pre>";
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2><br />

		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- form basic info details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- form basic info details -->

			<!-- FORM SECTION DATA DIV ------>
				<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
					
					<input type="hidden" name="session_setter" value="us_cancer_allsites"/>
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_all_cancer'])){ echo $lang['lbl_all_cancer']; } ?></span>
						</p>
						<div class="table-div">
							<table width="700" cellpadding="6" border="1" summary="" class="collapse">
								<tbody><tr>
								<th width="40%" class="thead">Cancer type</th>
								<th width="10%" class="thead">Count</th>
								<th width="20%" class="thead">Crude rate per 100,000</th>
								<th width="20%" class="thead">Age-Adjusted rate per 100,000</th>
								</tr>
								</tbody>
							</table>
							

								<table width="700" cellpadding="6" border="1" summary="" class="collapse">

								<tbody><tr class="botbar">
								<td width="40%">All Cancer Types Combined</td>
								<td width="10%" align="center"><input type="checkbox" value="All Cancer Types Combined" name="category[]"></td>
								<td width="20%" align="center"><input type="checkbox" value="All Cancer Types Combined, crude rate per 100,000 persons" name="category[]"></td>
								<td width="20%" align="center"><input type="checkbox" value="All Cancer Types Combined, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Acute Lymphocytic Leukemia</td>
								<td align="center"><input type="checkbox" value="Acute Lymphocytic Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Lymphocytic Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Lymphocytic Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Acute Monocytic Leukemia</td>
								<td align="center"><input type="checkbox" value="Acute Monocytic Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Monocytic Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Monocytic Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Acute Myeloid Leukemia</td>
								<td align="center"><input type="checkbox" value="Acute Myeloid Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Myeloid Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Acute Myeloid Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Aleukemic, Subleukemic and NOS</td>
								<td align="center"><input type="checkbox" value="Aleukemic, Subleukemic and NOS" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Aleukemic, Subleukemic and NOS, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Aleukemic, Subleukemic and NOS, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Anus, Anal Canal and Anorectum</td>
								<td align="center"><input type="checkbox" value="Anus, Anal Canal and Anorectum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Anus, Anal Canal and Anorectum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Anus, Anal Canal and Anorectum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Appendix</td>
								<td align="center"><input type="checkbox" value="Appendix" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Appendix, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Appendix, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Ascending Colon</td>
								<td align="center"><input type="checkbox" value="Ascending Colon" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ascending Colon, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ascending Colon, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Bones and Joints</td>
								<td align="center"><input type="checkbox" value="Bones and Joints" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Bones and Joints, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Bones and Joints, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Brain</td>
								<td align="center"><input type="checkbox" value="Brain" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Brain, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Brain, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Brain and Other Nervous System</td>
								<td align="center"><input type="checkbox" value="Brain and Other Nervous System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Brain and Other Nervous System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Brain and Other Nervous System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Cecum</td>
								<td align="center"><input type="checkbox" value="Cecum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cecum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cecum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Cervix Uteri</td>
								<td align="center"><input type="checkbox" value="Cervix Uteri" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cervix Uteri, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cervix Uteri, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Chronic Lymphocytic Leukemia</td>
								<td align="center"><input type="checkbox" value="Chronic Lymphocytic Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Chronic Lymphocytic Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Chronic Lymphocytic Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Chronic Myeloid Leukemia</td>
								<td align="center"><input type="checkbox" value="Chronic Myeloid Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Chronic Myeloid Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Chronic Myeloid Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Colon and Rectum</td>
								<td align="center"><input type="checkbox" value="Colon and Rectum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Colon and Rectum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Colon and Rectum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Colon excluding Rectum</td>
								<td align="center"><input type="checkbox" value="Colon excluding Rectum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Colon excluding Rectum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Colon excluding Rectum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Corpus Uteri</td>
								<td align="center"><input type="checkbox" value="Corpus Uteri" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Corpus Uteri, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Corpus Uteri, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Cranial Nerves Other Nervous System</td>
								<td align="center"><input type="checkbox" value="Cranial Nerves Other Nervous System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cranial Nerves Other Nervous System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Cranial Nerves Other Nervous System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Descending Colon</td>
								<td align="center"><input type="checkbox" value="Descending Colon" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Descending Colon, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Descending Colon, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Digestive System</td>
								<td align="center"><input type="checkbox" value="Digestive System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Digestive System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Digestive System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Endocrine System</td>
								<td align="center"><input type="checkbox" value="Endocrine System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Endocrine System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Endocrine System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Esophagus</td>
								<td align="center"><input type="checkbox" value="Esophagus" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Esophagus, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Esophagus, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Eye and Orbit</td>
								<td align="center"><input type="checkbox" value="Eye and Orbit" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Eye and Orbit, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Eye and Orbit, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Female Breast</td>
								<td align="center"><input type="checkbox" value="Female Breast" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Breast, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Breast, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Female Breast, In Situ</td>
								<td align="center"><input type="checkbox" value="Female Breast, In Situ" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Breast, In Situ, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Breast, In Situ, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Female Genital System</td>
								<td align="center"><input type="checkbox" value="Female Genital System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Genital System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Female Genital System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Floor of Mouth</td>
								<td align="center"><input type="checkbox" value="Floor of Mouth" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Floor of Mouth, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Floor of Mouth, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Gallbladder</td>
								<td align="center"><input type="checkbox" value="Gallbladder" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Gallbladder, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Gallbladder, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Gum and Other Mouth</td>
								<td align="center"><input type="checkbox" value="Gum and Other Mouth" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Gum and Other Mouth, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Gum and Other Mouth, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Hepatic Flexure</td>
								<td align="center"><input type="checkbox" value="Hepatic Flexure" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hepatic Flexure, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hepatic Flexure, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Hodgkin - Extranodal</td>
								<td align="center"><input type="checkbox" value="Hodgkin - Extranodal" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin - Extranodal, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin - Extranodal, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Hodgkin - Nodal</td>
								<td align="center"><input type="checkbox" value="Hodgkin - Nodal" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin - Nodal, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin - Nodal, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Hodgkin lymphoma</td>
								<td align="center"><input type="checkbox" value="Hodgkin lymphoma" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin lymphoma, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hodgkin lymphoma, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Hypopharynx</td>
								<td align="center"><input type="checkbox" value="Hypopharynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hypopharynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Hypopharynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>In Situ Breast Cancer</td>
								<td align="center"><input type="checkbox" value="In Situ Breast Cancer" name="category[]"></td>
								<td align="center"><input type="checkbox" value="In Situ Breast Cancer, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="In Situ Breast Cancer, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Intrahepatic Bile Duct</td>
								<td align="center"><input type="checkbox" value="Intrahepatic Bile Duct" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Intrahepatic Bile Duct, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Intrahepatic Bile Duct, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Kaposi Sarcoma</td>
								<td align="center"><input type="checkbox" value="Kaposi Sarcoma" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Kaposi Sarcoma, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Kaposi Sarcoma, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Kidney and Renal Pelvis</td>
								<td align="center"><input type="checkbox" value="Kidney and Renal Pelvis" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Kidney and Renal Pelvis, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Kidney and Renal Pelvis, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Large Intestine, NOS</td>
								<td align="center"><input type="checkbox" value="Large Intestine, NOS" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Large Intestine, NOS, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Large Intestine, NOS, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Larynx</td>
								<td align="center"><input type="checkbox" value="Larynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Larynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Larynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Leukemias</td>
								<td align="center"><input type="checkbox" value="Leukemias" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Leukemias, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Leukemias, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Lip</td>
								<td align="center"><input type="checkbox" value="Lip" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lip, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lip, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Liver</td>
								<td align="center"><input type="checkbox" value="Liver" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Liver, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Liver, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Liver and Intrahepatic Bile Duct</td>
								<td align="center"><input type="checkbox" value="Liver and Intrahepatic Bile Duct" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Liver and Intrahepatic Bile Duct, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Liver and Intrahepatic Bile Duct, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Lung and Bronchus</td>
								<td align="center"><input type="checkbox" value="Lung and Bronchus" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lung and Bronchus, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lung and Bronchus, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Lymphomas</td>
								<td align="center"><input type="checkbox" value="Lymphomas" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lymphomas, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Lymphomas, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Male Breast</td>
								<td align="center"><input type="checkbox" value="Male Breast" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Breast, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Breast, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Male Breast, In Situ</td>
								<td align="center"><input type="checkbox" value="Male Breast, In Situ" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Breast, In Situ, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Breast, In Situ, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Male Genital System</td>
								<td align="center"><input type="checkbox" value="Male Genital System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Genital System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male Genital System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Male and Female Breast</td>
								<td align="center"><input type="checkbox" value="Male and Female Breast" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male and Female Breast, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Male and Female Breast, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Melanoma of the Skin</td>
								<td align="center"><input type="checkbox" value="Melanoma of the Skin" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Melanoma of the Skin, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Melanoma of the Skin, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Mesothelioma</td>
								<td align="center"><input type="checkbox" value="Mesothelioma" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Mesothelioma, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Mesothelioma, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Miscellaneous</td>
								<td align="center"><input type="checkbox" value="Miscellaneous" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Miscellaneous, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Miscellaneous, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Myeloma</td>
								<td align="center"><input type="checkbox" value="Myeloma" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Myeloma, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Myeloma, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>NHL - Extranodal</td>
								<td align="center"><input type="checkbox" value="NHL - Extranodal" name="category[]"></td>
								<td align="center"><input type="checkbox" value="NHL - Extranodal, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="NHL - Extranodal, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>NHL - Nodal</td>
								<td align="center"><input type="checkbox" value="NHL - Nodal" name="category[]"></td>
								<td align="center"><input type="checkbox" value="NHL - Nodal, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="NHL - Nodal, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Nasopharynx</td>
								<td align="center"><input type="checkbox" value="Nasopharynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Nasopharynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Nasopharynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Non-Hodgkin Lymphoma</td>
								<td align="center"><input type="checkbox" value="Non-Hodgkin Lymphoma" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Non-Hodgkin Lymphoma, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Non-Hodgkin Lymphoma, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Nose, Nasal Cavity and Middle Ear</td>
								<td align="center"><input type="checkbox" value="Nose, Nasal Cavity and Middle Ear" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Nose, Nasal Cavity and Middle Ear, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Nose, Nasal Cavity and Middle Ear, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Oral Cavity and Pharynx</td>
								<td align="center"><input type="checkbox" value="Oral Cavity and Pharynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Oral Cavity and Pharynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Oral Cavity and Pharynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Oropharynx</td>
								<td align="center"><input type="checkbox" value="Oropharynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Oropharynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Oropharynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Acute Leukemia</td>
								<td align="center"><input type="checkbox" value="Other Acute Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Acute Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Acute Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Biliary</td>
								<td align="center"><input type="checkbox" value="Other Biliary" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Biliary, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Biliary, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Digestive Organs</td>
								<td align="center"><input type="checkbox" value="Other Digestive Organs" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Digestive Organs, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Digestive Organs, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Endocrine including Thymus</td>
								<td align="center"><input type="checkbox" value="Other Endocrine including Thymus" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Endocrine including Thymus, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Endocrine including Thymus, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Female Genital Organs</td>
								<td align="center"><input type="checkbox" value="Other Female Genital Organs" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Female Genital Organs, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Female Genital Organs, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Leukemias</td>
								<td align="center"><input type="checkbox" value="Other Leukemias" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Leukemias, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Leukemias, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Lymphocytic Leukemia</td>
								<td align="center"><input type="checkbox" value="Other Lymphocytic Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Lymphocytic Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Lymphocytic Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Male Genital Organs</td>
								<td align="center"><input type="checkbox" value="Other Male Genital Organs" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Male Genital Organs, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Male Genital Organs, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Myeloid/Monocytic Leukemia</td>
								<td align="center"><input type="checkbox" value="Other Myeloid/Monocytic Leukemia" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Myeloid/Monocytic Leukemia, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Myeloid/Monocytic Leukemia, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Non-Epithelial Skin</td>
								<td align="center"><input type="checkbox" value="Other Non-Epithelial Skin" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Non-Epithelial Skin, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Non-Epithelial Skin, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Oral Cavity and Pharynx</td>
								<td align="center"><input type="checkbox" value="Other Oral Cavity and Pharynx" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Oral Cavity and Pharynx, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Oral Cavity and Pharynx, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Other Urinary Organs</td>
								<td align="center"><input type="checkbox" value="Other Urinary Organs" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Urinary Organs, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Other Urinary Organs, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Ovary</td>
								<td align="center"><input type="checkbox" value="Ovary" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ovary, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ovary, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Pancreas</td>
								<td align="center"><input type="checkbox" value="Pancreas" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Pancreas, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Pancreas, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Penis</td>
								<td align="center"><input type="checkbox" value="Penis" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Penis, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Penis, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Peritoneum, Omentum and Mesentery</td>
								<td align="center"><input type="checkbox" value="Peritoneum, Omentum and Mesentery" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Peritoneum, Omentum and Mesentery, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Peritoneum, Omentum and Mesentery, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Pleura</td>
								<td align="center"><input type="checkbox" value="Pleura" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Pleura, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Pleura, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Prostate</td>
								<td align="center"><input type="checkbox" value="Prostate" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Prostate, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Prostate, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Rectosigmoid Junction</td>
								<td align="center"><input type="checkbox" value="Rectosigmoid Junction" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectosigmoid Junction, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectosigmoid Junction, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Rectum</td>
								<td align="center"><input type="checkbox" value="Rectum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Rectum and Rectosigmoid Junction</td>
								<td align="center"><input type="checkbox" value="Rectum and Rectosigmoid Junction" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectum and Rectosigmoid Junction, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Rectum and Rectosigmoid Junction, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Respiratory System</td>
								<td align="center"><input type="checkbox" value="Respiratory System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Respiratory System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Respiratory System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Retroperitoneum</td>
								<td align="center"><input type="checkbox" value="Retroperitoneum" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Retroperitoneum, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Retroperitoneum, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Salivary Gland</td>
								<td align="center"><input type="checkbox" value="Salivary Gland" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Salivary Gland, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Salivary Gland, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Sigmoid Colon</td>
								<td align="center"><input type="checkbox" value="Sigmoid Colon" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Sigmoid Colon, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Sigmoid Colon, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Skin excluding Basal and Squamous</td>
								<td align="center"><input type="checkbox" value="Skin excluding Basal and Squamous" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Skin excluding Basal and Squamous, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Skin excluding Basal and Squamous, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Small Intestine</td>
								<td align="center"><input type="checkbox" value="Small Intestine" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Small Intestine, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Small Intestine, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Soft Tissue including Heart</td>
								<td align="center"><input type="checkbox" value="Soft Tissue including Heart" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Soft Tissue including Heart, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Soft Tissue including Heart, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Splenic Flexure</td>
								<td align="center"><input type="checkbox" value="Splenic Flexure" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Splenic Flexure, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Splenic Flexure, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Stomach</td>
								<td align="center"><input type="checkbox" value="Stomach" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Stomach, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Stomach, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Testis</td>
								<td align="center"><input type="checkbox" value="Testis" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Testis, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Testis, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Thyroid</td>
								<td align="center"><input type="checkbox" value="Thyroid" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Thyroid, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Thyroid, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Tongue</td>
								<td align="center"><input type="checkbox" value="Tongue" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Tongue, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Tongue, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Tonsil</td>
								<td align="center"><input type="checkbox" value="Tonsil" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Tonsil, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Tonsil, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Trachea, Mediastinum and Other Respiratory Organs</td>
								<td align="center"><input type="checkbox" value="Trachea, Mediastinum and Other Respiratory Organs" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Trachea, Mediastinum and Other Respiratory Organs, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Trachea, Mediastinum and Other Respiratory Organs, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Transverse Colon</td>
								<td align="center"><input type="checkbox" value="Transverse Colon" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Transverse Colon, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Transverse Colon, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Ureter</td>
								<td align="center"><input type="checkbox" value="Ureter" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ureter, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Ureter, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Urinary Bladder</td>
								<td align="center"><input type="checkbox" value="Urinary Bladder" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Urinary Bladder, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Urinary Bladder, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Urinary System</td>
								<td align="center"><input type="checkbox" value="Urinary System" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Urinary System, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Urinary System, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Uterus, NOS</td>
								<td align="center"><input type="checkbox" value="Uterus, NOS" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Uterus, NOS, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Uterus, NOS, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Vagina</td>
								<td align="center"><input type="checkbox" value="Vagina" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Vagina, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Vagina, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								<tr class="botbar">
								<td>Vulva</td>
								<td align="center"><input type="checkbox" value="Vulva" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Vulva, crude rate per 100,000 persons" name="category[]"></td>
								<td align="center"><input type="checkbox" value="Vulva, age-adjusted rate per 100,000 persons" name="category[]"></td>
								</tr>
								</tbody></table>

					
						</div>
					</div>

					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_choose_area'])){ echo $lang['lbl_please_choose_area']; } ?></span>
						</p>
						<div class="table-div">
							<input type="text" id="search_criteria_duplicates" name="us_states"/>
						</div>
						<script type="text/javascript">
						$(document).ready(function() {							
					
							$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true });
						});
						</script>
					</div>
					
					

					

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
						
					
						
						<div class="submitbtn-div" id="submitButtons" >
							<input onclick="javascript: return checkLoginUser('<?php if(isset($_SESSION['user'])) echo "true"; else echo 'false'; ?>','select','0');" value="Submit" name="getresults" class="submitbtn" type="submit">
							<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >
							<input type="submit" class="right" name="" value="Reset">
						</div>
					
					</div>
					<!---------------- TIME INTERVAL SETTINGS -------------------->
				
				</form>
			<!-- /FORM SECTION DATA DIV ------>

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