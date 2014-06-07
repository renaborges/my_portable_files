<?php

// Store tag and value to debug.log file. This is useful for when the database
  // doesn't get updated correctly. Check the debug log file to see what values
  // tag and value had.

  // Setup a log file to help with debugging 
$myFile = "debug.log";

 //DATABASE DETAILS//
$DB_ADDRESS="localhost:3306";
$DB_USER="root";
$DB_PASS="";
$DB_NAME="androidapp";

//JSON settings//
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');
header('Content-Disposition: attachment; filename="JSON"');

//Connect to Database androidapp
$link = mysql_connect($DB_ADDRESS,$DB_USER,$DB_PASS);   //connect to the MYSQL database
mysql_select_db($DB_NAME,$link); 

// Openning the debug file to write in it.
$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh, "Starting here : ".PHP_EOL);

// Check if Uniform Resource Identifier (URI) is for a STORE or RETRIEVE
// URI string of characters used to identify a name of a web resource. Interaction with web resource over a network (typically the www)
$postUrl=$_SERVER["REQUEST_URI"];

// 'strpos' finds the position of the first occurrence of s string inside another string.
if(!strpos($postUrl,'storeavalue')){



// Retrieve surname from database when tag is provided
//$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh,"Retrieving data from MYSQL : ".PHP_EOL);

$forename=$_POST['tag'];
$forename = trim($forename);
 
//fwrite($fh,"forename : ".$forename.PHP_EOL);
// Prepare the query and get result from database
$query =  sprintf("select `forename`, `surname`, `temperature`, `pain` from `tinywebdb` where `forename` = '%s' limit 1", mysql_real_escape_string($forename));
fwrite($fh, "Query : ". $query.PHP_EOL);
if($link){ $result=mysql_query($query) ;}     
if($entry = mysql_fetch_assoc($result))
// mysql_fetch_assoc this function gets a row from the mysql_query() function and returns an array on success, or FALSE on failure or when there are no more rows.
{
	
    $forename = $entry["forename"];
    $surname = $entry["surname"];
	$temperature = $entry["temperature"];
	$pain = $entry["pain"];
	fwrite($fh,"Entry found in MYSQL : ". $forename.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $surname.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $temperature.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $pain.PHP_EOL);
	
} else {
	fwrite($fh,"No Entry found in MYSQL for name : ". mysql_real_escape_string($forename));

}

// Send result to JSON interface
echo json_encode(array("value", $forename, $surname, $temperature, $pain));


}
// Closes an open file
fclose($fh);