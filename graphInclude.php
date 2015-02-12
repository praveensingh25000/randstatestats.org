<?php
/******************************************
* @Modified on April 16, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://50.62.142.193
********************************************/

if((isset($_GET['graph']) && ($_GET['graph']=='linegraph' || $_GET['graph']=='bargraph')) && isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY') ) { 
		if(!isset($nograph) || (isset($nograph) && !$nograph)){
		$attributesResult = $admin->getDatabaseGraphAttributes($dbid);

		$graphAttributesArray = array();

		$graphAttributes = array('x_label' => '', 'y_label' => '', 'graph_label' => '');	
		
		if(mysql_num_rows($attributesResult)>0){
			while($graphAttr = mysql_fetch_assoc($attributesResult)){
				$graphAttributesArray[$graphAttr['attribute_name']] = $graphAttr['attribute_value'];		
			}
		}
		
		if($_GET['graph'] == 'bargraph' && isset($graphAttributesArray) && isset($graphAttributesArray['x_axis_bar_label']) && isset($graphAttributesArray['y_axis_bar_label']) && isset($graphAttributesArray['graph_bar_label'])){
			$graphAttributes = array('x_label' => $graphAttributesArray['x_axis_bar_label'], 'y_label' => $graphAttributesArray['y_axis_bar_label'], 'graph_label' => $graphAttributesArray['graph_bar_label']);
		} else if(isset($graphAttributesArray) && isset($graphAttributesArray['x_axis_line_label']) && isset($graphAttributesArray['y_axis_line_label']) && isset($graphAttributesArray['graph_line_label'])){
			$graphAttributes = array('x_label' => $graphAttributesArray['x_axis_line_label'], 'y_label' => $graphAttributesArray['y_axis_line_label'], 'graph_label' => $graphAttributesArray['graph_line_label']);
		}

		//echo "<pre>";print_r($timeIntervalSettings);echo "</pre>";
		//echo "<pre>";print_r($graphAttributes);echo "</pre>";

		$graphtype = ($_GET['graph'] == 'bargraph')?'bars':'lines';

		if($timeIntervalSettings['time_format'] != 'SQ-SY-EQ-EY') { ?>

			<div id="line_chart">
				<h3 class="txtcenter"><?php echo $graphAttributes['graph_label']; ?></h3>
				
				<div id="tobeprinted" style="display:none;">
					<table>
						<tr>
							<td colspan="2"><?php echo ucfirst($dbname); ?> - <?php echo $graphAttributes['graph_label']; ?></td>
						</tr>
						<tr>
							<td width="5%"><div class="y-axislabel"><?php echo $graphAttributes['y_label']; ?></div></td>
							<td>
								<div  id="line_display_canvas_print" style="width:auto;height:500px;" >
								</div>	
								<div class="x-axislabel"><?php echo $graphAttributes['x_label']; ?></div><br/>

								<div class="graph-deatail" id="choicesPrint">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2"><?php echo stripslashes($db_datasource); ?></td>
						</tr>
						<tr>
							<td colspan="2"><?php echo date('D M d H:i:s Y'); ?></td>
						</tr>
					</table>
				</div>

				<table width="100%" cellspacing="4" cellpadding="4">
					<tr>
						<td  width="5%">&nbsp</td>
						<td  colspan="2"><strong>Options:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name = "showgraphoption" class = "showgraphOption" checked>&nbsp;<?php echo ucfirst($graphtype); ?> Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" value="1" class = "showgraphOption">&nbsp;Points Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" class = "showgraphOption" value="2">&nbsp;Both</td>
					</tr>		
					<tr>
						<td width="5%"><div class="y-axislabel"><?php echo $graphAttributes['y_label']; ?></div></td>
						<td>
							<div  id="line_display_canvas" style="width:auto;height:500px;" >
							</div>	
							<div class="x-axislabel"><?php echo $graphAttributes['x_label']; ?></div>
						</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td>
							<div class="graph-deatail" id="choices">
								<?php
								/*$area_chunk = array_chunk($areaArray,4,true);
								echo "<table cellspacing='6' cellpadding='6'>";
								foreach($area_chunk as $keyM => $areas){
									echo "<tr>";
									foreach($areas as $key=> $area){
										echo "<td><div class='grahp-checkbox' style='background:".$colorsarray[$key]."'>&nbsp;</div><div class='grahp-checkbox-detail'>".$area."</div></td>";
									}
									echo "</tr>";
								}
								echo "</table>";*/
								?>
							</div>
							<div class="clear">&nbsp;</div>
						</td>
					</tr>
				</table>
				<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.js"></script>
				
				<input type="hidden" id="showPoints" value="0" />
				<script>
					$(function () {
						
						
						$("#graphPrint").click(function() {
							//$('.noPrint').hide();
							//$('.tobeprinted').show();
							var htmlline = jQuery('#tobeprinted').html();
							 my_window = window.open("", "mywindow","status=1,width=500,height=500,scrollbars=auto");
							  my_window.document.write('<html><head><title>Print Me</title></head>');
							  my_window.document.write('<body onafterprint="self.close()">');
							  my_window.document.write(htmlline);
							  my_window.document.write('</body></html>');  

							//printElem({});
						}); 

						var datasets = {
						<?php foreach($arrayColumns as $keyAr => $datasetsArray) { 
							
							
							?>
							"checkbox_<?php echo $keyAr; ?>": {
								label: "<?php echo $areaArray[$keyAr]; ?>",
								data: [<?php foreach($datasetsArray as $keyDataset => $dataset) { 

									if (isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){

										if($dataset>=0){
											echo "[new Date('".$yearArray[$keyDataset]."'),".$dataset."],";
										} else {
											echo "[new Date('".$yearArray[$keyDataset]."'),null],";
										}

									} else {

										if($dataset>=0){
											echo "[".$yearArray[$keyDataset].",".$dataset."],";
										} else {
											echo "[".$yearArray[$keyDataset].",null],";
										}

									} 

								} ?>]
							}<?php if(count($arrayColumns) != $keyAr+1){ echo ","; } ?> 
						<?php } ?>
							
						};

						// hard-code color indices to prevent them from shifting as
						// countries are turned on/off
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

							choiceContainer.append('<div class="serieslabelcontainer"><div class="seriescheckbox"><input type="checkbox" name="' + key +
												   '" checked="checked" id="id' + key + '"></div>' +
												   '<div class="seriescolor ' + labelwithoutspaces + ' " id="color_codes' + key + '" ></div><label class="serieslabel" for="id' + key + '">'
													+ val.label + '</label></div>');
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
								if (key && datasets[key])
									data.push(datasets[key]);
							});

							if (data.length > 0)
								var plot = $.plot($("#line_display_canvas"), data, {
									canvas: true,
									legend: { 
										show: true,  
										noColumns: 3, 
										container: choiceContainerPrint 
									},
									yaxis: { min: 0 },
									xaxis: { 
									<?php if (isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] != 'SM-SY-EM-EY')){ ?>	
										tickDecimals: 0 
									<?php } ?>

									<?php if (isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){ ?>
									mode: "time",
									timeformat: "%b-%y",
								
									monthNames: ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec"],
									minTickSize: [1, "month"]
									<?php } ?>

									},
									series: {
									   <?php echo $graphtype; ?>: { show: showgraph, lineWidth: 1 },
									   points: { show: showpoints,  lineWidth: 1 }

									},
									grid: { hoverable: true, clickable: true }
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
								jQuery('#line_display_canvas_print').html('');
								jQuery('#line_display_canvas_print').append("<img src='"+image+"' />");
							}

						plotAccordingToChoices();

						jQuery('.showgraphOption').click(function(){				

							var value = jQuery(this).val();
							jQuery('#showPoints').val(value);
							plotAccordingToChoices();
						
						});
						
						function showTooltip(x, y, contents) {
							$('<div id="tooltip">' + contents + '</div>').css( {
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
						
						

						var previousPoint = null;
						$("#line_display_canvas").bind("plothover", function (event, pos, item) {
							$("#x").text(pos.x.toFixed(2));
							$("#y").text(pos.y.toFixed(2));

							
							if (item) {
								if (previousPoint != item.dataIndex) {
									previousPoint = item.dataIndex;
									
									$("#tooltip").remove();
									var x = item.datapoint[0].toFixed(2),

									<?php if($decimal_settings == '') { ?>
										y = item.datapoint[1];
										var yvalue = y;
									<?php } else { ?>
										y = item.datapoint[1].toFixed(<?php echo $decimal_settings; ?>);
										var yvalue = y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
									<?php } ?>
									
									

									<?php if (isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){ ?>

									var d = new Date(parseInt(x));
									var m = d.getMonth(); 
								
									var year = d.getFullYear();
									
									

									showTooltip(item.pageX, item.pageY,
									item.series.label + " of " + (m+1) + "/" + year + " = " + yvalue);

									<?php } else { ?>
									

									showTooltip(item.pageX, item.pageY,
												item.series.label + " of " + parseInt(x) + " = " + yvalue);

									<?php } ?>
								}
							}
							else {
								$("#tooltip").remove();
								previousPoint = null;            
							}
							
						});

						
					});
				</script>
			</div>
	<?php } else {
					
			//echo $timeIntervalSettings['time_format'];

			if(count($yearArray)>0){
				$tempYears = array();
				for($i=0;$i<count($yearArray);$i++){
					
					$tempData = explode('/',$yearArray[$i]);
					$year = $tempData[1];
					$quater = $tempData[0];
					

					if(strtolower($quater) == 'q1')
						$tempYears[] = $year."-01-01"; 
					elseif($quater == 'q2')
						$tempYears[] = $year."-04-01";  
					elseif($quater == 'q3')
						$tempYears[] = $year."-07-01";  
					else
						$tempYears[] = $year."-10-01"; 
				}
				$yearArray = $tempYears;
			}

			//echo "<pre>";print_r($arrayColumns);echo "</pre>";

			//echo "<pre>";print_r($yearArray);echo "</pre>";

			?>

			<div id="line_chart">
				<h3 class="txtcenter"><?php echo $graphAttributes['graph_label']; ?></h3>
				
				<div id="tobeprinted" style="display:none;">
					<table>
						<tr>
							<td colspan="2"><?php echo ucfirst($dbname); ?> - <?php echo $graphAttributes['graph_label']; ?></td>
						</tr>
						<tr>
							<td width="5%"><div class="y-axislabel"><?php echo $graphAttributes['y_label']; ?></div></td>
							<td>
								<div  id="line_display_canvas_print" style="width:auto;height:500px;" >
								</div>	
								<div class="x-axislabel"><?php echo $graphAttributes['x_label']; ?></div><br/>

								<div class="graph-deatail" id="choicesPrint">
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2"><?php echo stripslashes($db_datasource); ?></td>
						</tr>
						<tr>
							<td colspan="2"><?php echo date('D M d H:i:s Y'); ?></td>
						</tr>
					</table>
				</div>

				<table width="100%" cellspacing="4" cellpadding="4">
					<tr>
						<td  width="5%">&nbsp</td>
						<td  colspan="2"><strong>Options:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name = "showgraphoption" class = "showgraphOption" checked>&nbsp;<?php echo ucfirst($graphtype); ?> Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" value="1" class = "showgraphOption">&nbsp;Points Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" class = "showgraphOption" value="2">&nbsp;Both</td>
					</tr>		
					<tr>
						<td width="5%"><div class="y-axislabel"><?php echo $graphAttributes['y_label']; ?></div></td>
						<td>
							<div  id="line_display_canvas" style="width:auto;height:500px;" >
							</div>	
							<div class="x-axislabel"><?php echo $graphAttributes['x_label']; ?></div>
						</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td>
							<div class="graph-deatail" id="choices">
								<?php
								/*$area_chunk = array_chunk($areaArray,4,true);
								echo "<table cellspacing='6' cellpadding='6'>";
								foreach($area_chunk as $keyM => $areas){
									echo "<tr>";
									foreach($areas as $key=> $area){
										echo "<td><div class='grahp-checkbox' style='background:".$colorsarray[$key]."'>&nbsp;</div><div class='grahp-checkbox-detail'>".$area."</div></td>";
									}
									echo "</tr>";
								}
								echo "</table>";*/
								?>
							</div>
							<div class="clear">&nbsp;</div>
						</td>
					</tr>
				</table>
				<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.js"></script>
				
				<input type="hidden" id="showPoints" value="0" />
				<script>
					$(function () {
						
						
						$("#graphPrint").click(function() {
							//$('.noPrint').hide();
							//$('.tobeprinted').show();
							var htmlline = jQuery('#tobeprinted').html();
							 my_window = window.open("", "mywindow","status=1,width=500,height=500,scrollbars=auto");
							  my_window.document.write('<html><head><title>Print Me</title></head>');
							  my_window.document.write('<body onafterprint="self.close()">');
							  my_window.document.write(htmlline);
							  my_window.document.write('</body></html>');  

							//printElem({});
						}); 

						var datasets = {
						<?php foreach($arrayColumns as $keyAr => $datasetsArray) { 
							
							
							?>
							"checkbox_<?php echo $keyAr; ?>": {
								label: "<?php echo $areaArray[$keyAr]; ?>",
								data: [<?php foreach($datasetsArray as $keyDataset => $dataset) { 

									if($dataset>0){
										echo "[new Date('".$yearArray[$keyDataset]."'),".$dataset."],";
									} else {
										echo "[new Date('".$yearArray[$keyDataset]."'),null],";
									}

								} ?>]
							}<?php if(count($arrayColumns) != $keyAr+1){ echo ","; } ?> 
						<?php } ?>
							
						};

						// hard-code color indices to prevent them from shifting as
						// countries are turned on/off
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

							choiceContainer.append('<div class="serieslabelcontainer"><div class="seriescheckbox"><input type="checkbox" name="' + key +
												   '" checked="checked" id="id' + key + '"></div>' +
												   '<div class="seriescolor ' + labelwithoutspaces + ' " id="color_codes' + key + '" ></div><label class="serieslabel" for="id' + key + '">'
													+ val.label + '</label></div>');
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
								if (key && datasets[key])
									data.push(datasets[key]);
							});

							if (data.length > 0)
								var plot = $.plot($("#line_display_canvas"), data, {
									canvas: true,
									legend: { show: true,  noColumns: 3, container: choiceContainerPrint },
									yaxis: { min: 0 },
									xaxis: { 
									
									mode: "time",
									timeformat: "%b/%y",
									monthNames: ["Q1", "", "", "Q2", "", "", "Q3", "", "", "Q4", "", ""],
									minTickSize: [3, "month"]

									},
									series: {
									   <?php echo $graphtype; ?>: { show: showgraph, lineWidth: 1},
									   points: { show: showpoints,  lineWidth: 1 }

									},
									grid: { hoverable: true, clickable: true }
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
								jQuery('#line_display_canvas_print').html('');
								jQuery('#line_display_canvas_print').append("<img src='"+image+"' />");
							}

						plotAccordingToChoices();

						jQuery('.showgraphOption').click(function(){				

							var value = jQuery(this).val();
							jQuery('#showPoints').val(value);
							plotAccordingToChoices();
						
						});
						
						function showTooltip(x, y, contents) {
							$('<div id="tooltip">' + contents + '</div>').css( {
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
						
						

						var previousPoint = null;
						$("#line_display_canvas").bind("plothover", function (event, pos, item) {
							$("#x").text(pos.x.toFixed(2));
							$("#y").text(pos.y.toFixed(2));

							
							if (item) {
								if (previousPoint != item.dataIndex) {
									previousPoint = item.dataIndex;
									
									$("#tooltip").remove();
									var x = item.datapoint[0].toFixed(2),

									<?php if($decimal_settings == '') { ?>
										y = item.datapoint[1];
										var yvalue = y;
									<?php } else { ?>
										y = item.datapoint[1].toFixed(<?php echo $decimal_settings; ?>);
										var yvalue = y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
									<?php } ?>
									
									var d = new Date(parseInt(x));
									var m = d.getMonth(); 
							
									var year = d.getFullYear();
									if(m == '0'){
										var quater = 'Q1';
									} else if(m == '3'){
										var quater = 'Q2';
									} else if(m == '6'){
										var quater = 'Q3';
									} else {
										var quater = 'Q4';
									}

									showTooltip(item.pageX, item.pageY,
									item.series.label + " of " + quater + "/" + year + " = " + yvalue);
								}
							}
							else {
								$("#tooltip").remove();
								previousPoint = null;            
							}
							
						});

						
					});
				</script>
			</div>

<?php } 
	}else {
		echo '<div id="line_chart">No Graph Available For This Form</div>';
	}
} 
?>
