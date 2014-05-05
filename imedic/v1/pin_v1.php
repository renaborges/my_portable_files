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

$postUrl=$_SERVER["REQUEST_URI"]; 

$pin=$_POST['tag'];
$name = $_POST['value'];

// mysql_query executes a query on a MySQL database.
mysql_query($query);
fwrite($fh,$query.PHP_EOL);
mysql_close();
// mysql_close closes a non-persistent MySQL connection.
 
}else {
// Retrieve surname from database when tag is provided
//$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh,"Retrieving data from MYSQL : ".PHP_EOL);

$pin=$_POST['tag'];
$pin = trim($pin);
 
//fwrite($fh,"forename : ".$forename.PHP_EOL);
// Prepare the query and get result from database
$query =  sprintf("select `name` from `nursepin` where `pin` = '%s' limit 1", mysql_real_escape_string($pin));
fwrite($fh, "Query : ". $query.PHP_EOL);
if($link){ $result=mysql_query($query) ;}     
if($entry = mysql_fetch_assoc($result))
// mysql_fetch_assoc this function gets a row from the mysql_query() function and returns an array on success, or FALSE on failure or when there are no more rows.
{
	
    $pin = $entry["pin"];
    $name = $entry["name"];
	
	fwrite($fh,"Entry found in MYSQL : ". $pin.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $name.PHP_EOL);
	
	
} else {
	fwrite($fh,"No Entry found in MYSQL for name : ". mysql_real_escape_string($pin));

}

// Send result to JSON interface
echo json_encode(array("pin", $name));


}
// Closes an open file
fclose($fh);
?>