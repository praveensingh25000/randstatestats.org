<?php
$graphtype = ($_GET['graph'] == 'bargraph')?'bars':'lines';
$min_date = $max_date = ''; 
//echo '<pre>';print_r($all_history);echo '</pre>';
?>
<b>Total Usage: </b><span id="totalUsage"><?php echo $totalUsage;?></span> accesses
<br class="clear"/>
<br class="clear"/>
<div id="graphtobeprinted" style="display:none;">
	<table>
		<tr>
			<td colspan="2"><b>Usage Statistics</b></td>
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

<table width="100%">
	<tr>
		<td  width="5%">&nbsp</td>
		<td  colspan="2"><strong>Options:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name = "showgraphoption" class = "showgraphOption" checked>&nbsp;<?php echo ucfirst($graphtype); ?> Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" value="1" class = "showgraphOption">&nbsp;Points Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" class = "showgraphOption" value="2">&nbsp;Both</td>
	</tr>		
	<tr>
		<td width="5%"></td>
		<td>
			<div id="placeholder" style="width:88%;height:500px;" >
			</div>	
		</td>
	</tr>
	<tr>
		<td width="5%">&nbsp;</td>
		<td>
			<div class="graph-deatail" id="choices">
			</div>
			<div class="clear">&nbsp;</div>
		</td>
	</tr>
