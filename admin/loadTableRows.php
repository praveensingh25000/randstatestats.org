<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Saket Bisht
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
require($basedir.'../../include/actionHeader.php');
if(isset($_GET['ids']) and $_GET['ids']!='null')
{
	$array_clm = '';
	$ids = $_GET['ids'];
	$ids = explode(',',$ids);
	array_pop($ids);		// removing extra (comma ,)
	$admin = new admin();
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
				$array_clm.='<div style="width:30%; float:left; padding-right:10px;">';
				 $array_clm.="<div><input class='' type='radio' value='".$data['Field']."' name='".$table_name."[]'><label class='mL10'>".$data['Field']."</label></div>";
				$array_clm.='</div>';
			}
			
		}
	
		$array_clm.='</div>';
		//$array_clm.='</td>';
	$array_clm.='</fieldset>';
	}

	echo $array_clm;

}
else
{
	echo 'No tables were selected';
}
?>
