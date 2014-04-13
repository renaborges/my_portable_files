<?php 

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
mysql_select_db($DB_NAME,$link);                        //connect to the right DB
 
//
$postUrl=$_SERVER["REQUEST_URI"]; 
if(strpos($postUrl,'storeavalue')){ 

// Storing a Value
 
// Get that tag and value from android application
$tag=$_POST['tag'];

$tagvalue = $_POST['value'];
$tag = trim($tag); 

// Prepare query for storing
// Execute update if tag exists
$query =  sprintf("update `tinywebdb` set `value` = '%s' where `tag` = '%s'", 
    mysql_real_escape_string($tagvalue), 
    mysql_real_escape_string($tag));
if($link){ $result=mysql_query($query) ;}  

// Execute insert if tag does not exist
$query =  sprintf("insert into `tinywebdb` (`tag`, `value`) values ('%s', '%s')", 
    mysql_real_escape_string($tag), 
    mysql_real_escape_string($tagvalue));
if($link){ $result=mysql_query($query) ;}                    

// Send database storin to JSON interface
echo json_encode(array("STORED", $tag, $tagvalue));

// Retrieving a value from database

} else { 
  
// Retrieving a Value

// Get tag from request
$tag=$_POST['tag'];
$tag = trim($tag); 

// Prepare the query and get result from database
$query =  sprintf("select `tag`, `value` from `tinywebdb` where `tag` = '%s' limit 1", mysql_real_escape_string($tag));
if($link){ $result=mysql_query($query) ;}     
if($entry = mysql_fetch_assoc($result))
{
    $tag = $entry["tag"];
    $tagvalue = $entry["value"];
}

// Send result to JSON interface
echo json_encode(array("VALUE", $tag, $tagvalue));

} 

?> 