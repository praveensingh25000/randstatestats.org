<?php
if(isset($databaseDetail) && !empty($databaseDetail)) { ?>
	<div class="tabnav pT10">
		<a <?php if(!isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab']=='1')) { echo 'class="active"';}?> href="database.php?tab=1&action=edit&id=<?php echo base64_encode($dbid); ?>">General Details</a>&nbsp;&nbsp;<a <?php if(isset($_GET['tab']) && $_GET['tab']=='2'){ echo 'class="active"';}?> href="database.php?tab=2&action=edit&id=<?php echo base64_encode($dbid); ?>">Graphical Interface</a>&nbsp;&nbsp;
		
		<a <?php if(isset($_GET['tab']) && ($_GET['tab']=='3' || $_GET['tab']=='4'  )){ echo 'class="active"';}?> href="associatedTables.php?tab=3&id=<?php echo base64_encode($dbid); ?>">Associated Table & Data</a>&nbsp;&nbsp;
		
		<?php if(isset($databaseDetail) && !empty($databaseDetail) && $databaseDetail['is_static_form'] != 'Y'){ ?>	
		<a <?php if(isset($_GET['tab']) && $_GET['tab']=='5'){ echo 'class="active"';}?> href="searchCriteria.php?tab=5&id=<?php echo base64_encode($dbid); ?>">Search Criteria</a>&nbsp;&nbsp;
		<?php } ?>

		<a <?php if(isset($_GET['tab']) && $_GET['tab']=='6'){ echo 'class="active"';}?> href="timeInterval.php?tab=6&id=<?php echo base64_encode($dbid); ?>">Time Interval</a>&nbsp;&nbsp;

		<!-- <a <?php if(isset($_GET['tab']) && $_GET['tab']=='7'){ echo 'class="active"';}?> href="columnSettings.php?tab=7&id=<?php echo base64_encode($dbid); ?>">Column Display Settings</a>&nbsp;&nbsp; -->

		<a <?php if(isset($_GET['tab']) && $_GET['tab']=='8'){ echo 'class="active"';}?> href="contactResources.php?tab=8&id=<?php echo base64_encode($dbid); ?>">Contact Resources</a>&nbsp;&nbsp;


		<?php if(isset($databaseDetail) && !empty($databaseDetail) && $databaseDetail['is_static_form'] == 'Y'){ ?>

			<a <?php if(isset($_GET['tab']) && $_GET['tab']=='8'){ echo 'class="active"';}?> href="formContent.php?tab=8&id=<?php echo base64_encode($dbid); ?>">Manage Form Content</a>&nbsp;&nbsp;

		<?php } ?>

		<?php if(isset($databaseDetail['share_status']) && !empty($databaseDetail) && $databaseDetail['share_status'] == '1' && $_SESSION['databaseToBeUse']=='rand_usa') { ?>

			<a <?php if(isset($_GET['tab']) && $_GET['tab']=='9'){ echo 'class="active"';}?> href="shareForm.php?tab=9&action=share&id=<?php echo base64_encode($dbid); ?>">Sharing</a>

		<?php } ?>
	</div>
	<br class="clear" />
<?php } ?>