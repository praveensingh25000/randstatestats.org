<!-- FILE IS USED TO ADD EDIT A SEARCH CRITERIA IN ADMIN SECTION -->
<?php
$is_filter = 'N';
$filtervalues = '';
?>
<div class="containerLadmin">
	<div style="clear: both; padding: 15px 0;">
	<fieldset style="border: 1px solid #cccccc; padding: 10px;">
	<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Edit Database '".stripslashes($databaseDetail['db_name'])."'"; } else { echo "Add Database"; } ?> </legend>

		<?php include('formNavigation.php'); ?>
		<div id="generalDetails">

		<script type="text/javascript">
			var tablename = '';
			var columnname = '';
		</script>
		
		<form method="post" action="" id="search_criteria">
			<label>Label</label><input class="required" id="group_name" type="text" name="label" value="<?php if(!empty($search_criteria)){ echo $search_criteria['label_name'];}?>">
			
			<div style="display:none" id="already_registered_search_name"><?php echo json_encode($admin->selectOnlyNameOfSearchCriteria($dbid));?></div>
			<div class="mT10" id="loading_div"></div>

			<div>
				<label>Criteria</label>&nbsp;&nbsp;<em class="">(select tables to see respective columns)</em><br>
				<table>
				
				<?php 
					$div_container='';
					foreach($getDbTables as $key=>$tables_db) { 
					?>
					<tr>
						<?php foreach($tables_db as $key=>$tables) { ?>
						 <td width="33%">  <input type="radio" <?php if(isset($search_criteria['related_tables']) && in_array($tables['table_name'],$search_criteria['related_tables'])) { echo'checked="checked"'; }?> class="table_check required" onclick="javascript:loadTableData($(this));" name="tables[]" value="<?php echo $tables['table_name']; ?>"><label class="mL10"><?php echo $tables['table_name']; ?></label></td>
						 <?php 
						$div_container.="<div id=id_".$tables['table_name']." class='otherTables'></div>";	//making div so when user clicks checkboes we have an area for each check
					} ?>
					 </tr>
				<?php } ?>
					<tr >
						<?php ?>
					</tr>
				</table>
				<label for="tables[]" style="display:none" generated="true" class="error">This field is required.</label>
			</div>
			<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">Columns for tables</legend>
				<?php if(isset($edit)){ 
						$is_filter = 'N';
						$filtervalues = '';
						if(count($search_criteria['search_criteria_coloums'])>0){
							$detailColumn = $search_criteria['search_criteria_coloums'][0];
							$tablename = $detailColumn['belongs_to'];
							$columnname = $detailColumn['coloum_name'];
							$is_filter = $detailColumn['is_filter'];
							if($is_filter == 'Y'){
								$filtervalues = unserialize($detailColumn['filtered_values']);
								
								$filtervaluesJsonArray = array();
								$filtervaluesJson = '';
								foreach($filtervalues as $key => $value)
								$filtervaluesJsonArray[] = array('id' => $value, 'name' => $value);

								$filtervaluesJson = json_encode($filtervaluesJsonArray);
							}
						}

						include_once($DOC_ROOT.'/admin/viewSearcRealtedTabels.php'); } else { ?>
					<div id="ajax_result"><?php echo $div_container; ?></div>  <!-- data will come hr by ajax -->

					<script type="text/javascript">
					$(document).ready(function() {
						var tablename = '';
						var columnname = '';
					});
					</script>


					<?php } ?>
				</label>
			</fieldset>
			<br/>

			<div class="pB5">
				<div class="pB10">
					<b><label  >Filter Control Values ?</label></b>
					<input type="checkbox" value="Y" class="" name="filter_values" <?php if(isset($is_filter) && $is_filter == 'Y'){ echo "checked='checked'"; } ?> id="filter_values" />
					
					<?php if($is_filter == 'Y'){ ?>
					
					<script type="text/javascript">
					$(document).ready(function() {	
						
						tablename = '<?php echo $tablename; ?>';
						columnname = '<?php echo $columnname; ?>';

						jQuery("#filtered_values").tokenInput("<?php echo URL_SITE; ?>/admin/filtered.php?table="+tablename+"&column="+columnname, {
								preventDuplicates: true,
								prePopulate: <?php echo $filtervaluesJson; ?>
								
						});
					});
					</script>
					<?php } ?>


					<script type="text/javascript">
					$(document).ready(function() {	
						
						jQuery('input:radio[name="tables[]"]').change(function(){
							jQuery('#checkFilterValuesDiv').hide();
							jQuery('#filter_values').removeAttr('checked');
						});

						jQuery('#filter_values').change(function(){
								var checked = jQuery(this).is(":checked");
								
								tablename = jQuery('input:radio[name="tables[]"]:checked').val();
									
								if(tablename!= undefined){
									columnname = jQuery('input:radio[name="'+tablename+'[]"]:checked').val();
								}

								if(checked && columnname!= undefined && columnname!=""){
									jQuery('#checkFilterValuesDiv').show();
									jQuery("#filtered_values").tokenInput("<?php echo URL_SITE; ?>/admin/filtered.php?table="+tablename+"&column="+columnname, {
											preventDuplicates: true,
											//method: 'POST',
									});
								}else if(checked){
									jQuery(this).removeAttr('checked');
									alert("Please select table & column first");
									jQuery('#checkFilterValuesDiv').hide();
								}else{
									jQuery('#checkFilterValuesDiv').hide();
								}


						});
					});
					
					</script>
				</div>
				<div class="pB10" id="checkFilterValuesDiv" <?php if($is_filter != 'Y'){ ?> style="display:none;" <?php } ?>>
					<b><label class="pB10" >Select Filter Values</label></b>
					<input type="text" id="filtered_values" name="filtered_values" />

				</div>
			</div>

			<div class="pB10">
				<b><label class="pB10" style="display:block">Control Input Value</label></b><input type="radio" <?php if(isset($search_criteria) and $search_criteria['type']=='single'){ echo 'checked="checked"'; } ?> value="single" class="required" name="type" id="singleControl">&nbsp;Single
				&nbsp;<input <?php if(isset($search_criteria) and $search_criteria['type']=='multiple'){ echo 'checked="checked"';} ?> type="radio" value="multiple" class="required" name="type" id="multipleControl">&nbsp;Multiple
				<br>
				<label  style="display:block" for="type" generated="true" class="error pB5"></label>
			</div>

			<div class="pB10" id="allowAllDiv" <?php if((isset($search_criteria) and $search_criteria['type']=='single') or (!isset($search_criteria))){ ?> style="display:none;" <?php } ?> >
				<b><label class="pB10 pR10" >Allow All</label></b>
				<input type="checkbox" value="Y" class="" name="allow_all" <?php if(isset($search_criteria) and $search_criteria['allow_all_values']=='Y'){ echo 'checked="checked"'; } ?>>
			</div>

			<script type='text/javascript'>
			// <![CDATA[
			jQuery(document).ready(function(){
				jQuery('input:radio[name="type"]').change(function(){

					//jQuery('input:radio[name="control_type"]').removeAttr('checked');

					if(jQuery(this).val() == 'multiple'){
						jQuery('#multipleDivBlock').show();
						jQuery('#singleDivBlock').hide();
						jQuery('#allowAllDiv').show();
					} else {
						jQuery('#singleDivBlock').show();
						jQuery('#multipleDivBlock').hide();
						jQuery('#allowAllDiv').hide();
					}
				});
			});

			// ]]>
			</script>


			
			
			<div class="pB5">
				<div  id="singleDivBlock"  <?php if(isset($search_criteria) and $search_criteria['type']=='single'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>

					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">Control Type</legend>
					<input type="radio" value="select" class="required" name="control_type" <?php if(isset($search_criteria) and $search_criteria['control_type']=='select'){ echo 'checked="checked"'; } ?>>&nbsp;Show as select box
					&nbsp;<input type="radio" value="radio" class="required" name="control_type" <?php if(isset($search_criteria) and $search_criteria['control_type']=='radio'){ echo 'checked="checked"'; } ?>>&nbsp;Show as radio button
					</fieldset>
				</div>

				<div  id="multipleDivBlock" <?php if(isset($search_criteria) and $search_criteria['type']=='multiple'){ ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
					<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">Control Type</legend>
					<input type="radio" value="select" class="required" name="control_type" <?php if(isset($search_criteria) and $search_criteria['control_type']=='select' && $search_criteria['type']=='multiple'){ echo 'checked="checked"'; } ?>>&nbsp;Show as select box
					&nbsp;<input type="radio" value="checkbox" class="required" name="control_type" <?php if(isset($search_criteria) and $search_criteria['control_type']=='checkbox'){ echo 'checked="checked"'; } ?>>&nbsp;Show as check boxes
					<br/>
					</fieldset>
				</div>
				<label for="control_type" generated="true" class="error" style="display:none;">This field is required.</label>
			</div>



			<input type="hidden" class="submitbtn" name="dbid" value="<?php echo base64_encode($dbid); ?>">
			<?php if(isset($edit)) {?>
			<input type="submit" class="submitbtn" id="button_for_search" name="update" value="Update">
			<input type="hidden" class="submitbtn" name="search_id" value="<?php echo $_GET['edit']; ?>">
			<?php } else{?>
			<input type="submit" id="button_for_search" class="submitbtn" name="getresults" value="Submit">
			<?php } ?>
			<em style="display:none;" id="error_name">Button is disabled please change 'label' of current search criteria</em>
		</form>

		</div>
	</fieldset>
	</div>
</div>
<script>
jQuery(document).ready(function(){
	current_value = jQuery('#group_name').val();
});
jQuery('#group_name').change(function(){
	
	var disabled;
	values = $(this).val();
	var json_array = $('#already_registered_search_name').html();
	json_array = jQuery.parseJSON(json_array);	
	$.each(json_array, function (index,value){		
		if(current_value!=values)
		{
			if(value==values)
			{
				alert('This Name is already occupied for current database\'s search criteria');
				$('#button_for_search').attr('disabled','disabled');
				disabled=true;
				$('#error_name').show();
			}
		}
	});
	if(typeof disabled!='boolean')
	{
		$('#button_for_search').removeAttr('disabled');
		$('#error_name').hide();
	}

})
</script>