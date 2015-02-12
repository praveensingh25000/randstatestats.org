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

$cat = $queryStr = '';$totalUsage=0;
$all_ips = $all_dates = array();

$userid	= $_SESSION['user']['id'];

if(isset($_GET['setparam'])){
	$_SESSION['all_data']=$_GET;
	header('location:'.URL_SITE.'/showUrl.php');
}

if(isset($_GET['graph'])){
	$qStr = $_GET['graph'];
}

$totalUsageRow = $user->totalUsage($userid);
$totalUsageCount = $totalUsageRow['totalusage'];

if(isset($_POST['search'])){
	if($_POST['start_date']!=''){
		$start_date			= date('Y-m-d',strtotime($_POST['start_date']));
	}else{
		$start_date			= '';
	}
	$_SESSION['start_date'] = $start_date;
	if($_POST['end_date']!=''){
		$end_date			= date('Y-m-d',strtotime($_POST['end_date']));
	}else{
		$end_date			= date('Y-m-d');
	}
	$_SESSION['end_date']	= $end_date;
	if(isset($qStr)){
		header('location:'.URL_SITE.'/loginUsage.php?graph='.$qStr.'');
		exit;
	}else{
		header('location:'.URL_SITE.'/loginUsage.php');
		exit;
	}
	
}else if(!isset($_SESSION['start_date']) && !isset($_SESSION['end_date'])){
	if($_SESSION['user']['parent_user_id']!=0){
		$userid = $_SESSION['user']['parent_user_id'];
	}
	if(!isset($_GET['graph'])){
		$allHistory				= $user->showAllLoginHistory($userid,'0');
	}else{
		$allHistory				= $user->showAllLoginHistory($userid,'1');
	}
}else if(!empty($_SESSION['start_date']) && !empty($_SESSION['end_date'])){
	if($_SESSION['user']['parent_user_id']!=0){
		$userid = $_SESSION['user']['parent_user_id'];
	}
	$start_date = $_SESSION['start_date'];
	$end_date = $_SESSION['end_date'];
	if(!isset($_GET['graph'])){
		$allHistory				=			$user->showLoginHistory($userid,$ip_adr='',$start_date, $end_date,'0');
	}else{
		$allHistory				=	$user->showLoginHistory($userid,$ip_adr='',$start_date, $end_date,'1');
	}
}

//echo '<pre>';print_r($allHistory);echo '</pre>';

//if(isset($allHistory))
$allHistory_obj	=	new PS_PaginationArray($allHistory, 10, 5, $queryStr);
$all_history	=	$allHistory_obj->paginate();
?>

