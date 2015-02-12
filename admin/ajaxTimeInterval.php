<?php
/******************************************
* @Modified on January 9, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
require($basedir.'../../include/actionHeader.php');
if(isset($_GET['ids']) and $_GET['ids']!='null' and isset($_REQUEST['time_format']))
{
	$array_clm = '';
	$ids = $_GET['ids'];
	$ids = explode(',',$ids);
	$admin = new admin();

	$time_format = ($_REQUEST['time_format']!='')?trim($_REQUEST['time_format']):"SY-EY";

	if(isset($_REQUEST['dbid'])){
		$dbid = trim($_REQUEST['dbid']);
		$admin = new admin();
		$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);
		//echo "<pre>";
		//print_r($timeIntervalSettings);
		$columns = unserialize($timeIntervalSettings['columns']);
		//	print_r($columns);
		//die;
	}
?>

	<fieldset style="border: 1px solid #cccccc; padding: 10px; margin-bottom:20px;">
		<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">Select Start-End Year Columns To Be Shown As Values</legend>
	<?php
	foreach($ids as $key=>$table_name)
	{  
		//$array_clm.='<td>';
		$array_clm.='<fieldset style="border: 1px solid #cccccc; padding: 10px; margin-bottom:20px;">
						<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">'.$table_name.'</legend>';
				
		$array_clm.='<div style="height:auto;overflow:hidden;width:100%; margin-bottom:15px;">';
		$array_clm_res = $admin->showColumns($table_name);
		while($data = mysql_fetch_assoc($array_clm_res))
		{
			
			if($data['Extra']!='auto_increment')
			{
				$checked = '';
				if(isset($columns['years'][$table_name]) && in_array($data['Field'], $columns['years'][$table_name])){
					$checked = "checked";
				}

				$array_clm.='<div style="width:30%; float:left; padding-right:10px;">';
				 $array_clm.="<div><input class='' type='checkbox' value='".$data['Field']."' name='columns[years][".$table_name."][]' ".$checked."><label class='mL10'>".$data['Field']."</label></div>";
				$array_clm.='</div>';
			}
			
		}
	
		$array_clm.='</div>';
		
		$array_clm.='</fieldset>';
	}
	echo $array_clm;
	?>
	</fieldset>


	<?php 
	if(isset($_REQUEST['time_format']) && $_REQUEST['time_format'] == "SM-SY-EM-EY") { 
		$array_clm = '';
	?>
	<fieldset style="border: 1px solid #cccccc; padding: 10px; margin-bottom:20px;">
		<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">Select Start-End Month Columns To Be Shown As Values</legend>
	<?php
		foreach($ids as $key=>$table_name){  
			//$array_clm.='<td>';
			$array_clm.='<fieldset style="border: 1px solid #cccccc; padding: 10px; margin-bottom:20px;">
							<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">'.$table_name.'</legend>';
					
			$array_clm.='<div style="height:auto;overflow:hidden;width:100%; margin-bottom:15px;">';
			$array_clm_res = $admin->showColumns($table_name);
			while($data = mysql_fetch_assoc($array_clm_res)){
				
				if($data['Extra']!='auto_increment'){
					$checked = '';
					if(isset($columns['months'][$table_name]) && in_array($data['Field'], $columns['months'][$table_name])){
						$checked = "checked";
					}
					$array_clm.='<div style="width:30%; float:left; padding-right:10px;">';
					 $array_clm.="<div><input class='' type='checkbox' value='".$data['Field']."' name='columns[months][".$table_name."][]' ".$checked."><label class='mL10'>".$data['Field']."</label></div>";
					$array_clm.='</div>';
				}
				
			}
			$array_clm.='</div>';
			
			$array_clm.='</fieldset>';
		}

		echo $array_clm;
	?>
	</fieldset>
	<?php
	} 
	?>
	
	
<?php
}
else
{
	echo 'No tables were selected';
}
?>
