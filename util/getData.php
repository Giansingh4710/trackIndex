<?php
 header("Access-Control-Allow-Origin: *");
/* $arr=array("name"=>"Jon","age"=>23); */
/* echo json_encode($arr); */


//echo "<pre>";
//var_dump($_GET);
//echo "</pre>";

$type = $_GET["type"];
$objLst = array();
//$fp = fopen("dataNew.csv", "w");
if (($handle = fopen("data.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if( !is_null($type) ){
      if( $data[1] != $type ){
        continue; 
      }
    }

    $obj['created']=$data[0];
    $obj['type']=$data[1];
    $obj['artist']=$data[2];
    $obj['timestamp']=$data[3];
    $obj['shabadID']=$data[4];
    $obj['description']=$data[5];
    $obj['link']=$data[6];
    array_push($objLst,$obj);
  }
  fclose($handle);
}

echo json_encode($objLst);
?>
