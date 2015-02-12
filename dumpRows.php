<?php
$fh = fopen('uspopraceage1_census.csv', 'r');

define('DATABASE_HOST', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', 'j0eN@t!on');
define('DATABASE_DB', 'rand_usa');

$conn =  mysql_connect( DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, true )or die('Error'.mysql_error());

mysql_select_db( DATABASE_DB ) or die('<b>Error: '.mysql_error().'</b>');

// Line number to start at (first is 0)
$start = 0;

if(isset($_GET['start'])){
	$start = $_GET['start'];
}

// Number of lines to get
$get = 30;
$message = '';
if ($fh)
{
    // Current line number (increments to 0 before first line is read)
     $line = -1;
     
     // Loops through every line before line $start + $get
     while ($line < $start + $get - 1 && !feof($fh))
     {
        // Increments the line counter
        $line++;
       
        // Gets the current line and moves the pointer ahead
        $line_contents = fgets($fh);
       
        if ($line < $start)
        {
            // Debugging only: Shows the lines that are skipped
           // $message .= "<div style='color:#CCC'>$line: $line_contents</div>\n";
           
            // Does not process any line before the start line
            continue;
        }
 
        // Process the "gotten" lines here
       
        // Debugging only: Shows the lines that are processed
         $lineArray = explode(",", $line_contents);
		
		$area = trim($lineArray[1], '"'); 
		$category =  trim($lineArray[2], '"'); 
		$cat1 =  trim($lineArray[3], '"'); 
		$year2012 = $lineArray[4]; 

		$sql = "select * from uspopraceage1_census where trim(Cat1) = '".$cat1."' and trim(Category) = '".$category."' and trim(Area) = '".$area."'";
		$resultCheck = mysql_query($sql);
		
		$message .= "CSV Line No. : ".$line."<br/>";
		$message .= "Checking: ".$sql."<br/>";
		if(mysql_num_rows($resultCheck)>0){
			$rowDb = mysql_fetch_assoc($resultCheck);

			$message .= "Total Rows Get From DB: ".mysql_num_rows($resultCheck)."<br/>";

			$sqlUpdate = "update uspopraceage1_census set Y2012 = $lineArray[4] where id = '".$rowDb['id']."'";

			$resultUpdate = mysql_query($sqlUpdate);

			$message .= "Updating: ".$sqlUpdate."<br/>";
		}

		$message .= "<br/>";
     }
	
	$start = ($start+$get);
}

$array = array('start' => $start, 'data' => $message);
echo json_encode($array);
?>