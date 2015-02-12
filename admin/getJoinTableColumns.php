<?php
/******************************************
* @Modified on January 9, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
require($basedir.'../../include/actionHeader.php');
if(isset($_REQUEST['primary_table']) and $_REQUEST['primary_table'] != '' and isset($_REQUEST['foreign_table']) and $_REQUEST['foreign_table'] != ''){
	
	$primary_table = $_REQUEST['primary_table'];
	$foreign_table = $_REQUEST['foreign_table'];
	$admin = new admin();
	if(isset($_REQUEST['dbid'])){
		$dbid = trim($_REQUEST['dbid']);	
	}
?>
	
	<div class="pB30">
		<label class="pB10" style="display:block"><b>Select Primary & Foreign Key Table Columns</b></label>
		<div class="clear">
			<div class="left pL10">
				<label class="pB10" style="display:block"><b>Primary Table Column</b></label>
				<select name="primary_table_column" id="primary_table_column" class="required">
					<option  value="">-- Select Primary Table Column --</option>
					<?php
					$array_clm_res = $admin->showColumns($primary_table);
					while($data = mysql_fetch_assoc($array_clm_res)){
						if($data['Extra']!='auto_increment'){
					?>
							<option  value="<?php echo $data['Field']; ?>"><?php echo $data['Field']; ?></option>
					<?php } 
					}?>
				</select>
			</div>
			<div class="left pL10">
				<label class="pB10" style="display:block"><b>Foreign Table Column</b></label>
				<select name="foreign_table_column" id="foreign_table_column" class="required">
					<option  value="">-- Select Foreign Table Column--</option>
					<?php
					$array_clm_res = $admin->showColumns($foreign_table);
					while($data = mysql_fetch_assoc($array_clm_res)){
						if($data['Extra']!='auto_increment'){
					?>
							<option  value="<?php echo $data['Field']; ?>"><?php echo $data['Field']; ?></option>
					<?php } 
					}?>
				</select>
			</div>

		</div>
		<div class="clear"></div>
	</div>
	
<?php
}
else
{
	echo 'No tables were selected';
}
?>
