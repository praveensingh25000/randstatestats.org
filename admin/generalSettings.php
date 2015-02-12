<?php
/******************************************
* @Modified on Dec 19, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
checkSession(true);

$groupid = '';

if(isset($_GET['groupid']) && $_GET['groupid'] != '' && is_numeric($_GET['groupid'])){
	$groupid = $_GET['groupid'];
}
$generalSettings = fetchGenralSettings($groupid);

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

<?php
	foreach($generalSettings as $key => $groupsdata){
		echo'<pre>';
		//print_R($groupsdata);	
		echo'</pre>';
?>
	
		<div>
		<h2 class="left"><?php if(isset($groupsArray[$key])) { echo $groupsArray[$key]; } ?></h2>
		<div id="" class="right pT15">		
			<a href="editSetting.php?group=<?php echo $key; ?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:window.history.go(-1)">Back</a>
		</div>
		<div class="clear"></div>
		<hr>
	</div>

		<table class=""  width="100%">
<?php
		foreach($groupsdata as $key1 => $setting){
			$name = $setting['name'];
			$text = $setting['text'];
			$value = $setting['value'];
			
?>
			<tr>
				<td class="first" valign="top" width="30%"><?php echo $text; ?> <?php if($name=='Validity') { echo '&nbsp;in Days';};?></td>
				<td >
				<?php 
				if($name == 'site_logo'){
				?>
				<img src = "<?php echo $URL_SITE; ?>rand/images/<?php echo $value; ?>" width="100" height="100"/>
				<?php
				} elseif($name=='Validity')	{
					echo $value; 
				} else {
					echo $value; 
				}
				?></td>
			</tr>
<?php			
		}
?>
</table>
<?php
	}
?>

 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>
