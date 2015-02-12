<?php
	$array_clm='';	
	foreach($search_criteria['related_tables'] as $key=>$table_name)
	{  
		if($table_name==null)
		{
			echo 'No Records Found for current search criteria';
			break;
		}
		$array_clm.='<div id="id_'.$table_name.'">';
		 $array_clm.='<fieldset style="border: 1px solid #cccccc; padding: 10px; margin-bottom:20px;">
						<legend  style="background: #cccccc; font-size: 14px; padding: 5px;">'.$table_name.'</legend>';				
		$array_clm.='<div style="height:auto;overflow:hidden;width:100%; margin-bottom:15px;">';
		$array_clm_res = $admin->showColumns($table_name);
		while($data = mysql_fetch_assoc($array_clm_res))
		{
			
			if($data['Extra']!='auto_increment')
			{
				$array_clm.='<div style="width:30%; float:left; padding-right:10px;">';
				
				foreach($search_criteria['search_criteria_coloums'] as $s_c)
				{
					if($s_c['coloum_name']==$data['Field'] and $s_c['belongs_to']==$table_name)
					{
						$checked = 'checked="checked"';
						break;
					}
					else
					{
						$checked='';
					}
				}
				 $array_clm.="<div><input class='' $checked type='radio' value='".$data['Field']."' name='".$table_name."[]'><label class='mL10'>".$data['Field']."</label></div>";
				$array_clm.='</div>';
			}
		}
	
		$array_clm.='</div>';
		
	$array_clm.='</fieldset>';
	$array_clm.='</div>';
	}
	echo $array_clm;
	foreach($getDbTables1 as $dbTables)				// making divs to show individual records of tables VIA ajax when user selects them
	{
		
		if(!in_array($dbTables['table_name'],$search_criteria['related_tables']))
		{
			echo '<div id="id_'.$dbTables['table_name'].'"></div>';
		}
	}
?>