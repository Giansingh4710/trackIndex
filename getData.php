<?php
 header("Access-Control-Allow-Origin: *");
/* $arr=array("name"=>"Jon","age"=>23); */
/* echo json_encode($arr); */



$objLst = array();
if (($handle = fopen("data.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    if($data[0]=="TimeAdded") continue;
    /* TimeAdded,Artist,Description,time,link,ShabadId */
    $obj['timeAdded']=$data[0];
    $obj['keertani']=$data[1];
    $obj['description']=$data[2];
    $obj['timeStamp']=$data[3];
    $obj['link']=$data[4];
    $obj['shabadId']=$data[5];
    array_push($objLst,$obj);
  }
  fclose($handle);
}

echo json_encode($objLst);
?>

