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

$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh, "Starting here : ".PHP_EOL);

// Check if URI is for a STORE or RETRIEVE

$postUrl=$_SERVER["REQUEST_URI"]; 

if(strpos($postUrl,'storeavalue')){

fwrite($fh,"Storing data to file : ".PHP_EOL);


$tag=$_POST['tag'];
$value = $_POST['value'];

// cleaning up strings by removing double quotes and square brackets
$value = str_replace('"', '', $value);
$value = str_replace('[', '', $value);
$value = str_replace(']', '', $value);


fwrite($fh, $value.PHP_EOL);

//splitting the value variable and store in an array
fwrite($fh,"Split value variable into an array : ".PHP_EOL);
$value_array = explode(",", $value);

$name = $value_array[0];
$temperature = $value_array[1];
$bloodps = $value_array[2];
$pain = $value_array[3];
$pulse = $value_array[4];

fwrite($fh,"name : ".$name.PHP_EOL);
fwrite($fh,"temperature : ".$temperature.PHP_EOL);
fwrite($fh,"bloodps : ".$bloodps.PHP_EOL);
fwrite($fh,"pain : ".$pain.PHP_EOL);
fwrite($fh,"pulse : ".$pulse.PHP_EOL);

fwrite($fh,"Storing data to MYSQL : ".PHP_EOL);

// Execute insert if tag does not exist
$query =  sprintf("insert into `tinywebdb` (`name`, `temperature`, `bloodps`, `pain`, `pulse`) values ('%s', '%s', '%s', '%s', '%s')", 
mysql_real_escape_string($name), 
mysql_real_escape_string($temperature),
mysql_real_escape_string($bloodps),
mysql_real_escape_string($pain),
mysql_real_escape_string($pulse));

mysql_query($query);


fwrite($fh,$query.PHP_EOL);
mysql_close();

fclose($fh);
 
}else {
// Retrieve temperature from database when tag is provided
//$fh = fopen($myFile, 'a+') or die("can't open file");
fwrite($fh,"Retrieving data from MYSQL : ".PHP_EOL);

$name=$_POST['tag'];
$name = trim($name);
 
// Prepare the query and get result from database
$query =  sprintf("select `name`, `temperature`, `bloodps`, `pain`, `pulse` from `tinywebdb` where `name` = '%s' limit 1", mysql_real_escape_string($name));


fwrite($fh, "Query : ". $query.PHP_EOL);

if($link){ $result=mysql_query($query) ;}     
if($entry = mysql_fetch_array($result))
{

	
    $name = $entry["name"];
    $temperature = $entry["temperature"];
	$bloodps = $entry["bloodps"];
	$pain = $entry["pain"];
	$pulse = $entry["pulse"];
	fwrite($fh,"Entry found in MYSQL : ". $name.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $temperature.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $bloodps.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $pain.PHP_EOL);
	fwrite($fh,"Entry found in MYSQL : ". $pulse.PHP_EOL);
	
	
	
} else {
	fwrite($fh,"No Entry found in MYSQL for name : ". mysql_real_escape_string($name));

}

// Send result to JSON interface
echo json_encode(array("value", $name, $temperature, $bloodps, $pain, $pulse));


}
fclose($fh);
?>