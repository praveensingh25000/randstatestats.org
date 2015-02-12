<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

checkSession(false);

//checking user is loggedin or not with alert message
//checksession_with_message($sessionType=false,$redirectpage='login.php',$messagetype='msgalert',$msgnumber='5');

if(!isset($_GET['graph'])) { $_GET['graph']='gridview';}

if(isset($_SESSION['search']['dbid']))$dbid=$_SESSION['search']['dbid'];else$dbid=1;

$region = '';
$areaArray = explode(';', $_SESSION['search']['blah']);

foreach($areaArray as $key => $regionname){
	$region .= "'".$regionname."',";
}

$region = substr($region, 0 , -1);

$asylem = new asylem();
$user = new user();

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
		for($i=$_SESSION['searchedfields']['syear'];$i<=$_SESSION['searchedfields']['eyear'];$i++){
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
			
			<!-- PAGE LINKS -->
			<div id="" class="right">
				<ul class="submenu">
					<!-- DOWNLOAD ------>
					<li id="download_link" class="">						
						<a href="download.php?type=csv">CSV</a>										
					</li>	
					<li id="download_link" class="">						
						<a href="download.php?type=excel">EXCEL</a>						
					</li>	
					<!-- DOWNLOAD ------->

					<!-- PRINT PREVIEW -->
					<li id="print_link" class="">	
						 <div id="aside">
							<a href="javascript:;" onclick="window.print();">Print</a>
						 </div>
					</li>
					<!-- <script type="text/javascript">
						$(function() {
							// Add link for print preview and intialise
							var checkdiv=$("#check_div").hasClass('print-preview');
							if(checkdiv==false)			
							$('#aside').prepend('<a href="javascript:;" id="check_div" class="print-preview">Print this page</a>');			
							$('a.print-preview').printPreview();		
						});	
					</script> -->
					<!-- PRINT PREVIEW -->		

					<!-- SHARING PAGE -->	
					<li class=""><span class='st_sharethis_hcount'></span></li>
					<!-- /SHARING PAGE -->	
				</ul>
					
			</div>			
			<br class="clear" />
			<!-- /PAGE LINKS -->			

			<h3>Your search criteria</h3>
			<p><strong>Country and/or Regions: &nbsp; </strong>  <?php echo $_SESSION['search']['blah']; ?></p>
			<br/>
			<p><strong>Period : &nbsp; </strong><?php echo $_SESSION['search']['syear']; ?> - <?php echo $_SESSION['search']['eyear']; ?> </p>
			<br/>
			<div class="clear"></div>
			<div class="left"><a class="Signupbtn" href="<?php echo URL_SITE; ?>">Search again</a></div>
			
			<!-- LINE PIE BAR CHART ------------------------->
			<ul class="submenu">
				<li id="showGrid" <?php if(isset($_GET['graph']) && $_GET['graph']=='gridview') echo 'class="current"'; ?>><a href="?graph=gridview">Grid View</a></li>		
				<?php //$selectChartDetailArray = $user->selectChartDetail($dbid);
				//$selectChartDetail=explode(',',$selectChartDetailArray['db_graph']);
				$selectChartDetail = array();
				if(!empty($selectChartDetail)){
					foreach($selectChartDetail as $values){?>
					<li id="showChart" <?php if(isset($_GET['graph']) && $_GET['graph']==$values) echo 'class="current"'; ?> ><a href="?graph=<?php echo $values;?>"><?php echo ucwords($values);?>&nbsp;Graph</a>
					<?php }	?></li>
				<?php } ?>				
			</ul>
			<div class="clear"></div>
			<div id="chart_view">
				
				<?php if(isset($_GET['graph']) && $_GET['graph']=='bar') {?> 
				<!-- BAR CHART -->				
				<div id="bar_chart">
					<h3 class="txtcenter">BAR CHART</h3>
					<canvas id="barcanvas" width="800" height="300">[No canvas support]</canvas>	
					 
					<script>
						window.onload = function ()
						{							
							// The data to be shown on the Pie chart
							//var data = <?php echo $jsoncolumn; ?>	
							
							// Create the br chart. The arguments are the ID of the canvas tag and the data
							var bar = new RGraph.Bar('barcanvas', <?php echo $jsoncolumn; ?>);		
							
							// Now configure the chart to appear as wanted by using the .Set() method.
							// All available properties are listed below.
							bar.Set('chart.labels', <?php echo $yearjson; ?>);
							bar.Set('chart.gutter.left', 45);
							bar.Set('chart.background.barcolor1', 'white');
							bar.Set('chart.background.barcolor2', 'white');
							bar.Set('chart.background.grid', true);
							bar.Set('chart.colors', ['red']);							
							
							// Now call the .Draw() method to draw the chart
							bar.Draw();
						}
					</script>

				</div>
				<!-- BAR CHART -->
				<?php } ?>				

				<?php if(isset($_GET['graph']) && $_GET['graph']=='pie') {?> 
				<!-- PIE CHART -->
				<div id="pie_chart">
					<h3 class="txtcenter">PIE CHART</h3>
					<br class="clear" />
					<canvas id="pie_display_canvas" width="850" height="300">[No canvas support]</canvas>	
					<script>
						window.onload = function ()
						{
							// The data to be shown on the Pie chart
							//var data = <?php echo $jsoncolumn; ?>		
						
							// Create the Pie chart. The arguments are the canvas ID and the data to be shown.
							var pie = new RGraph.Pie('pie_display_canvas', <?php echo $jsoncolumn; ?>);

							// Configure the chart to look as you want.
							pie.Set('chart.labels', <?php echo $yearjson; ?>);
							pie.Set('chart.linewidth', 5);
							pie.Set('chart.stroke', 'white');
							
							// Call the .Draw() chart to draw the Pie chart.
							pie.Draw();
						}
					</script>
				</div>
				<!-- PIE CHART -->
				<?php } ?>

				<?php
					// if(isset($_GET['graph']) && $_GET['graph']=='line') {
						?>
				<!-- LINE CHART -->
				<div id="line_chart">
					<h3 class="txtcenter">LINE CHART</h3>
					<canvas id="line_display_canvas" width="800" height="400">[No canvas support]</canvas>	
										
					<script>
						window.onload = function ()
						{
							// The data for the Line chart. Multiple lines are specified as seperate arrays.
							//var data = [10,4,17,50,25,19,20,25,30,29,30,29];
						
							// Create the Line chart object. The arguments are the canvas ID and the data array.
							var line = new RGraph.Line("line_display_canvas", <?php echo $jsoncolumn; ?>);
							
							// The way to specify multiple lines is by giving multiple arrays, like this:
							// var line = new RGraph.Line("myLine", [4,6,8], [8,4,6], [4,5,3]);
							line.Set('chart.key', <?php echo $regionjson; ?>);						
							line.Set('chart.linewidth', 1);
							line.Set('chart.hmargin', 5);
							line.Set('chart.labels', <?php echo $yearjson; ?>);
							line.Set('chart.tooltips', <?php echo $yearjson; ?>);
							line.Set('chart.tickmarks', 'endcircle');							
							line.Set('chart.gutter.left', 40);
							line.Set('chart.filled', true);
																				
							// Now call the .Draw() method to draw the chart.
							line.Draw();
						}
					</script>
				</div>
				<!-- LINE CHART -->
				<?php //} ?>

			</div>
			<!-- /LINE PIE BAR CHART ------------------------->

			<input type="hidden" value="0" id="chartShown"/>
			<?php if(isset($_GET['graph']) && $_GET['graph']=='gridview') {?>
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
			<?php } ?>

			<p><i><font color="#000000"><?php echo $searchedTotalRows; ?> of <?php echo $totalRows; ?></font> allowed matches returned.</i></p>

			<p>Source: RAND State Statistics (See <a href="/cgi-bin/statlist.cgi?db=popdemo/popest1790.html">Statistics Summary</a> for originating data source.)</p>

			<p>
			<?php echo date('D M d H:i:s Y'); ?>
			</p>		 
			<!-- left side -->
		
</section>
<!-- /container -->