</table>
<input type="hidden" id="showPoints" value="0" />
<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.time.js"></script>
    <script type="text/javascript">
	$(function() {

		$("#graphPrintStats").click(function() {
			//$('.noPrint').hide();
			//$('.tobeprinted').show();
			var htmlline = jQuery('#graphtobeprinted').html();
			 my_window = window.open("", "mywindow","status=1,width=500,height=500,scrollbars=auto");
			  my_window.document.write('<html><head><title>Usage Statistics</title></head>');
			  my_window.document.write('<body onafterprint="self.close()">');
			  my_window.document.write(htmlline);
			  my_window.document.write('</body></html>');  

			//printElem({});
		});

		var datasets = {
				<?php
				if(isset($all_history) and is_array($all_history) and count($all_history)>0){
					$k=0;
					foreach($all_history as $key =>$ipDetail){
						?>
							"checkboxes_<?php echo $k ?>":{
								label: "<?php echo $key ?>",
								data:  [<?php foreach($ipDetail as $ipAdrkey =>$dateDetail){
									if($min_date==''){
										$min_date = date('Y/m/j',strtotime($ipAdrkey));
									}
									if($max_date < date('Y/m/j',strtotime($ipAdrkey))){
										$max_date = date('Y/m/j',strtotime($ipAdrkey));
									}
									$count=count($dateDetail);?>
										<?php echo $strdata = "[new Date('".$ipAdrkey."'),".$count."],"; 
										$totalUsage += $count;
									} ?>]
								},
								<?php 
									$strdata = substr($strdata, 0, -1);
									$k++;
							}
					}?>
				};
		
		<?php
			
			$maxDate=strtotime($max_date) + (60 * 60 * 24);//increase date by 1 day
			//$minDate = strtotime($min_date) - (60 * 60 * 24);
			$minDate = $min_date;
			
		?>
		document.getElementById("totalUsage").innerHTML="<?php echo $totalUsage;?>";
		var maxDate = '<?php echo date("Y/m/j",$maxDate);?>';
		var minDate = '<?php echo $minDate;?>';
		var one_day = 1000*60*60*24; 

		//Here we need to split the inputed dates to convert them into standard format
        var min		= minDate.split("/");     
        var max		= maxDate.split("/");
		//date format(Fullyear,month,date) 

        var date1=new Date(min[0],(min[1]-1),min[2]);  
        var date2=new Date(max[0],(max[1]-1),max[2]);

        //Calculate difference between the two dates, and convert to days 
        var Difference=Math.ceil((date2.getTime()-date1.getTime())/(one_day));

		var i = 0;
		$.each(datasets, function(key, val) {
			val.color = i;
			++i;
		});

		var count = 0;

		// insert checkboxes 
		var choiceContainer = $("#choices");
		var choiceContainerPrint = $("#choicesPrint");
		$.each(datasets, function(key, val) {
			var stringval = val.label;
			var labelwithoutspaces = stringval.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
			choiceContainer.append("<div class='serieslabelcontainer'><div class='seriescheckbox'><input type='checkbox' name='" + key +
				"' checked='checked' id='id" + key + "'/></div>" +
				"<div class='seriescolor " + labelwithoutspaces + "'  id='color_codes" + key + "' ></div><label for='id" + key + "'>"
				+ val.label + "</label></div>");
			count++;
		});

		choiceContainer.find("input").click(plotAccordingToChoices);

		function plotAccordingToChoices() {

			var data = [];
			var showPointsVal = jQuery('#showPoints').val();
							
			var showpoints = false;

			var showgraph = true;

			if(showPointsVal == '1'){
				showpoints = true;
				showgraph = false;
			} else if(showPointsVal == '2'){
				showpoints = true;
				showgraph = true;
			}

			choiceContainer.find("input:checked").each(function () {
				var key = $(this).attr("name");
				if (key && datasets[key]) {
					data.push(datasets[key]);
				}
			});

			if (data.length > 0) 
				var plot = $.plot("#placeholder", data, {
					canvas: true,
					legend: { 
						show: true,  
						noColumns: 3,
						container: choiceContainerPrint,
					},
					series: {
					   <?php if($graphtype == 'lines'){echo $graphtype; ?>: { show: showgraph, lineWidth: 1 }<?php }else if($graphtype == 'bars'){echo $graphtype; ?>: { show: showgraph, barWidth: 24*60*60*1000 }<?php } ?>,
					   points: { show: showpoints,  lineWidth: 1 }

					},
					grid: { hoverable: true, clickable: true },
					yaxis: {
						min: 0
					},
					xaxis: {
						
						mode:"time",
						//ticks: 12,
						timeformat: "%d-%b-%Y",
						monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
						minTickSize: [1, "day"],

						min: (new Date(minDate)).getTime(),
						max: (new Date(maxDate)).getTime()
						
					}
				});

				var series = plot.getData();
								
				 for (var i = 0; i < series.length; ++i){
					var stringval = series[i].label;
					var labelwithoutspaces = stringval.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
					jQuery('.' + labelwithoutspaces + '').html('<div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid ' + series[i].color + ';overflow:hidden"></div></div>');
				} 

				for (var i = 0; i < count; ++i){
					if(jQuery('#idcheckbox_' +  i + '').is(':checked')){
					} else {
						jQuery('#color_codescheckbox_' + i + ' ').html('');
					}
				}

				var mycanvas = plot.getCanvas();
				var image    = mycanvas.toDataURL("image/png");
				jQuery('#placeholder_print').html('');
				jQuery('#placeholder_print').append("<img src='"+image+"' />");
			}

		plotAccordingToChoices();

		jQuery('.showgraphOption').click(function(){				
			var value = jQuery(this).val();
			jQuery('#showPoints').val(value);
			plotAccordingToChoices();
		
		});

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
	 
					/*if (item.datapoint[0] == item.series.data[0][3]) {
						originalPoint = item.series.data[0][0];
					} else if (item.datapoint[0] == item.series.data[1][3]){
						originalPoint = item.series.data[1][0];
					} else if (item.datapoint[0] == item.series.data[2][3]){
						originalPoint = item.series.data[2][0];
					} else if (item.datapoint[0] == item.series.data[3][3]){
						originalPoint = item.series.data[3][0];
					} else if (item.datapoint[0] == item.series.data[4][3]){
						originalPoint = item.series.data[4][0];
					}*/
	 
					//var x = getMonthName(originalPoint);
					//var x = $.plot.formatDate(item.datapoint[0], "%y/%m/%d");
					var x = item.datapoint[0].toFixed(2);
					var d = new Date(parseInt(x));
					var x = $.plot.formatDate(d, "%d %b,%Y");
					y = item.datapoint[1];
					z = item.series.color;
	 
					showTooltip(item.pageX, item.pageY,
						"<b>" + item.series.label + ":</b><br />Total Clicks on " + x + " = " + y + "",
						z);
				}
			} else {
				$("#flot-tooltip").remove();
				previousPoint = null;
			}
    });

		$("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
	});
    </script>
    
