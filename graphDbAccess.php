<h3 align="middle">Total Number of Accesses = <span id="accessTotal"><?php echo $totalAccess;?></span></h3>
<br/>
<br class="clear"/>
<?php
$graphtype = ($_GET['graph'] == 'bargraph')?'bars':'lines';
?>
<div id="printedurlgraph" style="display:none;">
	<table>
		<tr>
			<td colspan="2"><b>Accessed Database Detail</b></td>
		</tr>
		<tr>
			<td colspan="2">
				<strong>For IP = <?php echo $_SESSION['all_data']['ip'];?> <br/> Accessed Period = <?php echo date('M d, Y',strtotime($_SESSION['all_data']['sdate']));?></strong> To <strong><?php echo date('M d, Y',strtotime($_SESSION['all_data']['edate']));?></strong>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td>
				<div id="placeholder_print" style="width:auto;height:500px;" >
				</div>	
				<br/>
				<div class="graph-deatail" id="choicesPrint">
				</div>
			</td>
		</tr>
	</table>
</div>
<table width="100%" cellspacing="4" cellpadding="4">		
	<tr>
		<td width="5%"></td>
		<td>
			<div  id="placeholder" style="width:auto;height:500px;" >
			</div>	
		</td>
	</tr>
</table>
<input type="hidden" id="showPoints" value="0" />
<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.0.8.2.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.categories.js"></script>
<?php
$datasets = $strdata = '';
if(isset($urlHistory) and is_array($urlHistory) and count($urlHistory)>0)
{
	foreach($urlHistory as $dbkey =>$urlDetail)
	{
		$totalAccessDB = 0;
		$siteDBDetail = $admin->selectDatabases($dbkey);
		$dbName = $siteDBDetail['databasename'];
		$dbLabel = $siteDBDetail['database_label'];

		$count= 0;
		foreach($urlDetail as $urlKey=>$timeDetail){
			$count = count($timeDetail);
			$totalAccess += $count;
			$totalAccessDB += $count;
			
		}
		$strdata .= '["'.$dbLabel.'", '.$totalAccessDB.'], ';
		
		?>			
	<?php }
		$datasets .= "[".$strdata."]";
	 }
	 //$datasets = substr($datasets, 0, -1);
	 //echo $datasets;
?>
<script>
	document.getElementById("accessTotal").innerHTML="<?php echo $totalAccess;?>";
				
	$(function() {

		$("#graphPrintUrl").click(function() {
			//$('.noPrint').hide();
			//$('.tobeprinted').show();
			var htmlline = jQuery('#printedurlgraph').html();
			 my_window = window.open("", "mywindow","status=1,width=500,height=500,scrollbars=auto");
			  my_window.document.write('<html><head><title>Accessed Database Detail</title></head>');
			  my_window.document.write('<body onafterprint="self.close()">');
			  my_window.document.write(htmlline);
			  my_window.document.write('</body></html>');  

			//printElem({});
		});

		var data = <?php echo $datasets;?>;

		var plot = $.plot("#placeholder", [ data ], {
			canvas: true,
			series: {
				bars: {
					show: true,
					barWidth: 0.3,
					align: "center"
				},
			},
			grid: {
				hoverable: true
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			}
		});

		var mycanvas = plot.getCanvas();
		var image    = mycanvas.toDataURL("image/png");
		jQuery('#placeholder_print').html('');
		jQuery('#placeholder_print').append("<img src='"+image+"' />");

		function showTooltip(x, y, contents) {
			$('<div id="flot-tooltip">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 5,
				border: '1px solid #fdd',
				padding: '2px',
				'background-color': '#fee',
				opacity: 0.80
			}).appendTo("body").fadeIn(200);
		}

		// Add the Flot version string to the footer
		var previousPoint = null;
		$("#placeholder").bind("plothover", function (event, pos, item) {
			$("#x").text(pos.x.toFixed(2));
			$("#y").text(pos.y.toFixed(2));
			if (item) {
				if (previousPoint != item.datapoint) {
					previousPoint = item.datapoint;
					$("#flot-tooltip").remove();
						 
					var originalPoint;
	 
					if (item.datapoint[1] == item.series.data[0][1]) {
						originalPoint = item.series.data[0][0];
					} else if (item.datapoint[1] == item.series.data[1][1]){
						originalPoint = item.series.data[1][0];
					} else if (item.datapoint[1] == item.series.data[2][1]){
						originalPoint = item.series.data[2][0];
					} else if (item.datapoint[1] == item.series.data[3][1]){
						originalPoint = item.series.data[3][0];
					}/* else if (item.datapoint[0] == item.series.data[4][3]){
						originalPoint = item.series.data[4][0];
					}*/
	
					var x = originalPoint;
                        y = item.datapoint[1];
	 
					showTooltip(item.pageX, item.pageY,
						"Total Usage on " + x + " = " + y + "");
				}
			} else {
				$("#flot-tooltip").remove();
				previousPoint = null;
			}
    });

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});
	</script>
