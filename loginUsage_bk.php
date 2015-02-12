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
$admin = new admin();
$user = new user();
global $db;
$cat = '';
$queryStr = '';
$all_ips = $all_dates = array();

if(isset($_SESSION['user']) && $_SESSION['user']['id']!=''){
	$userid = $_SESSION['user']['id'];

	$all_history = $user->showAllLoginHistory($user_id);
	if(isset($all_history) and count($all_history)>0){
		foreach($all_history as $key=>$historyInfo){
			$history[] = $historyInfo;
			if(!in_array($historyInfo['ip'],$all_ips)){
				$all_ips[] = $historyInfo['ip'];
			}
			sort($all_ips);
			$date = substr($historyInfo['accessed_on'],0,-9);
			if(!in_array($date,$all_dates)){
				$all_dates[] = $date;
			}
			sort($all_dates);
		}
	}

	if(isset($_REQUEST['ip_adr'])) {
		$ip_adr	= $_SESSION['ip_adr'] = trim($_REQUEST['ip_adr']);
	} else if(isset($_SESSION['ip_adr'])) {
		$ip_adr    = $_SESSION['ip_adr'];
	} else {
		$ip_adr = 0;
	}

	if(isset($_REQUEST['start_date'])) {
		$start_date	= $_SESSION['start_date'] = trim($_REQUEST['start_date']);
	} else if(isset($_SESSION['start_date'])) {
		$start_date    = $_SESSION['start_date'];
	} else {
		$start_date    = '';
	}

	if(isset($_REQUEST['end_date'])) {
		$end_date	= $_SESSION['end_date'] = trim($_REQUEST['end_date']);
	} else if(isset($_SESSION['end_date'])) {
		$end_date    = $_SESSION['end_date'];
	} else {
		$end_date    = '';
	}

	//header('location: loginUsage.php');

	$allHistorySql	=	$user->showLoginHistory($userid,$ip_adr,$start_date, $end_date);
	$allHistory_obj	=	new PS_Pagination($db->conn, $allHistorySql, 15, 5, $queryStr);
	$history	=	$allHistory_obj->paginate();
	$total_rows		=	$allHistory_obj->total_rows;
} 

 //echo '<pre>';print_r($all_ips);echo '</pre>';
//unset($_SESSION['end_date']);
//unset($_SESSION['start_date']);
//unset($_SESSION['ip_adr']);
?>

 <!-- container -->
<section id="container">
 <!-- main div -->
	<section id="inner-content" class="conatiner-full" >
	<h2>Site Accessed Detail</h2><br/>
	<div id="" class="right pR10">
		<div class=" left"><span class="listform">Search:&nbsp;&nbsp;</span></div>
		<div class=" left">
			<form method="post" action="" name="search_history" id="search_history">
			<select name="ip_adr" id="ip_adr">
				<option value="">Select IP</option>
				<?php foreach($all_ips as $ipKey){?>
					<option <?php if(isset($_SESSION['ip_adr']) && $_SESSION['ip_adr'] == $ipKey) {?> selected="selected" <?php } ?>value="<?php echo $ipKey;?>"><?php echo $ipKey;?></option>
				<?php } ?>
			</select>&nbsp;&nbsp;
			<select name="start_date" id="start_date">
				<option value="">Select start date</option>
				<?php foreach($all_dates as $dateKey){?>
					<option <?php if(isset($_SESSION['start_date']) && $_SESSION['start_date'] == $dateKey) {?> selected="selected" <?php } ?>value="<?php echo $dateKey;?>"><?php echo $dateKey;?></option>
				<?php } ?>
			</select>&nbsp;&nbsp;
			<select name="end_date" id="end_date">
				<option value="">Select end date</option>
				<?php foreach($all_dates as $dateKey){?>
					<option <?php if(isset($_SESSION['end_date']) && $_SESSION['end_date'] == $dateKey) {?> selected="selected" <?php } ?>value="<?php echo $dateKey;?>"><?php echo $dateKey;?></option>
				<?php } ?>
			</select>						
			<input type="submit" value="Go" class="mL10" id="submitHistory" name="search">
			</form>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					
					jQuery('#submitHistory').click(function(){
						var ipAdr = jQuery('#ip_adr').val();
						var startDate = jQuery('#start_date').val();
						var endDate = jQuery('#end_date').val();
						if(ipAdr=='' && startDate=='' && endDate==''){
							alert('Please select atleast one criteria to search.');
						}
						jQuery('#search_history').submit();			
					});
					jQuery('#start_date').change(function(){
						var startDate = jQuery('#start_date').val();
						var endDate = jQuery('#end_date').val();
						if(startDate!='' && endDate!=''){
							if(endDate < startDate){
								alert('Start Date should be less than End Date');
								return false;
							}
						}
					});
					jQuery('#end_date').change(function(){
						var startDate = jQuery('#start_date').val();
						var endDate = jQuery('#end_date').val();
						if(startDate!='' && endDate!=''){
							if(endDate < startDate){
								alert('Start Date should be less than End Date');
								return false;
							}
						}
					});
				});
			</script>
		</div>							
	</div>
	<br class="clear"/><br class="clear"/>
		<table class="data-table" id="grid_view">
			<tr class="">
			<th class="thead_gr s11x">IP Address</th>
			<th class="thead_gr s11x">Accessed URL</th>
			<th class="thead_gr s11x">Accessed On</th>
			</tr>
	<?php  if(isset($total_rows) and $total_rows>0){
		while($historyDetail = mysql_fetch_assoc($history)){
			//foreach($history as $key =>$historyDetail){
		
		$dbname			= stripslashes($historyDetail['ip']);
		$accessed_url	= stripslashes($historyDetail['accessed_url']);
		$accessed_on	= stripslashes($historyDetail['accessed_on']);	
		?>
			<tr class="">
					<td valign="top" class="ip"><?php echo $dbname; ?></td>
					<td valign="top" class="dbname"><?php echo $accessed_url; ?></td>
					<td valign="top" class="accessed_on"><?php echo date('j M Y, H:i:s',strtotime($accessed_on)); ?></td>
					
				</tr><?php } ?>
				<tr>	
					<td bgcolor="#eeeeee" colspan="11">
						<!-- Pagination ----------->                      
						<div class="txtcenter pR20 pagination">
							<?php echo $allHistory_obj->renderFullNav();  ?>
						</div>
						<!-- /Pagination -----------> </td>
				</tr>
	<?php }else{?>
		<tr><td>No records found for this search.</td></tr>
	<?php } ?>
		</table>
		
	
	</section>
		
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>