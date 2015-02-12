<?php
/**
 * PHPSense Pagination Class
 *
 * PHP tutorials and scripts
 *
 * @package		PHPSense
 * @author		Jatinder Singh Thind
 * @copyright	Copyright (c) 2006, Jatinder Singh Thind
 * @link		http://www.phpsense.com
 */
 
// ------------------------------------------------------------------------

class PS_Pagination {
	var $php_self;
	var $rows_per_page	= 10; //Number of records to display per page
	var $total_rows		= 0; //Total number of rows returned by the query
	var $links_per_page	= 5; //Number of links to display per page
	var $append			= ""; //Paremeters to append to pagination links
	var $sql			= "";
	var $debug 			= false;
	var $conn			= false;
	var $page			= 1;
	var $max_pages		= 0;
	var $offset			= 0;
	
	/**
	 * Constructor
	 *
	 * @param resource $connection Mysql connection link
	 * @param string $sql SQL query to paginate. Example : SELECT * FROM users
	 * @param integer $rows_per_page Number of records to display per page. Defaults to 10
	 * @param integer $links_per_page Number of links to display per page. Defaults to 5
	 * @param string $append Parameters to be appended to pagination links 
	 */
	 
	function PS_Pagination($connection, $sql, $rows_per_page = 10, $links_per_page = 5, $append="") {
		$this->conn = $connection;
		$this->append= $append ;
		$this->sql = $sql;

		$this->rows_per_page = (int)$rows_per_page;
		if(intval($links_per_page) > 0) {
			$this->links_per_page = (int)$links_per_page;
		}
		else {
			$this->links_per_page = 5; 
		}
		$this->append = $append;
		$this->php_self = htmlspecialchars($_SERVER['PHP_SELF']);
		if(isset($_GET['page'])) {

			$this->page = intval($_GET['page']);
		}
	}
	
	/**
	 * Executes the SQL query and initializes internal variables
	 *
	 * @access public
	 * @return resource
	 */
	function paginate() {
		//Check for valid mysql connection


		if(!$this->conn || !is_resource($this->conn)) {
			if($this->debug) echo "MySQL connection missing<br />";
			return false;
		}
			

		$all_rs = @mysql_query($this->sql, $this->conn);
		if(!$all_rs) {
			if($this->debug) echo "SQL query failed. Check your query.<br /><br />Error Returned: ".mysql_error();
			return false;
		}
		$this->total_rows = mysql_num_rows($all_rs);
		@mysql_close($all_rs);
		
		//Max number of pages
		$this->max_pages = ceil($this->total_rows/$this->rows_per_page);
		if($this->links_per_page > $this->max_pages) {
			$this->links_per_page = $this->max_pages;
		}
		
		//Check the page value just in case someone is trying to input an aribitrary value
		if($this->page > $this->max_pages || $this->page <= 0) {
			$this->page = 1;
		}
		
		//Calculate Offset
		$this->offset = $this->rows_per_page * ($this->page-1);
		
		//Fetch the required result set
		$rs = @mysql_query($this->sql." LIMIT {$this->offset}, {$this->rows_per_page}", $this->conn) or die (mysql_error());
		if(!$rs) {
			if($this->debug) echo "Pagination query failed. Check your query.<br /><br />Error Returned: ".mysql_error();
			return false;
		}
		return $rs;
	}
	
	/**
	 * Display the link to the first page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'First'
	 * @return string
	 */
	function renderFirst($tag='') {
		$root="http://".$_SERVER['HTTP_HOST'];
	      $URL_SITE = $root;
		   $imgL='/images/arrowL.gif';
		if($this->page == 1) {
			return '<img src="'.$URL_SITE.$imgL.'"/>'."<span class='txtred pR5'> First </span>";
		}
		else {
			return '<a href="'.$this->php_self.'?page=1&'.$this->append.'" class="txtred pR5"><img src="'.$URL_SITE.$imgL.'"/> First   </a>';
		}
	}
	
	/**
	 * Display the link to the last page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
	 * @return string
	 */
	function renderLast($tag='Last') {
		 $root="http://maduhaa.com/";
	      $URL_SITE = $root;
		   $imgR='/images/arrowR.gif';
		if($this->page == $this->max_pages) {
		return "<span class='txtred pR5'>".$tag ."</span>".'<img src="'.$URL_SITE.$imgR.'"/>';
		}
		else {
			return '<a href="'.$this->php_self.'?page='.$this->max_pages.'&'.$this->append.'" class="txtred pR5">'.$tag.'</a>'.'<img src="'.$URL_SITE.$imgR.'"/>';
		}
	}
	
	/**
	 * Display the next link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '>>'
	 * @return string
	 */
	function renderNext($tag='Next') {
		 $root="http://maduhaa.com/";
	      $URL_SITE = $root;
		   $imgR='/images/arrowR.gif';
		if($this->page < $this->max_pages) {
			return '<li class="arrowR"><a href="'.$this->php_self.'?page='.($this->page+1).'&'.$this->append.'" class="txtred pR5">'.$tag.'</a>'.'</li>';
		}
		
	}
	
	/**
	 * Display the previous link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '<<'
	 * @return string
	 */
	function renderPrev($tag='Previous') {
		$root="http://".$_SERVER['HTTP_HOST'];
	      $URL_SITE = $root;
		   $imgL='/images/arrowL.gif';
		if($this->page > 1) {
			return '<li class="arrowL"><a href="'.$this->php_self.'?page='.($this->page-1).'&'.$this->append.'" class="txtred pR5">'.$tag.'</a></li>';
		}
	
	}
	
	/**
	 * Display the page links
	 *
	 * @access public
	 * @return string
	 */
	function renderNav() {
		$batch = @ceil($this->page/$this->links_per_page);
        $end = $batch * $this->links_per_page;
		if($end == $this->page) {
        	//$end = $end + $this->links_per_page - 1;
        	//$end = $end + ceil($this->links_per_page/2);
        }
		if($end > $this->max_pages) {
        	$end = $this->max_pages;
        }
        $start = $end - $this->links_per_page + 1;			
		$links = '';

		for( $i=$start ; $i <= $end ; $i++) {
			if($i == $this->page) {
				$links .= "<li><a class='selected'>$i</a></li> ";
			}
			else {
				$links .= '<li> <a href="'.$this->php_self.'?page='.$i.'&'.$this->append.'">'.$i.'</a></li> ';
			}
		}
		
		return $links;
	}
	
	/**
	 * Display full pagination navigation
	 *
	 * @access public
	 * @return string
	 */
	function renderFullNav() {
		return '<ul>'.$this->renderPrev().'&nbsp;'.$this->renderNav().'&nbsp;'.$this->renderNext().'</ul>';	
	}
	
	/**
	 * Set debug mode
	 *
	 * @access public
	 * @param bool $debug Set to TRUE to enable debug messages
	 * @return void
	 */
	function setDebug($debug) {
		$this->debug = $debug;
	}
}
?>
