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

// Check if URI is for a STORE or RETRIEVE

$postUrl=$_SERVER["REQUEST_URI"]; 

if(strpos($postUrl,'storeavalue')){
$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh,"Storing data to file : ".PHP_EOL);

$forename=$_POST['tag'];
$value = $_POST['value'];

// cleaning up strings by removing double quotes and square brackets
$forename = str_replace('"', '', $forename);
$value = str_replace('"', '', $value);

$value = str_replace('[', '', $value);
$value = str_replace(']', '', $value);



fwrite($fh, $forename." ");
fwrite($fh, $value.PHP_EOL);

//splitting the value variable and store in an array
fwrite($fh,"Split value variable into an array : ".PHP_EOL);
$value_array = explode(",", $value);

$surname = $value_array[0];
$temperature = $value_array[1];
$pain = $value_array[2];
fwrite($fh,"Forename : ".$forename.PHP_EOL);
fwrite($fh,"Surname : ".$surname.PHP_EOL);
fwrite($fh,"Temperature : ".$temperature.PHP_EOL);
fwrite($fh,"Pain : ".$pain.PHP_EOL);

fwrite($fh,"Storing data to MYSQL : ".PHP_EOL);

// Execute insert if tag does not exist
$query =  sprintf("insert into `tinywebdb` (`forename`, `surname`, `temperature`, `pain`) values ('%s', '%s', '%s', '%s')", 
mysql_real_escape_string($forename), 
mysql_real_escape_string($surname),
mysql_real_escape_string($temperature),
mysql_real_escape_string($pain));

mysql_query($query);
fwrite($fh,$query.PHP_EOL);
mysql_close();

fclose($fh);
 
}else {
// Retrieve value from database when tag is provided
$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh,"Retrieving data from MYSQL : ".PHP_EOL);

$forename=$_POST['tag'];
$forename = trim($forename); 

// Prepare the query and get result from database
$query =  sprintf("select `forename`, `surname`,`temperature` from `tinywebdb` where `forename` = '%s' limit 1", mysql_real_escape_string($forename));
fwrite($fh, "Query : ". $query.PHP_EOL);
if($link){ $result=mysql_query($query) ;}     
if($entry = mysql_fetch_assoc($result))
{
	
    $forename = $entry["forename"];
    $surname = $entry["surname"];
	$temperature = $entry["temperature"];
	fwrite($fh,"Entry found in MYSQL : ". $surname.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $temperature.PHP_EOL);
	
} else {
	fwrite($fh,"No Entry found in MYSQL for name : ". mysql_real_escape_string($forename));

}

// Send result to JSON interface
echo json_encode(array("VALUE", $forename, $surname, $temperature));
fclose($fh);

}

?>