<?php
Class FishMenu {		//Class is use to make top navigation in frontend

	public $nav_array = array();
	public $sql;
	public $resource;
	public $data;
	public $nav_DB;
	public $URL_SITE;
	
	public $HTML='<ul class="sf-menu sf-js-enabled sf-shadow"><li><a href="javascript:;">Databases</a><ul>';

	public $HTML_FOOTER='</li></ul>';
	
	function __construct($URL_SITE){
		$this->URL_SITE = $URL_SITE.'/';
		$this->nav_array = $this->getNavMenu();
		return $this->nav_array.$this->HTML_FOOTER;
	}

	function getNavMenu(){

		$this->sql = 'SELECT * from categories where is_active=1 and parent_id=0';
		$this->resource = mysql_query($this->sql);
		while($this->data=mysql_fetch_object($this->resource))
		{
			$this->HTML.='<li class="">';				// opning li parent
			$this->HTML.='<a href="'.$this->URL_SITE.'forms.php?cat='.$this->data->id.'" class="sf-with-ul">';
			$this->HTML.= $this->data->category_title;		// Parent creates hr

			//$this->nav_DB = $this->getAllDatabase($this->data->id);	// get all DB related to current category
			$child = $this->is_child($this->data->id);			// get all childs related to parent menu
		
			$this->HTML.='</a>'; // closing anchor tag for parent
		
			if($child!=null)
			{			
				$this->HTML.='<ul>';  // opening ul tag if it is not present due to no DB available		
				$this->getChild($this->data->id);	// getting all sub menus
				$this->HTML.='</ul>';				// close ul for menu			
			}
			
			$this->HTML.='</li>';		//closing li parent
		}
		$this->HTML.='</ul>';		//closing ul parent
		echo $this->HTML;
	}

	function getChild($parent_id) {			// check for  child category
		$this->sql = "SELECT id  'child_id', category_title 'child_category_title', description 'child_description' FROM categories WHERE parent_id='".$parent_id."'";
		$resource = mysql_query($this->sql);
		
		if(mysql_num_rows($resource)>0)
		{			
			while($array_data = mysql_fetch_object($resource))
			{
				$this->HTML.='<li class="">
				<a href="'.$this->URL_SITE.'showDatabase.php?cat='.$array_data->child_id.'" class="sf-with-ul">'.$array_data->child_category_title.'</a>';	 
				if($this->is_child($array_data->child_id)!=null)				// gettting is their any child 
				{
					$this->HTML.='<ul>';
					$this->getChild($array_data->child_id);
					$this->HTML.='</ul>';	
				}
				$this->HTML.='</li>';
			}
		}
		else
		{
			return null;
		}
	}

	function is_child($parent_id){			// checks if child present or not
	
		$this->sql = "SELECT id  'child_id', category_title 'child_category_title', description 'child_description' FROM categories WHERE parent_id='".$parent_id."'";
		$resource = mysql_query($this->sql);
		if(mysql_num_rows($resource)>0)
		{	
			
			return true;
		}
		else
		{
			return null;
		}
	}

	function getAllDatabase($parent_id)	{		// check for DB related to category
	
		$this->sql = "SELECT d.*  from database_category as dc,`databases` as d where dc.category_id = '".$parent_id."' and dc.database_id=d.id and d.is_active	=1";
		$resource = mysql_query($this->sql);
		if(mysql_num_rows($resource)>0)
		{
			while($DB_data = mysql_fetch_object($resource))
			{
				$array[]=$DB_data;
			}

			return $array;
		}else
		{
			return null;
		}
	}
	

}