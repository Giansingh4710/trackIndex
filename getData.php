<?php
 header("Access-Control-Allow-Origin: *");
/* $arr=array("name"=>"Jon","age"=>23); */
/* echo json_encode($arr); */



$filename = "./data.csv";
$file = fopen( $filename, "r" );
if( $file == false ) {
  echo ( "Error in opening file" );
  exit();
}
$filesize = filesize( $filename );
$filetext = fread($file, $filesize);
fclose($file);

function printLiItem($str){
  $strLst = explode(",",$str);
  /* var_dump($strLst); */
  echo "<li><div class='eachTrack'>";
  echo "<div>Description: <p class='description'>" . $strLst[2] . "</p></div>";
  echo "<div>Keertani: <p class='artist'>" . $strLst[1] . "</p></div>";
  echo "<div>Time Stamp: <p class='timestamp'>" . $strLst[3] . "</p></div>";
  echo "<div>Link: <a class='link' href='$strLst[4]' target='_blank'></a></div>";

  $theShabadId = trim($strLst[5]);
  if (!empty($theShabadId)){
    echo "<details class='shabad'>";
    echo "<summary>Shabad Id:". $theShabadId."</summary>";
    echo "<p class='shabadDisplay' shabadId='$theShabadId'></p>";
    echo "</details>";
  }
  echo "</div></li>";
}

$lines = explode("\n",$filetext);
$linesCount = count($lines)-1; //-1 becuase PHP weird.
$objLst = array();
for($i=1;$i<$linesCount;$i++){
  /* TimeAdded,Artist,Description,time,link,ShabadId */
  $strLst = explode(",",$lines[$i]);
  $obj['timeAdded']=$strLst[0];
  $obj['keertani']=$strLst[1];
  $obj['description']=$strLst[2];
  $obj['timeStamp']=$strLst[3];
  $obj['link']=$strLst[4];
  $obj['shabadId']=$strLst[5];
  array_push($objLst,$obj);
}
echo json_encode($objLst);
?>

