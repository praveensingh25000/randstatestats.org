<?php
/*manage cms pages start
	pragati 3/14/2013
	function to get all parent cms pages 
	*/
class CmsPages
{
	public $dbObj; 
	public $query; 
	public $id; 
	public $temp_array = array(); 
	public $result; 
	public $resource; 
	
	//function to get all parent cms pages 
	function getParentPages(){
		global $db;
		$sql="select * from cmspages where parent_page_id>0";
		return $db->getAll($db->run_query($sql));	
	}
	function getchildPages($pageid)
	{
		$this->query="select * from cmspages where parent_page_id='".$pageid."'";
		return $this->dbObj->getArray($this->dbObj->select($this->query));	
	}

	// function to save page data
	 function savePage(){
		 global $db;
		$sql="insert into  cmspages set page_name='".mysql_real_escape_string($_POST['page_name'])."',url='".str_replace (' ', '',$_POST['url'])."',parent_page_id='".$_POST['parent_page']."',description='".mysql_real_escape_string($_POST['description'])."',keyword='".mysql_real_escape_string($_POST['keyword'])."',tag='".mysql_real_escape_string($_POST['tag'])."',is_active='".$_POST['active_status']."',location='".$_POST['location']."',added_on=now()";
		$result_set=$db->insert($sql, $conn='');
		return true;
	 }
	 //function to get all cms spages
	function allCmsPages(){
		global $db;
		$sql ="select * from cmspages";
		return $db->getAll($db->run_query($sql));
	}
	function getPage($page_id){
		global $db;
		$sql = "select * from cmspages where id='".$page_id."'";
		return $result = $db->getRow($sql, $conn= '');
	}
	 //function to delete page
	function deletePage($page_id){
		global $db;
		$sql="delete from cmspages where parent_page_id='".$page_id."'";
		//mysql_query($this->query);
		$db->run_query($sql);
		$sql2="delete from  cmspages where id='".$page_id."'";
		 return $db->run_query($sql2);
	}
	
	function updatePage($page_id){	
		global $db;
		if(isset($_POST['parent_page']))
		{
			$parentpage=$_POST['parent_page'];
		}else
		{
			$parentpage=0;
		}
		$sql = "update cmspages set page_name='".mysql_real_escape_string($_POST['page_name'])."',url='".str_replace (' ', '',$_POST['url'])."',parent_page_id='".$parentpage."',description='".mysql_real_escape_string($_POST['description'])."',keyword='".mysql_real_escape_string($_POST['keyword'])."',tag='".mysql_real_escape_string($_POST['tag'])."',is_active='".$_POST['active_status']."',location='".$_POST['location']."',modified_on=now() where id='".$page_id."'";

		if($db->update($sql, $conn = '')){
			return true;
		}else{
			return false;
		}
	}

	function changeStatus($page_id,$status){
		global $db;
		$sql="update cmspages set is_active='".$status."' where id='".$page_id."'";
		if($db->update($sql, $conn = ''))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//end manage cms pages
	//get general settings

	 //function to get settings
	function settings(){
		$this->query="select * from settings";
		return $this->dbObj->getArray($this->dbObj->select($this->query));
	}
	//function to update setting
	function updateSetting()
	{
		$this->query="update  settings set username='".mysql_real_escape_string($_POST['username'])."',email='".$_POST['email']."',phone='".$_POST['phone']."',faxnumber='".$_POST['fax']."',address='".mysql_real_escape_string($_POST['address'])."',sitename='".mysql_real_escape_string($_POST['sitename'])."',paypal_username='".$_POST['paypalemail']."',paypal_password='".$_POST['paypalpassword']."',signature='".$_POST['paypalsignature']."',modified_on=now()";
		if($this->dbObj->update($this->query))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//end
	//get all footer pages
	function getFooterPage(){
		$this->query="select * from cmspages where location=0 and parent_page_id=0 and status='1'";
		return $this->dbObj->getArray($this->dbObj->select($this->query));
	}	
	//get all footer pages
	function getHeaderPage(){
		$this->query="select * from cmspages where location=1 and parent_page_id=0 and status='1'";
		return $this->dbObj->getArray($this->dbObj->select($this->query));
	}
	//get child pages
	function childPages($parentid)//parent id to get child of this page
	{		
		$this->query="select * from cmspages where  status='1' and parent_page_id=".$parentid;
		return $this->dbObj->getArray($this->dbObj->select($this->query));
	}

}