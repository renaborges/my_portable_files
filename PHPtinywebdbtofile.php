<?php 

$postUrl=$_SERVER["REQUEST_URI"]; 


if(strpos($postUrl,'storeavalue')){ 
  // Storing a Value
  
  // Get that tag
  //$id =$_POST['pat_id'];
  $name =$_POST['name'];
  $temp =$_POST['surname'];
  
#$tag=$_POST['tag'];
#$tagvalue = $_POST['value'];
#$tag = trim($tag); 
  // Get the value 
  //$tagvalue = trim($tagvalue); 

  // In this example, output to a file, but could use MySQL, or anything
  $myFile = "output1.txt";
  $fh = fopen($myFile, 'w') or die("can't open file");
  fwrite($fh, str_replace('"', '', $name));
  fwrite($fh, str_replace('"', '', $temp));
  fclose($fh);

} else { 
  // Retrieving a Value
#$tag=$_POST['tag'];
 # $tag = trim($tag); 

  $myFile = "output2.txt";
  $fh = fopen($myFile, 'r');
  $theData = fgets($fh);
  fclose($fh);

  if ($theData == "two times table") {
    $resultData = array("VALUE",$tag,array('1 x 2 = '=>2,'2 x 2 = '=>4,'3 x 2 = '=>6,'4 x 2 = '=>8,'5 x 2 ='=>10));
  } else {
    $resultData = array("VALUE",$tag,array($theData));
  }

  $resultDataJSON = json_encode($resultData); 
  echo $resultDataJSON; 

} 

?> 