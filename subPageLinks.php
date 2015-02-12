<div id="" class="right">
	<ul class="submenu">
		<!-- DOWNLOAD ------>
		<li id="download_link" class="">						
			<a href="<?php echo URL_SITE; ?>/downloadDynamic.php?type=csv">CSV</a>							
		</li>	

		<li id="download_link" class="">						
			<a href="<?php echo URL_SITE; ?>/downloadDynamic.php?type=excel">EXCEL</a>						
		</li>	
		<!-- DOWNLOAD ------->

		<!-- PRINT PREVIEW -->
		<li id="print_link" class="">	
			 <div id="aside">
				<!-- <a href="javascript:;" onclick="window.print();">Print</a> -->

				<?php if(isset($_GET['graph']) && ($_GET['graph']=='linegraph' || $_GET['graph']=='bargraph')) { ?>
					<a href="javascript:;" id = "graphPrint" >Print</a>
				<?php }  else { ?>
					<a href="javascript:;" id="simplePrint" >Print</a>
				<?php } ?>

			 </div>
		</li>
		
		<!-- SHARING PAGE -->			
		<li class=""><span class='st_sharethis_custom'>&nbsp;</span>
		</li>
		<!-- /SHARING PAGE -->	

	</ul>
	<!-- <div class="clear pT10"> </div> -->
	<!-- /PAGE LINKS -->
	<?php 
	
	if(!isset($nograph) || (isset($nograph) && !$nograph)){
		if(isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){ 	?>
		<!-- LINE PIE BAR CHART ------------------------->
		<ul class="submenu">
			<?php if(isset($_REQUEST['dbc']) && $_REQUEST['dbc']!='') { ?>

				<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>> <a href="?show=grid&dbc=<?php echo $_REQUEST['dbc'];?>">Grid View</a></li>
				
				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='linegraph') echo 'class="current"'; ?>><a href="?graph=linegraph&dbc=<?php echo $_REQUEST['dbc'];?>" >Line Graph</a>
				</li>

				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='bargraph') echo 'class="current"'; ?>><a href="?graph=bargraph&dbc=<?php echo $_REQUEST['dbc'];?>" >Column Graph</a>
				</li>

			<?php } else { ?>

				<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>><a href="?show=grid">Grid View</a></li>		
				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='linegraph') echo 'class="current"'; ?>><a href="?graph=linegraph" >Line Graph</a>
				</li>

				<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='bargraph') echo 'class="current"'; ?>><a href="?graph=bargraph" >Column Graph</a>
				</li>

			<?php } ?>
						
		</ul>
	<?php }
	}
	?>

</div>
<div class="clear pT10"></div>