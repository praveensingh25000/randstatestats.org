<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

$region = '';
$areaArray = explode(',', $_SESSION['search']['blah']);
foreach($areaArray as $key => $regionname){
	$region .= "'".$regionname."',";
}

$region = substr($region, 0 , -1);

$asylem = new asylem();

$resource = $asylem->getResultRegion($region);

$resourceAll = $asylem->getResultRegionAll();

$searchedTotalRows = $db->count_rows($resource);

$totalRows = $db->count_rows($resourceAll);

$array = array();

$yearArray = array();
for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){ 
	$yearArray[] = $i;
}

if($searchedTotalRows >0){ 
	$searchedData = $db->getAll($resource);
}

$columnsYear = array();
if($searchedTotalRows >0){ 
	foreach($searchedData as $keyRegion => $rowData){
		if(empty($columnsYear)){
			foreach($rowData as $keyColumn => $value){
				$columnsYear[] = $keyColumn;
			}
			break;
		}
	}
}

$yearjson = json_encode($yearArray);

$columsnData = array();
if($searchedTotalRows >0){ 
	foreach($searchedData as $keyRegion => $rowData){
		
		$columsnData[$keyRegion] = array();
		for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){
			if(in_array($i, $columnsYear)){ 
				if($rowData[$i] == '-'){
					$columsnData[$keyRegion][] = 0;  
				} else {
					$columsnData[$keyRegion][] = (int)$rowData[$i];  
				}
			} else {
				$columsnData[$keyRegion][] = 0; 
			} 
		}
	}
}

$jsoncolumn =  json_encode($columsnData);


$jsoncolumn = substr($jsoncolumn, 0, -1);
$jsoncolumn = substr($jsoncolumn, 1);

$regionjson = json_encode($areaArray);

?>


<!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">
		<!-- left side -->
		
			<h1 class="left">Asylees granted Affirmative Entry by Country of Nationality, <?php echo $_SESSION['search']['syear']; ?>-<?php echo $_SESSION['search']['eyear']; ?> </h1>

			<a class="download right" href="download.php">Download CSV</a>
			<br class="clear" />
			<h3>Your search criteria</h3>
			<p><strong>Country and/or Regions: &nbsp; </strong>  <?php echo $_SESSION['search']['blah']; ?></p>
			<br/>
			<p><strong>Period : &nbsp; </strong><?php echo $_SESSION['search']['syear']; ?> - <?php echo $_SESSION['search']['eyear']; ?> </p>
			<br/>
			<div class="clear"></div>
			<div style="float: left;"><a href="<?php echo URL_SITE; ?>">Search again</a></div>
			<ul class="submenu">
				<li class="current"  id="showGrid"><a href="#gridview">Grid View</a></li>
				<li  id="showChart"><a href="#listview">Chart View</a></li>
			</ul>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#showChart').click(function(){
					var chartShown = jQuery('#chartShown').val();
					jQuery('#grid_view').hide();
					jQuery('#chart_view').show();
					jQuery('#chart_view').addClass('current');
					jQuery('#grid_view').removeClass('current');
				});

				jQuery('#showGrid').click(function(){
					jQuery('#grid_view').show();
					jQuery('#chart_view').hide();
					jQuery('#grid_view').addClass('current');
					jQuery('#chart_view').removeClass('current');
				});

			});
			</script>
			<div class="clear"></div>
			<div id="chart_view" style="display:none">
				<!-- <iframe src="getChart.php" width="850" height="270"></iframe> -->
				<canvas id="cvs" width="800" height="250">[No canvas support]</canvas>

				<script>
					window.onload = function ()
					{
						var line = new RGraph.Line('cvs', <?php echo $jsoncolumn; ?>);
						//line.Set('chart.curvy', true);
						line.Set('chart.key', <?php echo $regionjson; ?>);
					  //  line.Set('chart.curvy.tickmarks', true);
					  //  line.Set('chart.curvy.tickmarks.fill', null);
					  //  line.Set('chart.curvy.tickmarks.stroke', '#aaa');
					   // line.Set('chart.curvy.tickmarks.stroke.linewidth', 2);
						//line.Set('chart.curvy.tickmarks.size', 5);
						line.Set('chart.linewidth', 1);
						line.Set('chart.hmargin', 5);
						line.Set('chart.labels', <?php echo $yearjson; ?>);
						line.Set('chart.tooltips', <?php echo $yearjson; ?>);
						line.Set('chart.tickmarks', 'endcircle');
						line.Draw();
					}
				</script>

			</div>
			
			<input type="hidden" value="0" id="chartShown"/>
			<div class="database">
				<table cellspacing="0" cellpadding="2" border="1" class="collapse" id="grid_view">
				<tbody>
					<tr>
						<th bgcolor="#eeeeee">Area</th>
						<?php for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){ ?>
						<th bgcolor="#eeeeee"><?php echo $i; ?></th>
						<?php } ?>
					</tr>
					<?php if($searchedTotalRows >0){ 
						foreach($searchedData as $keyRegion => $rowData){
					?>
						<tr>
							<td align="left"><font size="2"><?php echo $rowData['Region']; ?></font></td>

							<?php for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){ ?>
							<td align="right">
								<font size="2"><?php if(in_array($i, $columnsYear)){ echo $rowData[$i];  } else { echo "NA"; } ?></font>
							</td>
							<?php } ?>

						</tr>

					<?php } }?>

				</tbody>
				</table>
			</div>

			<p><i><font color="#000000"><?php echo $searchedTotalRows; ?> of <?php echo $totalRows; ?></font> allowed matches returned.</i></p>

			<p>Source: RAND State Statistics (See <a href="/cgi-bin/statlist.cgi?db=popdemo/popest1790.html">Statistics Summary</a> for originating data source.)</p>

			<p>
			<?php echo date('D M d H:i:s Y'); ?>
			</p>

				

				
		 
			<!-- left side -->
		
</section>
<!-- /container -->