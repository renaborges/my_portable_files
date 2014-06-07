<?php

DEFINE ('DBUSER', 'root'); 
DEFINE ('DBPW', ''); 
DEFINE ('DBHOST', 'localhost:3306'); 
DEFINE ('DBNAME', 'androidapp'); 

$myFile = "debug.log";
$fh = fopen($myFile, 'a+') or die("can't open file");

$dbc = mysqli_connect(DBHOST,DBUSER,DBPW);
if (!$dbc) {
    fwrite($fh, "Cannot connect to Database : ".PHP_EOL);
    die("Database connection failed: " . mysqli_error($dbc));
    exit();
}

$dbs = mysqli_select_db($dbc, DBNAME);
if (!$dbs) {
    fwrite($fh, "Cannot select Database : ".PHP_EOL);
    die("Database selection failed: " . mysqli_error($dbc));
    exit(); 
}

$result = mysqli_query($dbc, "SHOW COLUMNS FROM tinywebdb");
$numberOfRows = mysqli_num_rows($result);

if ($numberOfRows > 0) {
fwrite($fh, "Rows Found in Database : ". $numberOfRows.PHP_EOL);
/* By changing Fred below to another specific persons name you can limit access to just the part of the database for that individual. You could eliminate WHERE recorder_id='Fred' all together if you want to give full access to everyone. */

$values = mysqli_query($dbc, "SELECT * FROM tinywebdb WHERE forename='Renata'");
fwrite($fh, "Values : ". $values.PHP_EOL);
while ($rowr = mysqli_fetch_row($values)) {
 for ($j=0;$j<$numberOfRows;$j++) {
  $csv_output .= $rowr[$j].", ";
  fwrite($fh, "row : ". $rowr[$j].PHP_EOL);
 }
 $csv_output .= "\n";
}

}

print $csv_output;
fwrite($csv_output, "Row contents : ".PHP_EOL);
fclose($fh);
exit;
?>