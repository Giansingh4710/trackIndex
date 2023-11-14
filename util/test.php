<?php
$string = "https://keerat.xyz/Keertan/AkhandKeertan/?time=178&artist=Bhai+Mohinder+Singh+Ji+SDO&trackIndex=783";
$tok = strtok($string, "?");

while ($tok !== false) {
    echo "Word=$tok\n";
    $tok = strtok(" \n\t");
}
?>
