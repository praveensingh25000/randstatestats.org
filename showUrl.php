<?php
/******************************************
* @Modified on Jan 11, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

checkSession(false);

global $db;

$admin = new admin();
$user  = new user();

$cat = $queryStr = '';
$totalAccess = 0;
$all_ips = $all_dates = array();


$userid = $_SESSION['user']['id'];


if($_SESSION['user']['parent_user_id']!=0){
	$userid = $_SESSION['user']['parent_user_id'];
}

if(!empty($_SESSION['all_data'])) {
	$ip=$_SESSION['all_data']['ip'];
	$sDate=$_SESSION['all_data']['sdate'];
	$eDate=$_SESSION['all_data']['edate'];
	$urlHistory = $user->showUrlHistory($userid,$ip,$sDate,$eDate);
	//$urlHistory = $user->showLoginHistory($userid,$ip,$sDate, $eDate,'0');
	if(count($urlHistory) <= 0){
		header('location: loginUsage.php');
	}
	//$urlHistory_obj	=	new PS_PaginationArray($url_history, 15, 5, $queryStr);
	//$urlHistory	=	$urlHistory_obj->paginate();


	$totalUsageRow = $user->totalUsage($userid,$sDate,$eDate);
	$totalUsage = $totalUsageRow['totalusage'];

}else{
	header('location: loginUsage.php');
}
//echo '<pre>';print_r($urlHistory);echo '</pre>';

?>

 <!-- container -->
<section id="container">
 <!-- main div -->
	<section id="inner-content" class="conatiner-full" >
	<h2>
		<a href="loginUsage.php">Usage Statistics</a> >> Accessed Database Statistics
	</h2>
	<div id="" class="right pR10">
	<?php if(!empty($urlHistory)) {?>
		<ul class="submenu">
			<!-- PRINT PREVIEW -->
			<li id="print_link" class="">	
				 <div id="aside">
					<!-- <a href="javascript:;" onclick="window.print();">Print</a> -->

					<?php if(isset($_GET['graph']) && ($_GET['graph']=='bargraph')) { ?>
						<a href="javascript:;" id ="graphPrintUrl" >Print</a>
					<?php }  else { ?>
						<a href="javascript:;" id="simplePrintUrl" >Print</a>
					<?php } ?>

				 </div>
			</li>
		</ul>
		<?php } ?>
		<ul class="submenu">
			<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>><a href="?show=grid">Grid View</a>
			</li>

			<?php if(!empty($urlHistory)) {?>
				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='bargraph') echo 'class="current"'; ?>><a href="?graph=bargraph" >Column Graph</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	<br/>
	<strong>For IP = <?php echo $_SESSION['all_data']['ip'];?> <br/> Accessed Period = <?php echo date('M d, Y',strtotime($_SESSION['all_data']['sdate']));?></strong> To <strong><?php echo date('M d, Y',strtotime($_SESSION['all_data']['edate']));?>
	<br/>
	</strong>

	<?php if(!isset($_GET['graph'])){?>

	<!-- <h3 align="middle">Number of Accesses = <span id="accessTotal"><?php echo $totalAccess;?></span></h3>
	<br/> -->
	<br class="clear"/>	
	<div class='toPrintGrid' id="toPrintGrid">
		<table class="data-table" id="grid_view">
			<tr class="">
			<th class="thead_gr s11x">Accessed Database</th>
			<th class="thead_gr s11x">Section (Category)</th>
			<th class="thead_gr s11x">Number of Searches</th>
			<!-- <th class="thead_gr s11x">Accessed On</th> -->
			</tr>
			<?php   $i = 0;

			if(isset($urlHistory) and is_array($urlHistory) and count($urlHistory)>0)
			{
				foreach($urlHistory as $dbkey =>$urlDetail)
				{
					$totalAccessDB = 0;
					$siteDBDetail = $admin->selectDatabases($dbkey);
					$dbName = $siteDBDetail['databasename'];
					$dbLabel = $siteDBDetail['database_label'];
					?>
					<tr>
						<td colspan="2">
							<h3><?php echo $dbLabel;?> Total</h3>
						</td>
						<td><h3><span id="dbAccess_<?php echo $dbkey;?>"><?php echo $totalAccessDB?></span></h3></td>
					</tr><?php $count= 0;
					foreach($urlDetail as $urlKey=>$timeDetail){
						$count = count($timeDetail);

						$totalAccess += $count;

						/*foreach($timeDetail as $key=>$timeVal){
							$time = $timeVal;
						}*/

						$explode = explode('.org/',$urlKey);
						
							$explodedUriMain =  substr($explode['1'],0);
						
							$exploded = explode('?id=',$explodedUriMain);
						
							if($exploded['0']=='form.php'){
								$url = '';
								$dbid = trim(base64_decode($exploded['1']));
								$databaseDetail = $user->urlFormName($dbName,$dbid,$url);
							}else{
								$dbid = '';
								$url = trim($exploded['0']);
								$databaseDetail = $user->urlFormName($dbName,$dbid,$url);
							}
							$catDetail = $user->getDbCategories($dbName,$databaseDetail['id']);				
							foreach($catDetail as $catKey=>$category){
								if($category['cat_type']=='s'){
									$subcat = $user->getSubCatName($dbName,$category['category_id']);
								}elseif($category['cat_type']=='p'){
									$parentcat = $user->getParentCatName($category['category_id']);
								}
							}
					?>
					<tr class="">
						<td valign="top" class="url"><?php if(isset($databaseDetail['db_name'])){echo $databaseDetail['db_name']; }else{echo $urlKey;}?></td>
						<td valign="top" class="ip"><?php if(isset($parentcat['category_title'])){ echo $parentcat['category_title'].' ('.$subcat['category_title'].')'; } ?></td>
						<td valign="top" class="ip"><?php echo $count; ?></td>
						<!-- <td valign="top" class="accessed_on"><?php echo date('M d, Y, H:i:s',strtotime($time)); ?></td> -->
						
					</tr>
						
						<?php $totalAccessDB += $count;
					}
					?>
					<script>
						document.getElementById("dbAccess_<?php echo $dbkey;?>").innerHTML="<?php echo $totalAccessDB;?>";
					</script>
						
				<?php }
				
				?>
				<script>
					document.getElementById("accessTotal").innerHTML="<?php echo $totalAccess;?>";
				</script>
					<tr>	
						<td bgcolor="#eeeeee" colspan="11">
							<!-- Pagination ----------->                      
							<div class="txtcenter pR20 pagination">
								<?php //echo $urlHistory_obj->renderFullNav();  ?>
							</div>
							<!-- /Pagination -----------> </td>
					</tr>
			<?php }
		else{?>
		<tr><td>No records found for this search.</td></tr>
	<?php } ?>
		</table>
		</div>
		<?php }else{ 
			include('graphDbAccess.php');
		}?>
	
	</section>
		
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>