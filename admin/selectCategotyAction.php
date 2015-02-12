<?php
/******************************************
* @Modified on 22 March 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

ob_start();
session_start();
ini_set("display_errors","2");
ERROR_REPORTING(E_ALL);

if(isset($_POST['dbname']) && $_POST['dbname']== 'rand_texas') {

	if(isset($_POST['dbname']) && $_POST['dbname']!='') {
		$dbname=$_POST['dbname'];
	} else {
		$dbname = 'rand_texas';
	}

	if(isset($_POST['sharedbname']) && $_POST['sharedbname']!='') {
		$sharedbname = $_POST['sharedbname'];
	} else {
		$sharedbname = 'rand_usa';
	}
	
	$DOC_ROOT   = $_SERVER['DOCUMENT_ROOT'].'/';

	$protocolArray = explode('/', $_SERVER['SERVER_PROTOCOL']);

	if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
		$URL_SITE = 'https://'.$_SERVER['HTTP_HOST'];
	} else {
		$URL_SITE = strtolower($protocolArray[0]).'://'.$_SERVER['HTTP_HOST'];
	}

	require_once($DOC_ROOT.'include/functions.php');
	require_once($DOC_ROOT.'classes/database.class.php');
	require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
	require_once($DOC_ROOT.'classes/TempAdmin.php');
	require_once($DOC_ROOT.'classes/admin.class.php');	
	require_once($DOC_ROOT.'classes/user.class.php');
	require_once($DOC_ROOT.'classes/categoryClass.php');
	require_once($DOC_ROOT.'classes/pagination.class.php');
	require_once($DOC_ROOT.'classes/cms_pages.class.php');

	$db   = new db('localhost', 'root', 'j0eN@t!on', 'rand_admin');
	$dbtx = new db('localhost', 'root', 'j0eN@t!on', $dbname);
	$dbus = new db('localhost', 'root', 'j0eN@t!on', $sharedbname);

	$dbid					  = trim($_POST['dbid']);

	$admin = new admin();
	$categoryidArray = $subCategoriestxArray = array();

	$sqlpat_tx					 = "select * from `searchform_category` where database_id = '".$dbid."' and cat_type = 'p' ";
	$pattxResult				 = mysql_query($sqlpat_tx, $dbus->conn);	
	if(isset($pattxResult) && mysql_num_rows($pattxResult) > 0) {
		while($patcategoryDetail = mysql_fetch_assoc($pattxResult)){			
			$categoryidtxArray[]	 = $patcategoryDetail['category_id'];			
		}
	}

	if(!empty($categoryidtxArray)) { ?>
		
		<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
		<h2 class="pT10">Texas</h2>
		
		<?php foreach($categoryidtxArray as $categoryid){
				$category_sql="SELECT * from category WHERE id = '".$categoryid."' and is_active = '1' ";
				$category_res=mysql_query($category_sql, $db->conn);
				$CategoryDetail=mysql_fetch_assoc($category_res);

				$sql="SELECT * from categories WHERE parent_id = '".$categoryid."' and is_active = '1' order by id DESC";
				$res=mysql_query($sql,$dbtx->conn);
				while($subCategories =mysql_fetch_assoc($res)){
					$subCategoriestxArray[]=$subCategories;
				}
				?>
				<br class="clear" />
				<p>Select Sub Categories of <b><?php if(!empty($CategoryDetail['category_title'])) { echo ucwords($CategoryDetail['category_title']); } ?></b> for <b>Texas</b> to display this form<em>*</em></p>
				<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
					<?php 
					if(!empty($subCategoriestxArray)) { ?>
					<div class="pL10">
						<table border="0" cellpadding="4">
							<?php foreach($subCategoriestxArray as $key => $categoryDetail){ ?>
							<tr>
								<td width="10%">
									<input class="required" type="radio" name="categories[<?php echo $categoryid;?>][rand_texas]" value="<?php echo $categoryDetail['id']; ?>"/>
								</td>
								<td><?php echo $categoryDetail['category_title']; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
					<?php } else {
						$_SESSION['display']='no';
						echo 'No Subcategory added';
					} ?>
				</div>
			<?php } ?>

		</div>

	<?php } ?>

<?php } else if(isset($_POST['dbname']) && $_POST['dbname']== 'rand_newyork') {

	if(isset($_POST['dbname']) && $_POST['dbname']!='') {
		$dbname1 = $_POST['dbname'];
	} else {
		$dbname1 = 'rand_texas';
	}

	if(isset($_POST['sharedbname']) && $_POST['sharedbname']!='') {
		$sharedbname = $_POST['sharedbname'];
	} else {
		$sharedbname = 'rand_usa';
	}
	
	$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'].'/';

	$protocolArray = explode('/', $_SERVER['SERVER_PROTOCOL']);

	if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
		$URL_SITE = 'https://'.$_SERVER['HTTP_HOST'];
	} else {
		$URL_SITE = strtolower($protocolArray[0]).'://'.$_SERVER['HTTP_HOST'];
	}

	require_once($DOC_ROOT.'include/functions.php');
	require_once($DOC_ROOT.'classes/database.class.php');
	require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
	require_once($DOC_ROOT.'classes/TempAdmin.php');
	require_once($DOC_ROOT.'classes/admin.class.php');	
	require_once($DOC_ROOT.'classes/user.class.php');
	require_once($DOC_ROOT.'classes/categoryClass.php');
	require_once($DOC_ROOT.'classes/pagination.class.php');
	require_once($DOC_ROOT.'classes/cms_pages.class.php');

	$db   = new db('localhost', 'root', 'j0eN@t!on', 'rand_admin');
	$dbny = new db('localhost', 'root', 'j0eN@t!on', $dbname1);
	$dbus = new db('localhost', 'root', 'j0eN@t!on', $sharedbname);

	$dbid					  = trim($_POST['dbid']);

	$admin = new admin();
	$categoryidnyArray = $subCategoriesnyArray = array();

	$sqlpat_ny					 = "select * from `searchform_category` where database_id = '".$dbid."' and cat_type = 'p' ";
	$patnyResult				 = mysql_query($sqlpat_ny, $dbus->conn);	
	if(isset($patnyResult) && mysql_num_rows($patnyResult) > 0) {
		while($patcategoryDetail = mysql_fetch_assoc($patnyResult)){			
			$categoryidnyArray[]	 = $patcategoryDetail['category_id'];			
		}
	}

	if(!empty($categoryidnyArray)){ ?>
		
		<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
		<h2 class="pT10">Newyork</h2>
		
		<?php foreach($categoryidnyArray as $categoryid){
				$category_sql="SELECT * from category WHERE id = '".$categoryid."' and is_active = '1' ";
				$category_res=mysql_query($category_sql, $db->conn);
				$CategoryDetail=mysql_fetch_assoc($category_res);

				$sql="SELECT * from categories WHERE parent_id = '".$categoryid."' and is_active = '1' order by id DESC";
				$res=mysql_query($sql,$dbny->conn);
				while($subCategories =mysql_fetch_assoc($res)){
					$subCategoriesnyArray[]=$subCategories;
				}
				?>
				<br class="clear" />
				<p>Select Sub Categories of <b><?php if(!empty($CategoryDetail['category_title'])) { echo ucwords($CategoryDetail['category_title']); } ?></b> for <b>Newyork</b> to display this form<em>*</em></p>
				<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
					<?php 
					if(!empty($subCategoriesnyArray)) { ?>
					<div class="pL10">
						<table border="0" cellpadding="4">
							<?php foreach($subCategoriesnyArray as $key => $categoryDetail){ ?>
							<tr>
								<td width="10%">
									<input class="required" type="radio" name="categories[<?php echo $categoryid;?>][rand_newyork]" value="<?php echo $categoryDetail['id']; ?>"/>
								</td>
								<td><?php echo $categoryDetail['category_title']; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
					<?php } else {
						echo 'No Subcategory added';
					} ?>
				</div>
			<?php } ?>
		</div>
	<?php } ?>

<?php } else if(isset($_POST['dbname']) && $_POST['dbname']== 'rand_california') {

	if(isset($_POST['dbname']) && $_POST['dbname']!='') {
		$dbname2 = $_POST['dbname'];
	} else {
		$dbname2 = 'rand_texas';
	}

	if(isset($_POST['sharedbname']) && $_POST['sharedbname']!='') {
		$sharedbname = $_POST['sharedbname'];
	} else {
		$sharedbname = 'rand_usa';
	}

	$DOC_ROOT   = $_SERVER['DOCUMENT_ROOT'].'/';

	$protocolArray = explode('/', $_SERVER['SERVER_PROTOCOL']);

	if( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
		$URL_SITE = 'https://'.$_SERVER['HTTP_HOST'];
	} else {
		$URL_SITE = strtolower($protocolArray[0]).'://'.$_SERVER['HTTP_HOST'];
	}

	require_once($DOC_ROOT.'include/functions.php');
	require_once($DOC_ROOT.'classes/database.class.php');
	require_once($DOC_ROOT.'classes/searchCriteriaClass.php');
	require_once($DOC_ROOT.'classes/TempAdmin.php');
	require_once($DOC_ROOT.'classes/admin.class.php');	
	require_once($DOC_ROOT.'classes/user.class.php');
	require_once($DOC_ROOT.'classes/categoryClass.php');
	require_once($DOC_ROOT.'classes/pagination.class.php');
	require_once($DOC_ROOT.'classes/cms_pages.class.php');

	$db   = new db('localhost', 'root', 'j0eN@t!on', 'rand_admin');
	$dbca = new db('localhost', 'root', 'j0eN@t!on', $dbname2);
	$dbus = new db('localhost', 'root', 'j0eN@t!on', $sharedbname);

	$dbid					  = trim($_POST['dbid']);

	$admin = new admin();
	$categoryidcaArray = $subCategoriescaArray = array();

	$sqlpat_ca					 = "select * from `searchform_category` where database_id = '".$dbid."' and cat_type = 'p' ";
	$patcaResult				 = mysql_query($sqlpat_ca, $dbus->conn);	
	if(isset($patcaResult) && mysql_num_rows($patcaResult) > 0) {
		while($patcategoryDetail = mysql_fetch_assoc($patcaResult)){			
			$categoryidcaArray[]	 = $patcategoryDetail['category_id'];			
		}
	}

	if(!empty($categoryidcaArray)){ ?>
				
		<div style="margin: 12px 0px;padding:5px 0px 10px 10px;height:auto;overflow: auto;width: auto;border: 1px solid #cccccc;">
		<h2 class="pT10">California</h2>

			<?php foreach($categoryidcaArray as $categoryid){
				$category_sql="SELECT * from category WHERE id = '".$categoryid."' and is_active = '1' ";
				$category_res=mysql_query($category_sql, $db->conn);
				$CategoryDetail=mysql_fetch_assoc($category_res);

				$sql="SELECT * from categories WHERE parent_id = '".$categoryid."' and is_active = '1' order by id DESC";
				$res=mysql_query($sql, $dbca->conn);
				while($subCategories =mysql_fetch_assoc($res)){
					$subCategoriescaArray[]=$subCategories;
				}
				?>
				<br class="clear" />
				<p>Select Sub Categories of <b><?php if(!empty($CategoryDetail['category_title'])) { echo ucwords($CategoryDetail['category_title']);} ?></b> for <b>California</b> to display the form<em>*</em></p>
				<div style="margin: 12px 0px;padding:5px 0px 10px 0px;max-height:200px;overflow: auto;width: 344px;border: 1px solid #cccccc;">
					<?php 
					if(!empty($subCategoriescaArray)) { ?>
					<div class="pL10">
						<table border="0" cellpadding="4">
							<?php foreach($subCategoriescaArray as $key => $categoryDetail){ ?>
							<tr>
								<td width="10%">
									<input class="required" type="radio" name="categories[<?php echo $categoryid;?>][rand_california]" value="<?php echo $categoryDetail['id']; ?>"/>
								</td>
								<td><?php echo $categoryDetail['category_title']; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
					<?php } else {
						echo 'No Subcategory added';
					} ?>
				</div>

			<?php } ?>

		</div>

	<?php } ?>

<?php } else { } ?>