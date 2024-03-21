<?php
 header("Access-Control-Allow-Origin: *");
/* $arr=array("name"=>"Jon","age"=>23); */
/* echo json_encode($arr); */



$objLst = array();
//$fp = fopen("dataNew.csv", "w");
if (($handle = fopen("data.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    //fputcsv($fp, $data); # $line is an array of strings (array|string[])
    // created,type,artist,timestamp,shabadID,description,link
    if($data[0]=="TimeAdded") continue;
    // $obj['timeAdded']=$data[0];
    // $obj['keertani']=$data[1];
    // $obj['description']=$data[2];
    // $obj['timeStamp']=$data[3];
    // $obj['link']=$data[4];
    // $obj['shabadId']=$data[5];

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
//fclose($fp);

echo json_encode($objLst);
?>
