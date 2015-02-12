<?php
/******************************************
* @Modified on June 10, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/
?>
<!-- FILE IS USED TO ADD EDIT A SEARCH CRITERIA IN ADMIN SECTION -->
<div class="containerLadmin">
	<div style="clear: both; padding: 15px 0;">
		<fieldset style="border: 1px solid #cccccc; padding: 10px;">
		<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Search Criteria for '".stripslashes($databaseDetail['db_name'])."'"; } else { echo "Add Database"; } ?> </legend>
	
		<?php include('formNavigation.php'); ?>
		<br class="clear" />
		<a class="right" href="<?php echo URL_SITE?>/admin/searchCriteria.php?tab=5&id=<?php echo base64_encode($dbid);?>&action=add">Add New Criteria</a>
		
		<?php
		if(isset($search_criteria_details) and !empty($search_criteria_details))
		{
			echo '<h4>Drag and drop the label in order to display in the form </h4>';
			echo '<br class="clear" />';
			echo '<div style="display:none;" class="pT5 pB10 txtcenter show_label_order_msg"></div>';
			echo "<table width='100%' cellspacing='0' cellpadding='5' border='1' class='collapse' id='table-sort-search-criteria'>";
			echo "<tr align='left' bgcolor='#eeeeee'><th>Order</th><th>Label</th><th>Type</th><th>Action</th></tr>";
			
			foreach($search_criteria_details as $s_detail) {
				echo "<tr id=".$s_detail['id'].">";
				echo "<td class='dragHandle' align='left'>".$s_detail['orderby']."</td>
					  <td class='dragHandle' align='left'>".stripslashes($s_detail['label_name'])."</td>
					  <td align='left'>".stripslashes(ucfirst($s_detail['type']))."</td>
					  <td align='left'>
					  <a href='".URL_SITE."/admin/searchCriteria.php?action=edit&edit=".base64_encode($s_detail['id'])."&tab=5&id=".base64_encode($dbid)."'>Edit</a>&nbsp;|&nbsp;
					  <a onclick='javascript:return confirm(\"Are You Sure?\")' href='".URL_SITE."/admin/searchCriteria.php?action=delete&delete=".base64_encode($s_detail['id'])."&tab=5&id=".base64_encode($dbid)."'>Delete</a>
					  </td>";
				echo "</tr>";
			} 
			echo "</table>";
		}
		else
		{
			echo 'No Search Criteria is added yet';
		}
		?>

		<SCRIPT LANGUAGE="JavaScript">
		jQuery(document).ready(function(){	
			$('#table-sort-search-criteria').tableDnD({
				onDrop: function(table, row) {
					var rows = table.tBodies[0].rows;
					var debugStr = ""
					for (var i=0; i<rows.length; i++) {
						debugStr += rows[i].id+" ";
					}
					loader_show();
					jQuery(".show_label_order_msg").hide();	
					jQuery.ajax({
						type: "POST",
						data: "tablename=search_criteria&coloumname=orderby&sortorder="+debugStr,
						url : URL_SITE+"/admin/adminAction.php",												
						success: function(msg){
							loader_unshow();
							jQuery(".show_label_order_msg").html(msg).show();
						}
					});																		
				},
				dragHandle: ".dragHandle"
			});
		});
		</SCRIPT>
		
	</fieldset>
	</div>
</div>