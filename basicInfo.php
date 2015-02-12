<?php 
$_SESSION['databaseDetail'] = $databaseDetail;

$description = str_replace('http://50.62.142.193', 'http://randstatestats.org', $description);
if(isset($dbsource) && $dbsource!=''){ ?><p><strong>Summary: </strong> <?php echo stripslashes($dbsource); ?></p><?php } 

if(isset($description) && $description!=''){?>

<div class="additional">
	<div id="add">
		<a href="javascript:;" title="Click to expand section" class="plus" id="togglebutton"></a>
		<a href="javascript:;">Additional Background</a>
	</div>
	<!-- Hide - show div -->
	<div class="additional-deatil" id="additional-deatil">
		<p><?php echo ucfirst($description); ?> </p>
		<p><?php echo ucfirst($miscellaneous); ?> </p>
	</div>
	<!-- / Hide - show div -->
	<!-- Time -->
	<div class="clear"></div>
	<!-- /Time -->
</div>
<?php } ?>
<?php if(isset($db_geographic) && $db_geographic!=''){ ?><p class="pB5"><strong>Geographic Coverage: </strong> <?php echo stripslashes($db_geographic); ?></p><?php } ?>
<?php if(isset($db_periodicity) && $db_periodicity!=''){ ?><p class="pB5"><strong>Periodicity: </strong> <?php echo stripslashes($db_periodicity); ?></p><?php } ?>
<?php if(isset($db_dataseries) && $db_dataseries!=''){ ?><p class="pB5"><strong>Series Begins/Ends: </strong> <?php echo stripslashes($db_dataseries); ?></p><?php } ?>


<?php if(isset($db_datasource) && $db_datasource!=''){ ?>
<p class="pB5"><strong>Data Source: </strong><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a target="_blank" href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo stripslashes($db_datasource); ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
</p>
<?php } ?>

<div class="time-div" style="padding-top:5px;">
	<?php if($dateupdated!='0000-00-00') { ?>
		<span class="">
			<b>Updated:&nbsp;</b>
			<?php 
			//echo date('M d, Y', strtotime($dateupdated));
			$day   = date('d',strtotime($dateupdated));
			$month = date('F',strtotime($dateupdated));
			$year  = date('Y',strtotime($dateupdated));

			if(isset($month) && strlen($month) > 3) {
				$monthshow=ucwords(substr($month,0,3)).'.';
			} else {
				$monthshow=ucwords($month);
			}

			echo $monthshow.' '.$day.', '.$year;
			?> 
		</span> 
	<?php }else{ ?>
		<span class="">
			<b>Updated:&nbsp;</b>
			<?php 
				echo 'None';
			?>
		</span> 
		<?php
			}?>
	
	<?php if($nextupdate!='0000-00-00' && $nextupdate!='') { ?>
	<span class="pL20"><b>Next update: </b>
		<?php 
		if (DateTime::createFromFormat('Y-m-d H:i:s', $nextupdate) != false) {
			
			//echo $nxupdate = date('M d, Y', strtotime($nextupdate));
			
			$day   = date('d',strtotime($nextupdate));
			$month = date('F',strtotime($nextupdate));
			$year  = date('Y',strtotime($nextupdate));

			if(isset($month) && strlen($month) > 3) {
				$monthshow=ucwords(substr($month,0,3)).'.';
			} else {
				$monthshow=ucwords($month);
			}

			echo $monthshow.' '.$day.', '.$year;		
		} else {
			//echo $nxupdate = date('M d, Y', strtotime($nextupdate));
			$day   = date('d',strtotime($nextupdate));
			$month = date('F',strtotime($nextupdate));
			$year  = date('Y',strtotime($nextupdate));

			if(isset($month) && strlen($month) > 3) {
				$monthshow=ucwords(substr($month,0,3)).'.';
			} else {
				$monthshow=ucwords($month);
			}

			echo $monthshow.' '.$day.', '.$year;
		} 
		?>
	</span> 
	
		<?php if(isset($_SESSION['user'])) { ?>
		<span class="pL10 font10">
			<a id="notify_click" href="javascript:;"><img alt="Notify" src="<?php echo URL_SITE;?>/images/notify.png"></a>
			<?php include($DOC_ROOT."/notify.php"); ?>
		</span>
		<script type="text/javascript">
		jQuery('#notify_click').click(function() {	
				
			//Fade in the Popup and add close button				
			jQuery(".notify-box").fadeIn(300);
			
			//Set the center alignment padding + border
			var popMargTop = (jQuery(".notify-box").height() + 100) / 2; 
			var popMargLeft = (jQuery(".notify-box").width() + 100) / 2; 
			
			jQuery(".notify-box").css({ 
				'margin-top' : -popMargTop,
				'margin-left' : -popMargLeft
			});
			
			// Add the mask to body
			jQuery('body').append('<div id="mask"> </div>');
			jQuery('#mask').fadeIn(300);				
			
			return false;
		});
		</script>
		<?php } ?>
	<?php }else{ ?>
	<span class="pL20"><b>Next update: </b>
		<?php 
			echo 'None';
		}?>
</div>