<!-- container -->
<section id="container">
 <!-- main div -->
	<section id="inner-content" class="conatiner-full" >
	<h2 class="left">Usage Statistics</h2>
	<div id="" class="right pR10">
	<?php if(!empty($all_history)) {?>
		<ul class="submenu">
			<!-- PRINT PREVIEW -->
			<li id="print_link" class="">	
				 <div id="aside">
					<!-- <a href="javascript:;" onclick="window.print();">Print</a> -->

					<?php if(isset($_GET['graph']) && ($_GET['graph']=='linegraph' || $_GET['graph']=='bargraph')) { ?>
						<a href="javascript:;" id ="graphPrintStats" >Print</a>
					<?php }  else { ?>
						<a href="javascript:;" id="simplePrintStats" >Print</a>
					<?php } ?>

				 </div>
			</li>
		</ul>
		<?php } ?>
		<ul class="submenu">
			<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>><a href="?show=grid">Grid View</a>
			</li>

			<?php if(!empty($all_history)) {?>
				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='linegraph') echo 'class="current"'; ?>><a href="?graph=linegraph" >Line Graph</a>
				</li>
				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='bargraph') echo 'class="current"'; ?>><a href="?graph=bargraph" >Column Graph</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	<br class="clear"/>
	<br class="clear"/>
	<div class="left">
		
		<?php 
		if(!isset($_SESSION['start_date']) && !isset($_SESSION['end_date'])){ ?>
			<b>Usage Statistics Period:</b> <b><?php echo date("j M Y", strtotime(date("Y-m-d")."-1 month"));?></b> to <b><?php echo date('j M Y');?></b>
		<?php }else{?>
			<b>Usage Statistics Period:</b> <b><?php echo date("j M Y", strtotime($_SESSION['start_date']));?></b> to <b><?php echo date('j M Y',strtotime($_SESSION['end_date']));?></b>
			<?php }?>
		<br/>
		<br/>

		<form method="post" action="" name="search_history" id="search_history">
			<b>Analyze:</b>&nbsp;&nbsp;From&nbsp;
			<input max="<?php echo date('Y-m-d');?>" name="start_date" type="date" id="start_date" placeholder="Start Date" class="required" value="<?php if(isset($_SESSION['start_date'])){echo date('j M Y',strtotime($_SESSION['start_date']));}else{echo date("j M Y", strtotime(date("Y-m-d")."-1 month"));}?>"> &nbsp;To&nbsp;
				
			<input max="<?php echo date('Y-m-d');?>" name="end_date" id="end_date" type="date" value="<?php if(isset($_SESSION['end_date'])){echo date('j M Y',strtotime($_SESSION['end_date']));}else{ echo date('j M Y',strtotime(date('Y-m-d')));}?>"  placeholder="End Date">
										
			<input type="submit" value="Go" class="mL10" id="submitHistory" name="search">
		</form>
		<br/>
	</div>	
	<br class="clear"/>
	<?php   
	if(!isset($_GET['graph'])){?>
	<!-- <b>Total Usage = </b><span id=""><?php echo $totalUsageCount;?></span><br/> -->
	<b>Number of searches = </b><span id="totalUsage"><?php echo $totalUsage;?></span>
	<br class="clear"/>
	<br class="clear"/>
	<form action="showUrl.php" method="post" name="urlDataForm" id="urlDataForm">
		<table class="data-table toPrintGraph" id="grid_view">
			<tr class="">
				<th class="thead_gr s11x">IP Address</th>
				<th class="thead_gr s11x">Number of Searches</th>
				<!-- <th class="thead_gr s11x">Date</th> -->
			</tr>
	<?php
		if(!empty($all_history)) {
		$j = 0;
			foreach($all_history as $ipkey =>$ipDetail){
				$count= 0;
				foreach($ipDetail as $ipAdrkey =>$dateDetail){	
					$count += count($dateDetail);
				}
					?>
					<tr class="">
						<td valign="top" class="ip"><?php echo $ipkey; ?></td>
						<td valign="top" class="dbname"><a href="javascript:;" id="access_no_<?php echo $j;?>"><?php echo $count; ?></a></td>
						<!-- <td valign="top" class="accessed_on"><?php echo $date; ?></td> -->
						<input id="ip_no_<?php echo $j;?>" type="hidden" name="ip<?php echo $j;?>" value="<?php echo $ipkey; ?>">
						<?php 
							if(!isset($_SESSION['start_date']) && !isset($_SESSION['end_date'])){ 
								$s_date = date("Y-m-d",strtotime(date("Y-m-d")."-1 month"));
								$e_date= date('Y-m-d');
							}else{
								$s_date = $_SESSION['start_date'];
								$e_date= $_SESSION['end_date'];?></b>
						<?php }?>
						<input id="sdate_<?php echo $j;?>" type="hidden" name="sdate_<?php echo $j;?>" value="<?php echo $s_date; ?>">
						<input id="edate_<?php echo $j;?>" type="hidden" name="edate_<?php echo $j;?>" value="<?php echo $e_date; ?>">

					</tr>
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery('#access_no_<?php echo $j;?>').click(function(){
							var ip   = jQuery("#ip_no_<?php echo $j;?>").val();
							var sdate = jQuery("#sdate_<?php echo $j;?>").val();
							var edate = jQuery("#edate_<?php echo $j;?>").val();
							if(ip!=''){
								window.location=URL_SITE+'/loginUsage.php?setparam=1&ip='+ip+'&sdate='+sdate+'&edate='+edate;
							}
						});
					});
					</script>

					<?php 
						$j++;
					$totalUsage += $count;
				}
				unset($_SESSION['start_date']);
				unset($_SESSION['end_date']);
				?>
				<tr>	
					<td bgcolor="#eeeeee" colspan="11">
						<!-- Pagination ----------->                      
						<div class="txtcenter pR20 pagination">
							<?php echo $allHistory_obj->renderFullNav();  ?>
						</div>
						<!-- /Pagination -----------> </td>
				</tr>
	<?php }else{
			if(isset($_SESSION['start_date']) || isset($_SESSION['end_date'])){
				unset($_SESSION['start_date']);
				unset($_SESSION['end_date']);
			}?>
		<tr><td colspan="3" align="middle"><strong>No records found.</strong></td></tr>
	<?php } ?>
		</table>
		</form>
		<script>
			document.getElementById("totalUsage").innerHTML="<?php echo $totalUsage;?>";
		</script>

		<?php  
		}else{
			//include('historyGraph.php');
			include('historyGraph.php');
		} ?>
	
	</section>

	<script type="text/javascript">
	//added by pragati garg on Aug 28, 2013.
	jQuery(document).ready(function(){

		jQuery('#start_date').dateinput({			
			format: 'dd mmm yyyy',// the format displayed for the use
			selectors: true,// whether month/year dropdowns are shown
			//min: -500,    // min selectable day (100 days backwards)
			//max: "<?php echo date('Y-m-d');?>",// max selectable day (100 days onwards)
			offset: [10, 20],  // tweak the position of the calendar
			speed: 'fast',   // calendar reveal speed
			firstDay: 1 // which day starts a week. 0 = sunday, 1 = monday etc.					
		});

		jQuery('#end_date').dateinput({
			format: 'dd mmm yyyy',
			selectors: true,
			//min: -500,
			offset: [10, 20],
			speed: 'fast',
			firstDay: 1 
			
		});

		jQuery('#start_date').change(function(){
			var startDate = jQuery('#start_date').val();
			var endDate = jQuery('#end_date').val();
			tstart = new Date(startDate);
			tend = new Date(endDate);
			if(startDate!='' && endDate!=''){
				if(tend < tstart){
					alert('Start Date should be less than End Date');
					jQuery('#start_date').val('');
					return false;
				}
			}
		});

		jQuery('#end_date').change(function(){
			var startDate = jQuery('#start_date').val();
			var endDate = jQuery('#end_date').val();
			tstart = new Date(startDate);
			tend = new Date(endDate);
			if(startDate!='' && endDate!=''){
				if(tend < tstart){
					alert('Start Date should be less than End Date');
					jQuery('#end_date').val('');
					return false;
				}
			}
		});

		jQuery('#submitHistory').click(function(){
			var startDate = jQuery('#start_date').val();
			var endDate   = jQuery('#end_date').val();
			tstart = new Date(startDate);
			tend = new Date(endDate);
			if(startDate!='' && endDate!=''){
				if(tend < tstart){
					alert('Start Date should be less than End Date');
					jQuery('#start_date').val('');
					jQuery('#end_date').val('');
					return false;
				}
			}else if(startDate==''){
				alert('Please select Start Date to search');
				jQuery('#start_date').val('');
				return false;
			}else if(startDate!='' || endDate!=''){
				jQuery('#search_history').submit();
			}
		});

		$("#simplePrintStats").click(function() {
			printGraphElem({ overrideElementCSS: true });
		});

		function printGraphElem(options){
		$('.toPrintGraph').printElement(options);
	} 
	});
	</script>
		
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>